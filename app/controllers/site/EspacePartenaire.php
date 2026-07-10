<?php

class EspacePartenaire extends Controller
{
    // Formulaire de connexion / inscription (espace partenaire)
    public function login()
    {
        if (!empty($_SESSION['partenaire_id'])) {
            header("Location: " . BASE_URL . "/site/EspacePartenaire/discussion");
            exit;
        }

        $model = new Partenaire();

        if (isset($_POST['connexion'])) {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            $partenaire = $model->verifierConnexion($email, $motDePasse);
            if ($partenaire) {
                $_SESSION['partenaire_id'] = $partenaire->id_partenaire;
                $_SESSION['partenaire_nom'] = $partenaire->nom_compagnie;
                header("Location: " . BASE_URL . "/site/EspacePartenaire/discussion");
                exit;
            }

            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Connexion impossible', 'text' => 'Email ou mot de passe incorrect.'];
        }

        if (isset($_POST['inscription'])) {
            $nom_compagnie = trim($_POST['nom_compagnie'] ?? '');
            $email = trim($_POST['email_inscription'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $motDePasse = $_POST['mot_de_passe_inscription'] ?? '';

            if ($nom_compagnie === '' || $email === '' || $motDePasse === '') {
                $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Formulaire incomplet', 'text' => 'Veuillez remplir tous les champs obligatoires.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Email invalide', 'text' => "L'adresse email n'est pas valide."];
            } else {
                $partenaire = $model->creer([
                    'nom_compagnie' => $nom_compagnie,
                    'email' => $email,
                    'mot_de_passe' => $motDePasse,
                    'telephone' => $telephone
                ]);

                if (!$partenaire) {
                    $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Compte existant', 'text' => 'Un compte existe déjà avec cet email. Connectez-vous plutôt.'];
                } else {
                    $_SESSION['partenaire_id'] = $partenaire->id_partenaire;
                    $_SESSION['partenaire_nom'] = $partenaire->nom_compagnie;
                    header("Location: " . BASE_URL . "/site/EspacePartenaire/discussion");
                    exit;
                }
            }
        }

        $this->view('site/partenaire_login');
    }

    // Espace de discussion avec le super_admin
    public function discussion()
    {
        if (empty($_SESSION['partenaire_id'])) {
            header("Location: " . BASE_URL . "/site/EspacePartenaire/login");
            exit;
        }

        $messageModel = new Partenaire_message();

        if (isset($_POST['envoyer_message'])) {
            $texte = trim($_POST['message'] ?? '');
            if ($texte !== '') {
                $messageModel->envoyer($_SESSION['partenaire_id'], 'partenaire', $texte);
            }
            header("Location: " . BASE_URL . "/site/EspacePartenaire/discussion");
            exit;
        }

        $messages = $messageModel->getByPartenaire($_SESSION['partenaire_id']);

        $this->view('site/partenaire_discussion', [
            'messages' => $messages
        ]);
    }

    public function deconnexion()
    {
        unset($_SESSION['partenaire_id'], $_SESSION['partenaire_nom']);
        header("Location: " . BASE_URL . "/site/compagnies");
        exit;
    }
}
