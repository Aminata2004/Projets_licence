<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Livraison_colis extends Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    public function index()
    {
        $colisModel = new Livraisons_colis();

        // 1) Livrer le colis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['livrer'])) {

            $idColis = (int)($_POST['id_colis'] ?? 0);
            if ($idColis <= 0) {
                $colisModel->set_flash('Colis introuvable.', 'danger');
            }

            $colis = $colisModel->getById($idColis);
            if (!$colis) {
                $colisModel->set_flash('Colis introuvable.', 'danger');
            }

            // Vérification des droits
            $droit      = $_SESSION['droit'] ?? '';
            $memeVille  = $colis['localite'] === ($_SESSION['ville'] ?? '');
            $memeGare   = $colis['numero_gare'] === ($_SESSION['numero_gare'] ?? '');
            $bonStatut  = $colis['status'] === 'recu';

            $peutLivrer = match ($droit) {
                'Admin_regionale' => $memeVille && $bonStatut,
                'utilisateur'     => $memeVille && $memeGare && $bonStatut,
                default           => false,
            };
            if (!$peutLivrer) {
                $colisModel->set_flash("Pas les droits pour livrer.", 'danger');
            }

            // Envoi mail avec PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'airbarry94@gmail.com';
                $mail->Password   = 'jzdmiazwxwjqhikg'; // mot de passe d’application
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('airbarry94@gmail.com', 'Airbarry');
                $mail->addAddress($colis['email_dest']);  // email destinataire
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);

                $date = date('d/m/Y');
                $mail->Subject = "Confirmation de réception de colis";
                $mail->Body = <<<HTML
<p><strong>À CONSERVER</strong></p>
<p>Madame, Monsieur,</p>
<p>Votre colis <strong>n° {$colis['id_colis']}</strong> a été remis avec succès à
<strong>{$colis['localite']}</strong> le <strong>$date</strong>.</p>
<p>Merci de votre confiance,<br>L’équipe Airbarry</p>
HTML;

                $mail->send();
            } catch (Exception $e) {
                error_log("PHPMailer error: " . $mail->ErrorInfo);
                $colisModel->set_flash("Le colis a été livré mais l'e-mail n'a pas pu être envoyé.", 'warning');
                // On peut choisir de continuer ou non ici, moi je continue
            }

            // Mise à jour du statut
            $updated = $colisModel->livrer($idColis);
            if (!$updated) {
                $colisModel->set_flash("Erreur lors de la mise à jour du statut.", 'danger');
            }

            $colisModel->set_flash("Colis livré avec succès !", 'primary');
        }

        // 2) Recherche de colis par code
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
            $code = trim($_POST['code'] ?? '');
            if ($code === '') {
                $colisModel->set_flash("Merci de renseigner un code colis.", 'danger');
            }

            $colis = $colisModel->findByCode($code);
            if (!$colis) {
                $colisModel->set_flash("Le code colis n’existe pas.", 'danger');
            }

            // Vérification droits
            $droit      = $_SESSION['droit'] ?? '';
            $memeVille  = $colis['localite'] === ($_SESSION['ville'] ?? '');
            $memeGare   = $colis['numero_gare'] === ($_SESSION['numero_gare'] ?? '');
            $bonStatut  = $colis['status'] === 'recu';

            $peutLivrer = match ($droit) {
                'Admin_regionale' => $memeVille && $bonStatut,
                'utilisateur'     => $memeVille && $memeGare && $bonStatut,
                default           => false,
            };

            // Affichage du formulaire avec infos colis + bouton livrer si autorisé
            $this->view('admin/livraison_colis', ['colis' => $colis, 'peutLivrer' => $peutLivrer]);
            return;
        }

        // 3) Page d’accueil du formulaire (aucun colis trouvé)
        $this->view('admin/livraison_colis', ['colis' => null, 'peutLivrer' => false]);
    }
}
