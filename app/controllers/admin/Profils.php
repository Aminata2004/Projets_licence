<?php

class Profils extends Controller
{

    public function index()
    {
        $profil = new Add_billet;
        $bdd = $profil->connect();
        $req = $bdd->prepare("SELECT * FROM utilisateur  WHERE idUser= ?");
        $req->execute(array($_SESSION['id_utilisateur']));
        $info_user = $req->fetch();
        $this->view('admin/profile', ['info_user' => $info_user]);
    }
    public function changePassword()
    {
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
        $this->view('admin/activites');
    }

    // Page mot de passe oublié
    public function mot_passe_oublie()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emailUser'])) {
            $email = trim($_POST['emailUser']);

            // Validation email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userModel =  new Configuration();
                $user = $userModel->getByEmail($email);
                if ($user) {
                    // Redirection si l’email existe
                    header("Location: " . BASE_URL . "/admin/Profils/reset?emailUser=" . urlencode($email));
                    exit();
                } else {
                    $data['error'] = "Aucun compte trouvé pour cet email.";
                }
            } else {
                $data['error'] = "Veuillez entrer une adresse e-mail valide.";
            }
        }

        // Charger la vue
        $this->view('admin/mot_passe_oublie', isset($data) ? $data : []);
    }

    // Page reset password
    // public function reset()
    // {
    //     $email = $_GET['emailUser'] ?? null;
    //     $this->view('admin/change_password_oublie', ['emailUser' => $email]);
    // }

    // Page de réinitialisation
    public function reset()
    {
        // Vérification email dans l’URL
        if (!isset($_GET['emailUser']) || !filter_var($_GET['emailUser'], FILTER_VALIDATE_EMAIL)) {
            $data['error'] = "L'email est invalide ou manquant.";
            $this->view('admin/mot_passe_oublie', $data);
            return;
        }

        $email = $_GET['emailUser'];
        $userModel = new Configuration();
        $user = $userModel->getByEmail($email);

        if (!$user) {
            $data['error'] = "Cet email n'est pas associé à un compte valide.";
            $this->view('admin/mot_passe_oublie', $data);
            return;
        }

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword     = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword !== $confirmPassword) {
                $data['error'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Hashage du mot de passe avant enregistrement
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                if ($userModel->updatePassword1($email, $hashedPassword)) {
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
            'error'     => $data['error'] ?? null,
            'success'   => $data['success'] ?? null
        ]);
    }
}
