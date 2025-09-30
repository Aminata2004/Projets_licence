<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Reservation_formulaire extends Controller
{
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
                $prixTotal       = $_POST['montant_payer'];
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
                    $car = $programmeModel->fetchOne(
                        "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
                        [':num' => $idCarProgrammer]
                    );
                    if (!$car) throw new Exception("Car introuvable.");

                    $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
                    if ($nbPassagers > $placesDispo) throw new Exception("Places insuffisantes : $placesDispo restantes.");

                    $start = (int)$car['nbr_place_reserve'] + 1;
                    $end   = $start + $nbPassagers - 1;
                    $numPlace = ($nbPassagers == 1) ? "$start" : "$start-$end";
                }

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
                    $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE numero_car = :num");
                    $stmt->execute([':n' => $nbPassagers, ':num' => $idCarProgrammer]);
                }

                $pdo->commit();

                // --- 5️⃣ Affichage SweetAlert directement sur la page ---

                $successMessage = "Votre réservation a été enregistrée. Veuillez vérifier votre email pour finaliser et valider votre billet.";

                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            title: "🎉 Réservation réussie !",
            html: "<b>' . addslashes($successMessage) . '</b>",
            icon: "success",
            confirmButtonText: "Super !",
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
