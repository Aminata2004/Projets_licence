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

    // Page mot de passe oublié, en une seule étape sans email : si l'adresse saisie
    // correspond à un compte, le champ nouveau mot de passe est affiché directement.
    // Aucune preuve de possession de la boîte mail n'est demandée (choix assumé pour cette
    // appli interne) : quiconque connaît l'email d'un compte peut réinitialiser son mot de
    // passe.
    public function mot_passe_oublie()
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                $data['error'] = "Session expirée, veuillez réessayer.";
                $this->view('admin/mot_passe_oublie', $data);
                return;
            }

            $email = trim($_POST['emailUser'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = "Veuillez entrer une adresse e-mail valide.";
            } else {
                $userModel = new Configuration();
                $user = $userModel->getByEmail($email);

                if (!$user) {
                    $data['error'] = "Aucun compte n'est associé à cette adresse email.";
                } elseif (isset($_POST['new_password'])) {
                    // Étape 2 : l'email a déjà été validé (champ caché du formulaire précédent).
                    $newPassword     = $_POST['new_password'];
                    $confirmPassword = $_POST['confirm_password'] ?? '';

                    if ($newPassword === '' || $newPassword !== $confirmPassword) {
                        $data['error']       = "Les mots de passe ne correspondent pas.";
                        $data['emailValide'] = $email;
                    } else {
                        $userModel->updatePassword1($email, password_hash($newPassword, PASSWORD_DEFAULT));
                        $userModel->set_flash("Votre mot de passe a été mis à jour avec succès.", "success");
                        header("Location: " . BASE_URL . "/admin/Loguins");
                        exit();
                    }
                } else {
                    // Étape 1 : email trouvé, on affiche le formulaire de nouveau mot de passe.
                    $data['emailValide'] = $email;
                }
            }
        }

        $this->view('admin/mot_passe_oublie', $data);
    }
}
