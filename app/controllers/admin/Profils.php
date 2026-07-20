<?php

class Profils extends Controller
{

    public function index()
    {
        $this->requireLogin();

        $profil = new Add_billet;
        $bdd = $profil->connect();
        $req = $bdd->prepare("SELECT * FROM utilisateur  WHERE idUser= ?");
        $req->execute(array($_SESSION['id_utilisateur']));
        $info_user = $req->fetch();
        $this->view('admin/profile', ['info_user' => $info_user]);
    }
    public function changePassword()
    {
        $this->requireLogin();

        $userModel = new Configuration();
        if (isset($_POST['changer_password'])) {
            $ancien_passe   = $_POST['ancien_password'];
            $new_passe      = $_POST['new_password'];
            $confirme_passe = $_POST['confirme_new_passe'];
            $idUser         = $_SESSION['id_utilisateur'];

            $info_user = $userModel->getUserById($idUser);

            if ($info_user && password_verify($ancien_passe, $info_user['motPasse'])) {
                if (!empty($new_passe) && $new_passe === $confirme_passe) {
                    $new_passe_hash = password_hash($new_passe, PASSWORD_DEFAULT);

                    if ($userModel->updatePassword($idUser, $new_passe_hash)) {
                        $userModel->set_flash('Le mot de passe a été mis à jour avec succès.', 'primary');
                    } else {
                        $userModel->set_flash('Erreur lors de la mise à jour du mot de passe.', 'danger');
                    }
                } else {
                    $userModel->set_flash('Les nouveaux mots de passe ne correspondent pas ou sont vides.', 'danger');
                }
            } else {
                $userModel->set_flash('Ancien mot de passe incorrect.', 'danger');
            }
        }

        // Affiche la vue
        $this->view('admin/pages_change_passe');
    }


    public function activites()
    {
        $this->requireLogin();

        $this->view('admin/activites');
    }

    // Page mot de passe oublié : génère un jeton à usage unique (30 min) et l'envoie par
    // email. Avant, cette page redirigeait directement vers reset() dès que l'email existait
    // en base, sans jamais prouver que le demandeur possède cette boîte mail.
    public function mot_passe_oublie()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emailUser'])) {
            $email = trim($_POST['emailUser']);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userModel = new Configuration();
                $user = $userModel->getByEmail($email);

                if ($user) {
                    $token = $userModel->creerTokenReset($email);
                    $lien = BASE_URL . "/admin/Profils/reset?emailUser=" . urlencode($email) . "&token=" . urlencode($token);
                    $this->envoyerEmailReset($email, $lien);
                }

                // Message identique que le compte existe ou non : ne révèle pas si un email
                // est associé à un compte (évite l'énumération des comptes existants).
                $data['success'] = "Si un compte existe avec cet email, un lien de réinitialisation vient d'être envoyé.";
            } else {
                $data['error'] = "Veuillez entrer une adresse e-mail valide.";
            }
        }

        $this->view('admin/mot_passe_oublie', isset($data) ? $data : []);
    }

    // Page de réinitialisation : exige un jeton valide (envoyé par email), pas seulement l'email.
    public function reset()
    {
        $email = $_GET['emailUser'] ?? ($_POST['emailUser'] ?? null);
        $token = $_GET['token'] ?? ($_POST['token'] ?? null);

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = "L'email est invalide ou manquant.";
            $this->view('admin/mot_passe_oublie', $data);
            return;
        }

        $userModel = new Configuration();

        // Le jeton doit correspondre à cet email et ne pas avoir expiré : c'est la seule
        // preuve acceptée que le demandeur contrôle réellement cette adresse.
        if (!$userModel->verifierTokenReset($email, $token)) {
            $data['error'] = "Ce lien de réinitialisation est invalide ou a expiré. Merci de refaire une demande.";
            $this->view('admin/mot_passe_oublie', $data);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword     = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword === '' || $newPassword !== $confirmPassword) {
                $data['error'] = "Les mots de passe ne correspondent pas.";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                if ($userModel->updatePassword1($email, $hashedPassword)) {
                    // Jeton à usage unique : détruit dès qu'il a servi.
                    $userModel->supprimerTokenReset($email);
                    $data['success'] = "Votre mot de passe a été mis à jour avec succès.";
                    header("Location: " . BASE_URL . "/admin/Loguins");
                    exit();
                } else {
                    $data['error'] = "Une erreur est survenue lors de la mise à jour.";
                }
            }
        }

        $this->view('admin/change_password_oublie', [
            'emailUser' => $email,
            'token'     => $token,
            'error'     => $data['error'] ?? null,
            'success'   => $data['success'] ?? null
        ]);
    }

    private function envoyerEmailReset($email, $lien)
    {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = MAIL_PORT;

            $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = 'Bonjour,<br><br>Cliquez sur ce lien pour réinitialiser votre mot de passe (valable 30 minutes) :<br>'
                . '<a href="' . htmlspecialchars($lien) . '">' . htmlspecialchars($lien) . '</a><br><br>'
                . "Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.";
            $mail->send();
        } catch (\Throwable $e) {
            // L'échec d'envoi ne doit pas révéler d'information ni bloquer la réponse générique.
        }
    }
}
