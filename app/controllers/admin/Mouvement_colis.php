<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mouvement_colis extends Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    public function index()
    {
        $mouvement_colis = new Mouvements_colis();

        // Traitement de la réception de colis (formulaire envoyé)
        if (isset($_POST['reception'])) {

            if (empty($_POST['selected_colis'])) {
                $mouvement_colis->set_flash("Aucun colis sélectionné !", 'danger');
                header('Location: ' . BASE_URL . '/admin/mouvement_colis');
                exit;
            }

            $ids = array_map('intval', $_POST['selected_colis']);
            $model = new Mouvements_colis();
            $colis = $model->getColisPourReception($ids);

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'airbarry94@gmail.com';
                $mail->Password   = 'jzdmiazwxwjqhikg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->setFrom('airbarry94@gmail.com', 'Airbarry');
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);

                foreach ($colis as $c) {
                    $mail->clearAddresses();
                    $mail->addAddress($c['email_dest']);
                    $mail->Subject = 'Notification de mise à disposition de colis';
                    $mail->Body = <<<HTML
<strong>À CONSERVER</strong><br><br>
Madame, Monsieur,<br><br>
Votre colis <strong>n° {$c['id_colis']}</strong> est disponible à la gare <strong>{$c['numeroGare']}</strong> (<em>{$c['localite']}</em>).<br><br>
Frais : <strong>{$c['fraix_transaction']} FCFA</strong><br>
Code de retrait : <strong>{$c['code_colis']}</strong><br><br>
Merci de votre confiance,<br>L’équipe Airbarry
HTML;
                    $mail->send();
                    $model->marquerRecu((int)$c['id_colis']);
                }

                $mouvement_colis->set_flash("Colis marqués « reçu » et mails envoyés.", 'success');
            } catch (Exception $e) {
                error_log("Erreur PHPMailer : " . $mail->ErrorInfo);
                $mouvement_colis->set_flash("Une erreur est survenue lors de l'envoi des mails.", 'danger');
            }

            // ✅ Redirection après traitement POST
            header('Location: ' . BASE_URL . '/admin/mouvement_colis');
            exit;
        }

        // 💡 Ce bloc ne s'exécute que si ce n'est pas un POST
        $liste_colis = $mouvement_colis->FetchSelectColisEncours();
        // liste des colis recue
        $liste_colis_recue = $mouvement_colis->FetchSelectColisRecu();
        // liste des colis livre
        $liste_colis_livre = $mouvement_colis->FetchSelectColisLivre();
        $this->view('admin/mouvement_colis', ['liste_colis' => $liste_colis, 'liste_colis_recue' => $liste_colis_recue, 'liste_colis_livre' => $liste_colis_livre]);
    }
}
