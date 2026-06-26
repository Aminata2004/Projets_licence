<?php
class Loguin extends Model
{
    public function connecter()
    {
        // Récupération des champs email et mot de passe du formulaire
        $emailUser = filter_var($_POST['emailUser'] ?? null, FILTER_SANITIZE_EMAIL);
        $motPasse = htmlspecialchars($_POST['motPasse'] ?? null);

        // Vérification des champs vides
        if (empty($emailUser) || empty($motPasse)) {
            if (empty($emailUser)) {
                $this->set_flash("Veuillez remplir le champ Email", 'danger');
            }
            if (empty($motPasse)) {
                $this->set_flash("Veuillez remplir le champ Mot de passe", 'danger');
            }
            return;
        }

        // Requête : récupération de l'utilisateur et de l'agence si nécessaire
        $utilisateur = $this->FetchSelectWhere(
            'utilisateur.*, agence.localite, agence.numeroGare', // explicitement ce dont tu as besoin
            'utilisateur 
     LEFT JOIN agence ON utilisateur.id_agence = agence.idAgence',
            'emailUser = :emailUser',
            ['emailUser' => $emailUser]
        );


        if (!$utilisateur) {
            $this->set_flash("Aucun utilisateur trouvé avec cet email", 'danger');
            return;
        }

        if (password_verify($motPasse, $utilisateur->motPasse)) {
            // Connexion réussie
            $_SESSION['id_utilisateur'] = $utilisateur->idUser;
            $_SESSION['emailUser'] = $utilisateur->emailUser;
            $_SESSION['nom'] = $utilisateur->utilisateurs;
            $_SESSION['droit'] = $utilisateur->droit;
            $_SESSION['status'] = $utilisateur->status;

            // Vérifie si l'utilisateur a une agence
            $_SESSION['ville'] = $utilisateur->localite ?? null;
            $_SESSION['id_agence'] = $utilisateur->idAgence ?? null;
            $_SESSION['numero_gare'] = $utilisateur->numeroGare ?? null;

            // Prend l'id_compagnie directement depuis la table utilisateur
            $_SESSION['id_compagnie'] = $utilisateur->id_compagnie ?? null;

            // Affichage pour debug
            // echo "id_compagnie = " . $_SESSION['id_compagnie'];
            // exit;

            header("Location: index.php?url=admin/Homes/home");
            exit;
        } else {
            $this->set_flash("Mot de passe incorrect pour l'utilisateur", 'danger');
            return;
        }
    }
}
