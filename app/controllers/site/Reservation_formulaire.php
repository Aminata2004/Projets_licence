<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class Reservation_formulaire extends Controller
{
    // Fiche PDF du billet, accessible publiquement via son numéro (aucune vérification
    // supplémentaire requise, comme un ticket de cinéma) — utilisé pour le lien envoyé par WhatsApp.
    public function billet_pdf($numeroBillets = null)
    {
        $model = new Programme();

        if (!$numeroBillets) {
            http_response_code(404);
            exit('Billet introuvable.');
        }

        $billet = $model->fetchOne(
            "SELECT b.*, c.Client, c.montant_payer
             FROM billets b
             JOIN client c ON c.idClient = b.id_client
             WHERE b.numeroBillets = :num
             LIMIT 1",
            [':num' => $numeroBillets]
        );

        if (!$billet) {
            http_response_code(404);
            exit('Billet introuvable.');
        }

        $billet = (object)$billet;

        $compagnie = $model->fetchOne(
            "SELECT nom_compagnie, slogant, logo FROM compagnie WHERE id_compagnie = :id",
            [':id' => $billet->id_compagnie]
        ) ?: [];

        $logoPath = !empty($compagnie['logo']) ? ROOT . '/public/images/logos/' . $compagnie['logo'] : null;

        $qrData = "Nom: {$billet->Client}\n"
            . "Code: {$billet->numeroBillets}\n"
            . "Destination: {$billet->destinationId}\n"
            . "Place: {$billet->numeroPlace}";
        $qrResult = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->size(150)
            ->margin(6)
            ->build();
        $qrPath = "data:image/png;base64," . base64_encode($qrResult->getString());

        ob_start();
        include ROOT . '/app/views/admin/pdf/billet_client.php';
        $html = ob_get_clean();

        $this->streamThermalPdf($html, "billet_{$billet->numeroBillets}.pdf");
    }

    // public function index()
    // {
    //     $id = $_GET['id'] ?? null;
    //     $trajet = null;

    //     if ($id) {
    //         $programmeModel = new Programme(); // modèle Programme
    //         $programme = $programmeModel->findById($id); // Assure-toi que findById existe

    //         if ($programme) {
    //             $trajet = [
    //                 'depart' => $programme->idDepart,
    //                 'destination' => $programme->idDestination,
    //                 'heure_depart' => $programme->heureDepart,
    //                 'prix' => $programme->prix,
    //                 'id_compagnie' => $programme->id_compagnie,
    //                 'escales_avec_frais' => $programme->escales_avec_frais


    //             ];
    //         }
    //     }


    //     $this->view('site/formulaire_reservation', ['trajet' => $trajet]);
    // }
    public function index()
    {
        $programmeModel = new Programme();

        $id = $_GET['id'] ?? null;
        $trajet = null;

        if ($id) {
            // modèle Programme
            $programme = $programmeModel->findById($id); // méthode findById à créer

            if ($programme) {
                $trajet = [
                    'depart' => $programme->departLocalite,
                    'destination' => $programme->destinationLocalite,
                    'heure_depart' => $programme->heureDepart,
                    'prix' => $programme->prix,
                    'id_compagnie' => $programme->id_compagnie,
                    'escales_avec_frais' => $programme->escales_avec_frais,
                    'numeroGare1' => $programme->numeroGare1,
                    'numeroGare2' => $programme->numeroGare2,
                    'codeDepart' => $programme->codeDepart
                ];
            }
        }
        // reservation en ligne 

        if (isset($_POST['reserver'])) {
            if (!csrf_verify()) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            title: "⚠️ Session expirée",
            text: "Merci de réessayer votre réservation.",
            icon: "warning",
            confirmButtonText: "OK"
        });
    });
</script>';
                $this->view('site/formulaire_reservation', ['trajet' => $trajet]);
                return;
            }

            $pdo = $programmeModel->connect();
            $pdo->beginTransaction(); // Démarrer la transaction

            try {
                // --- 1️⃣ Récupération des données ---
                $departId        = $_POST['departId'];
                $idCompagnie     = $_POST['id_compagnie'];
                $destinationId   = $_POST['destinationId'];
                $numeroGare      = $_POST['numeroGare'];
                $escaleFinale    = $_POST['escale_finale'] ?? null;
                $destinationAEnregistrer = $escaleFinale ?: $destinationId;
                $jourVoyage      = date('Y-m-d', strtotime($_POST['jourVoyage']));
                $heureDepart     = $_POST['Heur_departs'];
                $nomClient       = $_POST['Client'];
                $nbPassagers     = (int)$_POST['nombrePassages'];
                $telephone       = $_POST['numeroClient'];
                $numeroPaiement  = $_POST['numeroPaiement'];
                $emailClient     = $_POST['emailClient'];
                $numeroBillets   = $_POST['numeroBillets'];
                $date_enregistrement = date('Y-m-d H:i:s');

                date_default_timezone_set('Africa/Bamako');
                $aujourdhui = date('Y-m-d');
                $demain     = date('Y-m-d', strtotime('+1 day'));

                // --- Vérification de la date ---
                if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
                    throw new Exception("Date invalide : choisissez aujourd’hui ou demain.");
                }

                // --- Calcul des places pour aujourd'hui ---
                $numPlace = '-';
                if ($jourVoyage == $aujourdhui) {
                    $rowProg = $programmeModel->fetchOne(
                        "SELECT id_car_programmer FROM programmation_voyage 
                 WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t 
                   AND localite_user = :l AND id_compagnie = :c LIMIT 1",
                        [':h' => $heureDepart, ':d' => $jourVoyage, ':t' => $destinationId, ':l' => $departId, ':c' => $idCompagnie]
                    );

                    if (!$rowProg) throw new Exception("Aucun car programmé pour cette heure et ce trajet.");

                    $idCarProgrammer = $rowProg['id_car_programmer'];
                    // SELECT ... FOR UPDATE : verrouille la ligne le temps de la transaction pour
                    // empêcher deux réservations simultanées de lire le même nbr_place_reserve et
                    // de faire du surbooking (race condition).
                    $stmtCar = $pdo->prepare("SELECT nbr_place, nbr_place_reserve FROM car WHERE id_car = :num LIMIT 1 FOR UPDATE");
                    $stmtCar->execute([':num' => $idCarProgrammer]);
                    $car = $stmtCar->fetch(PDO::FETCH_ASSOC);
                    if (!$car) throw new Exception("Car introuvable.");

                    $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
                    if ($nbPassagers > $placesDispo) throw new Exception("Places insuffisantes : $placesDispo restantes.");

                    $start = (int)$car['nbr_place_reserve'] + 1;
                    $end   = $start + $nbPassagers - 1;
                    $numPlace = ($nbPassagers == 1) ? "$start" : "$start-$end";
                }

                // --- Prix recalculé côté serveur (le montant_payer posté n'est qu'un aperçu JS,
                // falsifiable via les DevTools : on ignore toujours $_POST['montant_payer']). ---
                if (!$programme) {
                    throw new Exception("Trajet invalide.");
                }
                $prixUnitaire = (int) $programme->prix;
                if (!empty($escaleFinale)) {
                    $ligneEscale = $programmeModel->fetchOne(
                        "SELECT lt.prix_escale
                         FROM ligneTrajet lt
                         JOIN escale e ON e.id_escale = lt.id_escales
                         WHERE lt.id_trajets = :id_trajet AND lt.type_trajet = 'programmer' AND e.escales = :escale",
                        [':id_trajet' => $programme->idProgrammer, ':escale' => $escaleFinale]
                    );
                    if ($ligneEscale) {
                        $prixUnitaire = (int) $ligneEscale['prix_escale'];
                    }
                }
                $prixTotal = $prixUnitaire * $nbPassagers;

                // --- 2️⃣ Enregistrement client ---
                $stmt = $pdo->prepare(
                    "INSERT INTO client (Client, numeroClient, emailClient, date_enregistrement, montant_payer, numeroPaiement, id_compagnie)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([$nomClient, $telephone, $emailClient, $date_enregistrement, $prixTotal, $numeroPaiement, $idCompagnie]);
                $idClient = $pdo->lastInsertId();

                // --- 3️⃣ Enregistrement réservation ---
                $codeReservation = strtoupper(substr(md5(uniqid()), 0, 8));
                $expiration      = date('Y-m-d', strtotime($jourVoyage . ' +1 week'));
                $delait_reservation = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                $stmt = $pdo->prepare(
                    "INSERT INTO billets (id_client, status_reservation, validation_billets, date_reservation, date_expiration, 
                                  nombrePassages, numeroBillets, jourVoyage, Heur_departs, destinationId, departId, numeroPlace, 
                                  id_compagnie, num_gare, delait_reservation)
             VALUES (?, 'en_ligne', 'en_attente', NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([
                    $idClient,
                    $expiration,
                    $nbPassagers,
                    $numeroBillets,
                    $jourVoyage,
                    $heureDepart,
                    $destinationAEnregistrer,
                    $departId,
                    $numPlace,
                    $idCompagnie,
                    $numeroGare,
                    $delait_reservation
                ]);

                // --- 4️⃣ Mise à jour places si voyage aujourd'hui ---
                if ($jourVoyage == $aujourdhui) {
                    $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE id_car = :num");
                    $stmt->execute([':n' => $nbPassagers, ':num' => $idCarProgrammer]);
                }

                $pdo->commit();

                // --- 5️⃣ Affichage SweetAlert directement sur la page ---

                // Lien public vers la fiche PDF du billet + email de rappel de paiement.
                $lienPdf = BASE_URL . "/site/Reservation_formulaire/billet_pdf/" . rawurlencode($numeroBillets);

                $emailEnvoye = null;
                if (!empty($emailClient)) {
                    try {
                        $compagnie = $programmeModel->fetchOne(
                            "SELECT nom_compagnie FROM compagnie WHERE id_compagnie = :id",
                            [':id' => $idCompagnie]
                        ) ?: [];

                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = MAIL_HOST;
                        $mail->SMTPAuth   = true;
                        $mail->Username   = MAIL_USERNAME;
                        $mail->Password   = MAIL_PASSWORD;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = MAIL_PORT;

                        $nomCompagnie = $compagnie['nom_compagnie'] ?? 'Billetterie';

                        $mail->setFrom(MAIL_USERNAME, $nomCompagnie);
                        $mail->addAddress($emailClient);
                        $mail->isHTML(true);
                        $mail->Subject = "🎟️ Réservation enregistrée - $numeroBillets";
                        $mail->Body    = '
                            <div style="font-family:Segoe UI,Arial,sans-serif;max-width:480px;margin:auto;">
                                <div style="background:#0f3b5e;color:#fff;padding:16px 20px;border-radius:10px 10px 0 0;text-align:center;">
                                    <h2 style="margin:0;font-size:18px;">' . htmlspecialchars($nomCompagnie) . '</h2>
                                </div>
                                <div style="border:1px solid #e5e7eb;border-top:none;border-radius:0 0 10px 10px;padding:20px;">
                                    <p style="font-size:16px;margin:0 0 14px;">Bonjour <strong>' . htmlspecialchars($nomClient) . '</strong>, votre réservation est enregistrée ✅</p>
                                    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:16px;">
                                        <tr><td style="padding:6px 0;color:#6b7280;">N° billet</td><td style="padding:6px 0;font-weight:bold;text-align:right;">' . htmlspecialchars($numeroBillets) . '</td></tr>
                                        <tr><td style="padding:6px 0;color:#6b7280;">Destination</td><td style="padding:6px 0;font-weight:bold;text-align:right;">' . htmlspecialchars($destinationAEnregistrer) . '</td></tr>
                                        <tr><td style="padding:6px 0;color:#6b7280;">Date</td><td style="padding:6px 0;font-weight:bold;text-align:right;">' . date('d/m/Y', strtotime($jourVoyage)) . '</td></tr>
                                        <tr><td style="padding:6px 0;color:#6b7280;">Heure</td><td style="padding:6px 0;font-weight:bold;text-align:right;">' . htmlspecialchars($heureDepart) . '</td></tr>
                                    </table>
                                    <div style="background:#fff7ed;border-left:4px solid #f59e0b;padding:12px 14px;border-radius:6px;font-size:14px;color:#92400e;">
                                        ⏱️ <strong>Paiement Orange Money sous 30 minutes</strong><br>Sinon la réservation sera automatiquement annulée.
                                    </div>
                                </div>
                            </div>
                        ';
                        $mail->send();

                        $emailEnvoye = true;
                    } catch (Exception $e) {
                        $emailEnvoye = false;
                    }
                }

                $htmlConfirmation = '<b>Votre réservation a été enregistrée.</b><br><br>';
                if ($emailEnvoye === true) {
                    $htmlConfirmation .= '<small>📧 Un email de confirmation vous a été envoyé. '
                        . 'Merci de payer via Orange Money dans les 30 minutes, sinon la réservation sera automatiquement annulée.</small>';
                } elseif ($emailEnvoye === false) {
                    $htmlConfirmation .= '<small>⚠️ Impossible d\'envoyer l\'email de confirmation. '
                        . 'Merci de payer via Orange Money dans les 30 minutes, sinon la réservation sera automatiquement annulée.</small>';
                } else {
                    $htmlConfirmation .= '<small>Aucune adresse email fournie. '
                        . 'Merci de payer via Orange Money dans les 30 minutes, sinon la réservation sera automatiquement annulée.</small>';
                }

                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            title: "🎉 Réservation réussie !",
            html: ' . json_encode($htmlConfirmation) . ',
            icon: "success",
            confirmButtonText: "Fermer",
            confirmButtonColor: "#3085d6",
            background: "#fefefe",
            color: "#333",
            showClass: {
                popup: "animate__animated animate__fadeInDown"
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp"
            }
        });
    });
</script>';
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                // Affichage de l’erreur directement
                // $errorMessage = "Erreur lors de la réservation : " . htmlspecialchars($e->getMessage());
                // echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                // echo '<script>
                //         document.addEventListener("DOMContentLoaded", function () {
                //             Swal.fire({
                //                 title: "Erreur",
                //                 text: ' . json_encode($errorMessage) . ',
                //                 icon: "error",
                //                 confirmButtonText: "OK"
                //             });
                //         });
                //       </script>';


                $errorMessage = "Erreur lors de la réservation : " . htmlspecialchars($e->getMessage());

                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            title: "⚠️ Oups, une erreur !",
            html: "<i>' . addslashes($errorMessage) . '</i>",
            icon: "error",
            confirmButtonText: "Réessayer",
            confirmButtonColor: "#d33",
            background: "#fff0f0",
            color: "#721c24",
            showClass: {
                popup: "animate__animated animate__shakeX"
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp"
            }
        });
    });
</script>';
            }
        }


        // Passe les infos à la vue
        $this->view('site/formulaire_reservation', ['trajet' => $trajet]);
    }
}
