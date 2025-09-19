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
                    'depart' => $programme->idDepart,
                    'destination' => $programme->idDestination,
                    'heure_depart' => $programme->heureDepart,
                    'prix' => $programme->prix,
                    'id_compagnie' => $programme->id_compagnie,
                    'escales_avec_frais' => $programme->escales_avec_frais
                ];
            }
        }
        /// reservation en ligne
        // if (isset($_POST['reserver'])) {
        //     // 1️⃣ Récupérer les données du formulaire
        //     $departId = $_POST['departId'];
        //     $idCompagnie = $_POST['id_compagnie'];
        //     $destinationId = $_POST['destinationId'];
        //     $escaleFinale = $_POST['escale_finale'] ?? null;
        //     $destinationAEnregistrer = $escaleFinale ?: $destinationId;

        //     $jourVoyage = date('Y-m-d', strtotime($_POST['jourVoyage']));
        //     $heureDepart = $_POST['Heur_departs'];
        //     $nomClient = $_POST['Client'];
        //     $nbPassagers = (int)$_POST['nombrePassages'];
        //     $telephone = $_POST['numeroClient'];
        //     $numeroPaiement = $_POST['numeroPaiement'];
        //     $emailClient = $_POST['emailClient'];
        //     $prixTotal = $_POST['montant_payer'];
        //     $codeMarchand = $_POST['code_marchand'];
        //     $numeroBillets = $_POST['numeroBillets'];
        //     $date_enregistrement = date('Y-m-d H:i:s');
        //     date_default_timezone_set('Africa/Bamako');
        //     $aujourdhui = date('Y-m-d');
        //     $demain = date('Y-m-d', strtotime('+1 day'));

        //     if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
        //         $programmeModel->set_toast_top(
        //             "Erreur",
        //             "Date invalide : choisissez aujourd’hui ou demain.",
        //             "error",
        //             "#dc3545"
        //         );
        //         return false;
        //     }

        //     $car = null;
        //     $idCarProgrammer = null;
        //     $numPlace = '-';

        //     // Pour aujourd'hui
        //     if ($jourVoyage == $aujourdhui) {
        //         $rowProg = $programmeModel->fetchOne(
        //             "SELECT id_car_programmer FROM programmation_voyage
        //     WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
        //     AND localite_user = :l AND id_compagnie = :c LIMIT 1",
        //             [
        //                 ':h' => $heureDepart,
        //                 ':d' => $jourVoyage,
        //                 ':t' => $destinationId,
        //                 ':l' => $departId,
        //                 ':c' => $idCompagnie
        //             ]
        //         );

        //         if (!$rowProg) {
        //             $programmeModel->set_toast_top(
        //                 "Erreur",
        //                 "Aucun car programmé pour cette heure et ce trajet.",
        //                 "error",
        //                 "#dc3545"
        //             );
        //             return false;
        //         }

        //         $idCarProgrammer = $rowProg['id_car_programmer'];

        //         $car = $programmeModel->fetchOne(
        //             "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
        //             [':num' => $idCarProgrammer]
        //         );

        //         if (!$car) {
        //             $programmeModel->set_toast_top(
        //                 "Erreur",
        //                 "Car introuvable.",
        //                 "error",
        //                 "#dc3545"
        //             );
        //             return false;
        //         }

        //         $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
        //         if ($nbPassagers > $placesDispo) {
        //             $programmeModel->set_toast_top(
        //                 "Erreur",
        //                 "Places insuffisantes : $placesDispo restantes.",
        //                 "error",
        //                 "#dc3545"
        //             );
        //             return false;
        //         }

        //         $start = (int)$car['nbr_place_reserve'] + 1;
        //         $end = $start + $nbPassagers - 1;
        //         $numPlace = ($nbPassagers == 1) ? "$start" : "$start-$end";
        //     }

        //     try {
        //         $pdo = $programmeModel->connect();
        //         $pdo->beginTransaction();

        //         // Enregistrer le client
        //         $stmt = $pdo->prepare(
        //             "INSERT INTO client (Client, numeroClient, emailClient, date_enregistrement, montant_payer, numeroPaiement, id_compagnie)
        //      VALUES (?, ?, ?, ?, ?, ?, ?)"
        //         );
        //         $stmt->execute([$nomClient, $telephone, $emailClient, $date_enregistrement, $prixTotal, $numeroPaiement, $idCompagnie]);
        //         $idClient = $pdo->lastInsertId();

        //         // Traitement pour demain
        //         if ($jourVoyage == $demain) {
        //             $stmt = $pdo->prepare("SELECT place_minumale FROM place_minumale LIMIT 1");
        //             $stmt->execute();
        //             $rowPlace = $stmt->fetch();
        //             $placeTotale = $rowPlace ? (int)$rowPlace['place_minumale'] : 0;

        //             if ($placeTotale <= 0) {
        //                 $pdo->rollBack();
        //                 $programmeModel->set_toast_top(
        //                     "Erreur",
        //                     "Nombre de places minimales non défini.",
        //                     "error",
        //                     "#dc3545"
        //                 );
        //                 return false;
        //             }

        //             // ... Reste du code pour vérifier et insérer dans suivis ...
        //         }

        //         // Générer un code unique
        //         $codeReservation = strtoupper(substr(md5(uniqid()), 0, 8));
        //         $expiration = date('Y-m-d', strtotime($jourVoyage . ' +1 week'));
        //         $delait_reservation = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        //         // Enregistrer la réservation
        //         $sqlBillet = "INSERT INTO billets
        //     (id_client, status_reservation, validation_billets, date_reservation, date_expiration, nombrePassages, numeroBillets, jourVoyage, Heur_departs, destinationId, departId, numeroPlace, id_compagnie, delait_reservation)
        //     VALUES (?, 'en_ligne', 'en_attente', NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //         $stmt = $pdo->prepare($sqlBillet);
        //         $stmt->execute([
        //             $idClient,
        //             $expiration,
        //             $nbPassagers,
        //             $numeroBillets,
        //             $jourVoyage,
        //             $heureDepart,
        //             $destinationAEnregistrer,
        //             $departId,
        //             $numPlace,
        //             $idCompagnie,
        //             $delait_reservation
        //         ]);
        //         $pdo->commit();

        //         // Message de succès avec instruction pour vérifier l'email
        //         $programmeModel->set_toast_top(
        //             "Réservation en attente",
        //             "Votre réservation est en attente de paiement. Veuillez vérifier votre email pour finaliser et valider votre billet.",
        //             null, // pas de couleur
        //             BASE_URL . "/site/Accueil"
        //         );



        //         // Envoi email avec PHPMailer
        //         $mail = new PHPMailer(true);
        //         try {
        //             $mail->isSMTP();
        //             $mail->Host       = 'smtp.gmail.com';
        //             $mail->SMTPAuth   = true;
        //             $mail->Username   = 'airbarry94@gmail.com';
        //             $mail->Password   = 'jzdmiazwxwjqhikg';
        //             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        //             $mail->Port       = 587;
        //             $mail->setFrom('airbarry94@gmail.com', 'Airbarry');
        //             $mail->CharSet = 'UTF-8';
        //             $mail->isHTML(true);

        //             $mail->addAddress($emailClient);
        //             $mail->Subject = "Action requise : Confirmez votre réservation";
        //             $mail->Body = <<<HTML
        //             <h2>Confirmez votre réservation</h2>
        //             <p>Pour finaliser votre réservation, veuillez effectuer le paiement via le numéro suivant :</p>
        //             <h3>$numeroPaiement</h3>
        //             <p>Ensuite, envoyez le code de réservation <strong>$codeReservation</strong> au même numéro.</p>
        //             <p>⚠️ Vous avez 30 minutes pour finaliser le paiement. Sans paiement dans ce délai, votre réservation sera annulée.</p>
        //             <p>Merci de votre confiance !</p>
        //             HTML;
        //             $mail->send();
        //         } catch (Exception $e) {
        //             $programmeModel->set_toast_top(
        //                 "Erreur",
        //                 "Erreur lors de l'envoi de l'email : " . htmlspecialchars($mail->ErrorInfo),
        //                 "error",
        //                 "#dc3545"
        //             );
        //         }

        //         // Mise à jour des places pour aujourd'hui
        //         if ($jourVoyage == $aujourdhui) {
        //             $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE numero_car = :num");
        //             $stmt->execute([':n' => $nbPassagers, ':num' => $idCarProgrammer]);
        //         }
        //     } catch (Exception $e) {
        //         if ($pdo->inTransaction()) $pdo->rollBack();
        //         $programmeModel->set_toast_top(
        //             "Erreur",
        //             "Erreur lors de la réservation : " . htmlspecialchars($e->getMessage()),
        //             "error",
        //             "#dc3545"
        //         );
        //     }
        // }

if (isset($_POST['reserver'])) {
    $pdo = $programmeModel->connect();
    $pdo->beginTransaction(); // Démarrer la transaction

    try {
        // --- 1️⃣ Récupération des données ---
        $departId        = $_POST['departId'];
        $idCompagnie     = $_POST['id_compagnie'];
        $destinationId   = $_POST['destinationId'];
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
                [':h'=>$heureDepart, ':d'=>$jourVoyage, ':t'=>$destinationId, ':l'=>$departId, ':c'=>$idCompagnie]
            );

            if (!$rowProg) throw new Exception("Aucun car programmé pour cette heure et ce trajet.");

            $idCarProgrammer = $rowProg['id_car_programmer'];
            $car = $programmeModel->fetchOne(
                "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
                [':num'=>$idCarProgrammer]
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
        $codeReservation = strtoupper(substr(md5(uniqid()),0,8));
        $expiration      = date('Y-m-d', strtotime($jourVoyage . ' +1 week'));
        $delait_reservation = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $stmt = $pdo->prepare(
            "INSERT INTO billets (id_client, status_reservation, validation_billets, date_reservation, date_expiration, 
                                  nombrePassages, numeroBillets, jourVoyage, Heur_departs, destinationId, departId, numeroPlace, 
                                  id_compagnie, delait_reservation)
             VALUES (?, 'en_ligne', 'en_attente', NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $idClient, $expiration, $nbPassagers, $numeroBillets, $jourVoyage, $heureDepart, 
            $destinationAEnregistrer, $departId, $numPlace, $idCompagnie, $delait_reservation
        ]);

        // --- 4️⃣ Mise à jour places si voyage aujourd'hui ---
        if ($jourVoyage == $aujourdhui) {
            $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE numero_car = :num");
            $stmt->execute([':n'=>$nbPassagers, ':num'=>$idCarProgrammer]);
        }

        $pdo->commit();

        // --- 5️⃣ Affichage SweetAlert directement sur la page ---
        $successMessage = "Votre réservation a été enregistrée. Veuillez vérifier votre email pour finaliser et valider votre billet.";
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        title: "Réservation en attente",
                        text: ' . json_encode($successMessage) . ',
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                });
              </script>';

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // Affichage de l’erreur directement
        $errorMessage = "Erreur lors de la réservation : " . htmlspecialchars($e->getMessage());
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        title: "Erreur",
                        text: ' . json_encode($errorMessage) . ',
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                });
              </script>';
    }
}


        // Passe les infos à la vue
        $this->view('site/formulaire_reservation', ['trajet' => $trajet]);
    }
}
