<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;


class Liste_ententes extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $model = new Liste_entente();
        $liste_entente = $model->liste_entente();

        $this->view('admin/liste_entente', ['liste_entente' => $liste_entente]);
    }


    // public function validation($id_reservation = null)
    // {
    //     $model = new Liste_entente();

    //     if (!$id_reservation) {
    //         header('Location: ' . BASE_URL . '/admin/Liste_ententes?error=missing_id');
    //         exit;
    //     }

    //     // 1️⃣ Récupération des infos du billet
    //     $listeticked_validations = $model->getTicketById($id_reservation);

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {
    //         $numeroConfirme = $_POST['confirme'] ?? '';

    //         if ($numeroConfirme === $listeticked_validations->numeroPaiement) {
    //             // 2️⃣ Mise à jour du statut du billet
    //             $sql = "UPDATE billets SET validation_billets = 'valider' WHERE idBillets = :id";
    //             $stmt = $model->connect()->prepare($sql);
    //             $stmt->bindParam(':id', $id_reservation, PDO::PARAM_INT);
    //             $stmt->execute();

    //             // 2️⃣b️⃣ Récupération des informations de la compagnie
    //             $stmtCompagnie = $model->connect()->prepare("
    //                     SELECT nom, adresse, tel, email 
    //                     FROM compagnies 
    //                     WHERE id = :id_compagnie
    //                 ");
    //             $stmtCompagnie->bindParam(':id_compagnie', $listeticked_validations->id_compagnie, PDO::PARAM_INT);
    //             $stmtCompagnie->execute();
    //             $compagnie = $stmtCompagnie->fetch(PDO::FETCH_OBJ);

    //             // 3️⃣ Génération du QR Code avec le numéro de place inclus
    //             $qrData = "Nom: {$listeticked_validations->Client}\n"
    //                 . "Code: {$listeticked_validations->numeroBillets}\n"
    //                 . "Destination: {$listeticked_validations->destinationId}\n"
    //                 . "Place: {$listeticked_validations->numero_place}\n"
    //                 . "Compagnie: {$compagnie->nom}";
    //             $qrResult = Builder::create()
    //                 ->writer(new PngWriter())
    //                 ->data($qrData)
    //                 ->size(150)
    //                 ->margin(6)
    //                 ->build();

    //             $qrBase64 = base64_encode($qrResult->getString());
    //             $qrPath = "data:image/png;base64," . $qrBase64;

    //             // 4️⃣ Génération du HTML du billet avec les infos compagnie
    //             $billet = (array)$listeticked_validations;
    //             $billet['qrPath'] = $qrPath;
    //             $billet['compagnie'] = $compagnie; // infos compagnie pour PDF

    //             ob_start();
    //             include ROOT . '/app/views/admin/pdf/billet_client.php';
    //             $html = ob_get_clean();

    //             // 5️⃣ Génération du PDF
    //             $options = new Options();
    //             $options->setIsRemoteEnabled(true);
    //             $dompdf = new Dompdf($options);
    //             $dompdf->loadHtml($html);
    //             $dompdf->setPaper('A6', 'portrait');
    //             $dompdf->render();
    //             $pdfContent = $dompdf->output();

    //             // 6️⃣ Envoi de l'email avec le PDF en pièce jointe
    //             $mail = new PHPMailer(true);
    //             try {
    //                 $mail->isSMTP();
    //                 $mail->Host       = 'smtp.gmail.com';
    //                 $mail->SMTPAuth   = true;
    //                 $mail->Username   = 'airbarry94@gmail.com';
    //                 $mail->Password   = 'jzdmiazwxwjqhikg';
    //                 $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //                 $mail->Port       = 587;

    //                 $mail->setFrom('airbarry94@gmail.com', $compagnie->nom); // nom de la compagnie
    //                 $mail->addAddress($listeticked_validations->emailClient);
    //                 $mail->isHTML(true);
    //                 $mail->Subject = "Votre billet est validé !";
    //                 $mail->Body    = "
    //                     Bonjour {$listeticked_validations->Client},<br>
    //                     Votre billet numéro <strong>{$listeticked_validations->numeroBillets}</strong> pour la destination 
    //                     <strong>{$listeticked_validations->destinationId}</strong> a été validé.<br>
    //                     Numéro de place : <strong>{$listeticked_validations->numero_place}</strong>.<br>
    //                     <strong>Compagnie :</strong> {$compagnie->nom}<br>
    //                     Adresse : {$compagnie->adresse}<br>
    //                     Tél : {$compagnie->tel}<br>
    //                     Email : {$compagnie->email}<br><br>
    //                     Veuillez trouver votre billet en pièce jointe.<br><br>
    //                     Merci de voyager avec {$compagnie->nom} !
    //                 ";

    //                 $mail->addStringAttachment($pdfContent, "Billet_{$listeticked_validations->numeroBillets}.pdf");

    //                 $mail->send();
    //                 $model->set_flash("Succès", "Email avec billet PDF envoyé au client.", "success");
    //             } catch (Exception $e) {
    //                 $model->set_flash(
    //                     "Erreur",
    //                     "Erreur lors de l'envoi de l'email : " . htmlspecialchars($mail->ErrorInfo),
    //                     "danger"
    //                 );
    //             }

    //             header('Location: ' . BASE_URL . '/admin/Liste_ententes?success=validated');
    //             exit;
    //         } else {
    //             $error = "Le numéro de paiement ne correspond pas !";
    //         }
    //     }

    //     // 7️⃣ Affichage de la page de validation
    //     $this->view('admin/valider_billets', [
    //         'listeticked_validations' => $listeticked_validations,
    //         'error' => $error ?? null
    //     ]);
    // }

    // public function validation($id_reservation = null)
    // {
    //     $model = new Liste_entente();

    //     if (!$id_reservation) {
    //         header('Location: ' . BASE_URL . '/admin/Liste_ententes?error=missing_id');
    //         exit;
    //     }

    //     // 1️⃣ Récupération des infos du billet
    //     $listeticked_validations = $model->getTicketById($id_reservation);

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {
    //         $numeroConfirme = $_POST['confirme'] ?? '';

    //         if ($numeroConfirme === $listeticked_validations->numeroPaiement) {
    //             // 2️⃣ Mise à jour du statut du billet
    //             // 2️⃣ Mise à jour du statut du billet et de l'utilisateur qui valide
    //             $sql = "UPDATE billets 
    //             SET validation_billets = 'valider', idUser = :idUser 
    //             WHERE idBillets = :id";

    //             $stmt = $model->connect()->prepare($sql);
    //             $stmt->bindParam(':idUser', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
    //             $stmt->bindParam(':id', $id_reservation, PDO::PARAM_INT);
    //             $stmt->execute();


    //             // 2️⃣b️⃣ Récupération des informations de la compagnie
    //             $stmtCompagnie = $model->connect()->prepare("
    //             SELECT nom_compagnie, libele, slogant, logo
    //             FROM compagnie
    //             WHERE id_compagnie = :id_compagnie
    //              ");
    //             $stmtCompagnie->bindParam(':id_compagnie', $listeticked_validations->id_compagnie, PDO::PARAM_INT);
    //             $stmtCompagnie->execute();
    //             $compagnie = $stmtCompagnie->fetch(PDO::FETCH_ASSOC);


    //             // 2️⃣c️⃣ Récupération du nom de l'agent qui a validé
    //             $stmtAgent = $model->connect()->prepare("
    //             SELECT utilisateurs
    //             FROM utilisateur
    //             WHERE idUser = :idUser
    //         ");
    //             $stmtAgent->bindParam(':idUser', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
    //             $stmtAgent->execute();
    //             $agent = $stmtAgent->fetch(PDO::FETCH_ASSOC);


    //             // 3️⃣ Génération du QR Code
    //             $qrData = "Nom: {$listeticked_validations->Client}\n"
    //                 . "Code: {$listeticked_validations->numeroBillets}\n"
    //                 . "Destination: {$listeticked_validations->destinationId}\n"
    //                 . "Place: {$listeticked_validations->numero_place}\n"
    //                 . "Compagnie: {$compagnie['nom']}\n"
    //                 . "Départ: " . ($_SESSION['ville'] ?? '-') . "\n"
    //                 . "Montant: " . ($listeticked_validations->montant_payer ?? '-');

    //             $qrResult = Builder::create()
    //                 ->writer(new PngWriter())
    //                 ->data($qrData)
    //                 ->size(150)
    //                 ->margin(6)
    //                 ->build();
    //             $qrBase64 = base64_encode($qrResult->getString());
    //             $qrPath = "data:image/png;base64," . $qrBase64;

    //             // 4️⃣ Préparation du billet pour PDF
    //             $billet = $listeticked_validations; // objet
    //             $billet->qrPath = $qrPath;
    //             $billet->compagnie = (object)$compagnie;
    //             $billet->agent = $agent['utilisateurs'] ?? '-';


    //             // 5️⃣ Génération du HTML du billet
    //             ob_start();
    //             include ROOT . '/app/views/admin/pdf/billet_client.php';
    //             $html = ob_get_clean();

    //             // 6️⃣ Génération du PDF
    //             $options = new Options();
    //             $options->setIsRemoteEnabled(true);
    //             $dompdf = new Dompdf($options);
    //             $dompdf->loadHtml($html);
    //             $dompdf->setPaper('A6', 'portrait');
    //             $dompdf->render();
    //             $pdfContent = $dompdf->output();

    //             // 7️⃣ Envoi de l'email avec le PDF en pièce jointe
    //             $mail = new PHPMailer(true);
    //             try {
    //                 $mail->isSMTP();
    //                 $mail->Host       = 'smtp.gmail.com';
    //                 $mail->SMTPAuth   = true;
    //                 $mail->Username   = 'airbarry94@gmail.com';
    //                 $mail->Password   = 'jzdmiazwxwjqhikg'; // mot de passe d'application
    //                 $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //                 $mail->Port       = 587;

    //                 $mail->setFrom('airbarry94@gmail.com', $billet->compagnie->nom);
    //                 $mail->addAddress($billet->emailClient);
    //                 $mail->isHTML(true);
    //                 $mail->Subject = "Votre billet est validé !";
    //                 $mail->Body    = "
    //                 Bonjour {$billet->Client},<br>

    //                 Veuillez trouver votre billet en pièce jointe.<br><br>
    //                 Merci de voyager avec {$billet->compagnie->nom_compagnie} !
    //             ";

    //                 $mail->addStringAttachment($pdfContent, "Billet_{$billet->numeroBillets}.pdf");
    //                 $mail->send();
    //                 // $model->set_flash("Succès", "Email avec billet PDF envoyé au client.", "success");

    //                 // ✅ Message SweetAlert après validation
    //                 echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    //                 echo '<script>
    //     document.addEventListener("DOMContentLoaded", function () {
    //         Swal.fire({
    //             title: "La validation a été faite avec succès",
    //             icon: "success",
    //             confirmButtonText: "OK"
    //         }).then(() => {
    //             window.location.href = "' . BASE_URL . '/admin/Liste_ententes.php";
    //         });
    //     });
    // </script>';
    //                 exit;
    //             } catch (Exception $e) {
    //                 $model->set_flash(
    //                     "Erreur",
    //                     "Erreur lors de l'envoi de l'email : " . htmlspecialchars($mail->ErrorInfo),
    //                     "danger"
    //                 );
    //             }

    //             header('Location: ' . BASE_URL . '/admin/Liste_ententes?success=validated');
    //             exit;
    //         } else {
    //             $error = "Le numéro de paiement ne correspond pas !";
    //         }
    //     }

    //     // 8️⃣ Affichage de la page de validation
    //     $this->view('admin/valider_billets', [
    //         'listeticked_validations' => $listeticked_validations,
    //         'error' => $error ?? null
    //     ]);
    // }


    // public function validation($id_reservation = null)
    // {
    //     $model = new Liste_entente();

    //     // if (!$id_reservation) {
    //     //     header('Location: ' . BASE_URL . '/admin/Liste_ententes?error=missing_id');
    //     //     exit;
    //     // }

    //     // 1️⃣ Récupération des infos du billet
    //     $listeticked_validations = $model->getTicketById($id_reservation);

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {
    //         $numeroConfirme = $_POST['confirme'] ?? '';

    //         if ($numeroConfirme === $listeticked_validations->numeroPaiement) {
    //             $pdo = $model->connect();
    //             $pdo->beginTransaction(); // Démarrer la transaction

    //             try {
    //                 // 2️⃣ Mise à jour du statut du billet et de l'utilisateur qui valide
    //                 $sql = "UPDATE billets 
    //                     SET validation_billets = 'valider', idUser = :idUser 
    //                     WHERE idBillets = :id";
    //                 $stmt = $pdo->prepare($sql);
    //                 $stmt->bindParam(':idUser', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
    //                 $stmt->bindParam(':id', $id_reservation, PDO::PARAM_INT);
    //                 $stmt->execute();

    //                 // 3️⃣ Récupération infos compagnie
    //                 $stmtCompagnie = $pdo->prepare("
    //                 SELECT nom_compagnie, libele, slogant, logo
    //                 FROM compagnie
    //                 WHERE id_compagnie = :id_compagnie
    //             ");
    //                 $stmtCompagnie->bindParam(':id_compagnie', $listeticked_validations->id_compagnie, PDO::PARAM_INT);
    //                 $stmtCompagnie->execute();
    //                 $compagnie = $stmtCompagnie->fetch(PDO::FETCH_ASSOC);

    //                 // 4️⃣ Récupération nom de l'agent
    //                 $stmtAgent = $pdo->prepare("
    //                 SELECT utilisateurs
    //                 FROM utilisateur
    //                 WHERE idUser = :idUser
    //             ");
    //                 $stmtAgent->bindParam(':idUser', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
    //                 $stmtAgent->execute();
    //                 $agent = $stmtAgent->fetch(PDO::FETCH_ASSOC);

    //                 // 5️⃣ Génération du QR Code
    //                 $qrData = "Nom: {$listeticked_validations->Client}\n"
    //                     . "Code: {$listeticked_validations->numeroBillets}\n"
    //                     . "Destination: {$listeticked_validations->destinationId}\n"
    //                     . "Place: {$listeticked_validations->numeroPlace}\n"
    //                     . "Compagnie: {$compagnie['nom_compagnie']}\n"
    //                     . "Départ: " . ($_SESSION['ville'] ?? '-') . "\n"
    //                     . "Montant: " . ($listeticked_validations->montant_payer ?? '-');

    //                 $qrResult = Builder::create()
    //                     ->writer(new PngWriter())
    //                     ->data($qrData)
    //                     ->size(150)
    //                     ->margin(6)
    //                     ->build();
    //                 $qrBase64 = base64_encode($qrResult->getString());
    //                 $qrPath = "data:image/png;base64," . $qrBase64;

    //                 // 6️⃣ Préparation du billet pour PDF
    //                 $billet = $listeticked_validations; // objet
    //                 $billet->qrPath = $qrPath;
    //                 $billet->compagnie = (object)$compagnie;
    //                 $billet->agent = $agent['utilisateurs'] ?? '-';

    //                 // 7️⃣ Génération du HTML du billet
    //                 ob_start();
    //                 include ROOT . '/app/views/admin/pdf/billet_client.php';
    //                 $html = ob_get_clean();

    //                 // 8️⃣ Génération du PDF
    //                 $options = new Options();
    //                 $options->setIsRemoteEnabled(true);
    //                 $dompdf = new Dompdf($options);
    //                 $dompdf->loadHtml($html);
    //                 $dompdf->setPaper('A6', 'portrait');
    //                 $dompdf->render();
    //                 $pdfContent = $dompdf->output();

    //                 // 9️⃣ Envoi de l'email
    //                 $mail = new PHPMailer(true);
    //                 try {
    //                     $mail->isSMTP();
    //                     $mail->Host       = 'smtp.gmail.com';
    //                     $mail->SMTPAuth   = true;
    //                     $mail->Username   = 'airbarry94@gmail.com';
    //                     $mail->Password   = 'jzdmiazwxwjqhikg';
    //                     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //                     $mail->Port       = 587;

    //                     $mail->setFrom('airbarry94@gmail.com', $billet->compagnie->nom_compagnie);
    //                     $mail->addAddress($billet->emailClient);
    //                     $mail->isHTML(true);
    //                     $mail->Subject = "Votre billet est validé !";
    //                     $mail->Body    = "
    //                     Bonjour {$billet->Client},<br>
    //                     Veuillez trouver votre billet en pièce jointe.<br><br>
    //                     Merci de voyager avec {$billet->compagnie->nom_compagnie} !
    //                 ";
    //                     $mail->addStringAttachment($pdfContent, "Billet_{$billet->numeroBillets}.pdf");
    //                     $mail->send();
    //                 } catch (Exception $e) {
    //                     // 🚨 Email échoué → rollback et message utilisateur
    //                     $pdo->rollBack();

    //                     $model->set_toast_top(
    //                         "Erreur",
    //                         "Problème réseau : l'email n'a pas pu être envoyé. Veuillez réessayer plus tard.",
    //                         "danger"
    //                     );
    //                     header('Location: ' . BASE_URL . '/admin/Liste_ententes');
    //                     exit;
    //                 }

    //                 // === Alimentation de la caisse ===
    //                 $stmt = $pdo->prepare("
    //         SELECT c.id_caisse, c.montant_billets
    //         FROM caisse c
    //         INNER JOIN agence a ON c.id_agence = a.idAgence
    //         WHERE c.id_compagnie = :id_compagnie
    //           AND a.localite = :ville
    //            AND a.numeroGare = :numeroGare

    //         LIMIT 1
    //     ");
    //                 $stmt->execute([
    //                     ':id_compagnie' => $_SESSION['id_compagnie'],
    //                     ':ville'        => $_SESSION['ville'],
    //                     ':numeroGare'   => $_SESSION['numero_gare']

    //                 ]);
    //                 $caisse = $stmt->fetch(PDO::FETCH_ASSOC);

    //                 if ($caisse) {
    //                     $stmtUpdate = $pdo->prepare("
    //             UPDATE caisse
    //             SET montant_billets = montant_billets + :montant
    //             WHERE id_caisse = :id_caisse
    //         ");
    //                     $stmtUpdate->execute([
    //                     ':montant'   => $listeticked_validations->montant_payer,
    //                     ':id_caisse' => $caisse['id_caisse']
    //                     ]);
    //                 }

    //                 // ✅ Tout est OK → commit
    //                 $pdo->commit();

    //                 // Message SweetAlert après validation
    //                 echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    //                 echo '<script>
    //                 document.addEventListener("DOMContentLoaded", function () {
    //                     Swal.fire({
    //                         title: "La validation a été faite avec succès",
    //                         icon: "success",
    //                         confirmButtonText: "OK"
    //                     }).then(() => {
    //                         window.location.href = "' . BASE_URL . '/admin/Liste_ententes";
    //                     });
    //                 });
    //             </script>';
    //             } catch (Exception $e) {
    //                 if ($pdo->inTransaction()) $pdo->rollBack();
    //                 $model->set_flash(
    //                     "Erreur",
    //                     "Erreur lors de la validation : " . htmlspecialchars($e->getMessage()),
    //                     "danger"
    //                 );
    //                 header('Location: ' . BASE_URL . '/admin/Liste_ententes');
    //                 exit;
    //             }
    //         } else {
    //             $error = "Le numéro de paiement ne correspond pas !";
    //         }
    //     }

    //     // 10️⃣ Affichage de la page de validation
    //     $this->view('admin/valider_billets', [
    //         'listeticked_validations' => $listeticked_validations,
    //         'error' => $error ?? null
    //     ]);
    // }

    public function validation($id_reservation = null)
    {
        $model = new Liste_entente();

        // 0️⃣ Vérifier que l'ID est fourni
        if (!$id_reservation) {
            header('Location: ' . BASE_URL . '/admin/Liste_ententes?error=missing_id');
            exit;
        }

        // 1️⃣ Récupération des infos du billet
        $listeticked_validations = $model->getTicketById($id_reservation);

        if (!$listeticked_validations) {
            $model->set_flash("Erreur", "Billet introuvable !", "danger");
            header('Location: ' . BASE_URL . '/admin/Liste_ententes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {

            $numeroConfirme = trim($_POST['confirme'] ?? '');

            // 2️⃣ Vérifier le numéro de paiement
            if ($numeroConfirme === $listeticked_validations->numeroPaiement) {

                $pdo = $model->connect();
                $pdo->beginTransaction(); // Démarrer la transaction

                try {
                    // 3️⃣ Mise à jour du billet
                    $sql = "UPDATE billets 
                        SET validation_billets = 'valider', idUser = :idUser 
                        WHERE idBillets = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':idUser', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id_reservation, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() === 0) {
                        throw new Exception("Erreur : billet non mis à jour !");
                    }
                    // var_dump($listeticked_validations->montant_payer);exit;

                    // 4️⃣ Mise à jour de la caisse
                    $stmtCaisse = $pdo->prepare("
                    SELECT c.id_caisse, c.montant_billets
                    FROM caisse c
                    INNER JOIN agence a ON c.id_agence = a.idAgence
                    WHERE c.id_compagnie = :id_compagnie
                      AND a.localite = :ville
                      AND a.numeroGare = :numeroGare
                    LIMIT 1
                ");
                    $stmtCaisse->execute([
                        ':id_compagnie' => $_SESSION['id_compagnie'],
                        ':ville'        => $_SESSION['ville'],
                        ':numeroGare'   => $_SESSION['numero_gare']
                    ]);
                    $caisse = $stmtCaisse->fetch(PDO::FETCH_ASSOC);

                    if (!$caisse) {
                        throw new Exception("Erreur : caisse non trouvée !");
                    }

                    $stmtUpdate = $pdo->prepare("
                    UPDATE caisse
                    SET montant_billets = montant_billets + :montant
                    WHERE id_caisse = :id_caisse
                ");
                    // Nettoyer le montant pour garder uniquement les chiffres
                    $montant = (int) preg_replace('/\D/', '', $listeticked_validations->montant_payer);

                    $stmtUpdate->execute([
                        ':montant'   => $montant,
                        ':id_caisse' => $caisse['id_caisse']
                    ]);

                    if ($stmtUpdate->rowCount() === 0) {
                        throw new Exception("Erreur : mise à jour de la caisse échouée !");
                    }

                    // ✅ Tout est OK → commit
                    $pdo->commit();

                         echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            Swal.fire({
                                title: "La validation a été faite avec succès",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.href = "' . BASE_URL . '/admin/Liste_ententes.php";
                            });
                        });
                    </script>';
                    exit;
                 
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    $model->set_flash("Erreur", $e->getMessage(), "danger");
                    header('Location: ' . BASE_URL . '/admin/Liste_ententes');
                    exit;
                }
            } else {
                $model->set_flash("Erreur", "Le numéro de paiement ne correspond pas !", "warning");
                header('Location: ' . BASE_URL . '/admin/Liste_ententes');
                exit;
            }
        }

        // 5️⃣ Affichage de la page de validation
        $this->view('admin/valider_billets', [
            'listeticked_validations' => $listeticked_validations
        ]);
    }
}
