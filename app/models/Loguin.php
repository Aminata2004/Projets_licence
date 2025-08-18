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

        // Requête : Suppression de l’espace inutile dans le nom de la table
        $utilisateur = $this->FetchSelectWhere(
            '*',
            'utilisateur 
        LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence 
        LEFT JOIN compagnie ON compagnie.id_compagnie = utilisateur.id_compagnie',
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
            $_SESSION['ville'] = $utilisateur->localite;
            $_SESSION['id_agence'] = $utilisateur->idAgence;
            $_SESSION['numero_gare']=  $utilisateur->numeroGare;
            $_SESSION['id_compagnie'] = $utilisateur->id_compagnie;
            
         

            //echo $_SESSION['ville'] ;exit;

            // Redirection
          //  $this->redirect("Homes/home");
        //   header("Location: " . BASE_URL . "/Homes/home");
        header("Location: index.php?url=admin/Homes/home");



            return;
        } else {
            $this->set_flash("Mot de passe incorrect pour l'utilisateur", 'danger');
            return;
        }
    }
}
