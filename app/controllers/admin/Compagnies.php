<?php
class Compagnies extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  // Ce contrôleur gère TOUTES les compagnies du SaaS (pas seulement celle de l'utilisateur
  // connecté) : réservé au super_admin. Seul impersonate() vérifiait déjà ce rôle ; index(),
  // edit(), delete(), place_limite() et edit1() étaient accessibles à n'importe quel
  // utilisateur connecté, de n'importe quel rôle et de n'importe quelle compagnie — delete()
  // permettait ainsi à un simple "Utilisateur" de supprimer n'importe quelle compagnie du
  // système (vérifié en conditions réelles).
  private function requireSuperAdmin()
  {
    if (($_SESSION['droit'] ?? null) !== 'super_admin') {
      header("Location: " . BASE_URL . "/admin/Homes/home");
      exit;
    }
  }

  public function index()
  {
    $this->requireSuperAdmin();

    // Instanciation du modèle
    $compagnie = new Compagnie();

    // Traitement du formulaire
    if (isset($_POST["save"])) {
      // Appel de la méthode d'enregistrement
      $compagnie->saveCompagnie($_FILES['logo'] ?? null);
    }

    // Récupération des données à afficher
    $liste = $compagnie->SelectAllData('*', "compagnie");

    // Affichage de la vue
    $this->view('admin/compagnies', ['liste' => $liste]);
  }


  // fonction pour la modification des compagnie
  public function edit()
  {
    $this->requireSuperAdmin();

    $compagnie = new Compagnie();

    if (isset($_POST['edit'])) {

    $id_compagnie  = $_POST["id_compagnie"];
    $nom_compagnie = $_POST["nom_compagnie"];
    $libele        = $_POST["libele"];
    $slogant       = $_POST["slogant"];
    $ancien_logo   = $_POST["ancien_logo"];

    $logo = $ancien_logo; // par défaut on garde l’ancien

    // Vérifier si un nouveau logo est envoyé
    if (!empty($_FILES['logo']['name'])) {

        $dossier = "public/images/logos/";
        $nom_fichier = time() . "_" . basename($_FILES['logo']['name']);
        $chemin = $dossier . $nom_fichier;

        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $extensions_autorisees)) {

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $chemin)) {

                // Supprimer l'ancien logo si existant
                if (!empty($ancien_logo) && file_exists($dossier . $ancien_logo)) {
                    unlink($dossier . $ancien_logo);
                }

                $logo = $nom_fichier;
            }
        }
    }

    // Mise à jour
    $compagnie->editCompagnie([
        'id_compagnie'  => $id_compagnie,
        'nom_compagnie' => $nom_compagnie,
        'libele'        => $libele,
        'slogant'       => $slogant,
        'logo'          => $logo
    ]);

    header("Location: " . BASE_URL . "/admin/Compagnies/index");
    exit;
}

  }

  public function delete($id)
  {
    $this->requireSuperAdmin();

    $compagnie = new Compagnie();
    // Définir la requête de suppression et les paramètres
    $sql = 'DELETE FROM compagnie WHERE id_compagnie = :id';
    $params = [':id' => $id];
    $result = $compagnie->insertion_update_simples($sql, $params);
    if ($result->rowCount() > 0) {
      //$compagnie->set_flash("Suppression réussie", 'success');
      //     header("Location: " . ROOT . "/compagnies/index");
      // exit;
    }
    header("Location: " . BASE_URL . "/admin/Compagnies/index");
    exit;
  }

  // limitation de place 
  public function place_limite()
  {
    $this->requireSuperAdmin();

    // Instanciation du modèle
    $compagnie = new Compagnie();
    $liste_place = $compagnie->SelectAllData('*', "place_minumale");

    $this->view('admin/place_limite', ['liste_place' => $liste_place]);
  }

  public function edit1()
  {
    $this->requireSuperAdmin();

    $compagnie = new Compagnie();

    if (isset($_POST['edit'])) {
      $place_minumale = $_POST["place_minumale"];
      $id = $_POST["id_place_minumale"]; // Récupération de l’ID depuis le formulaire

      $compagnie->editPlace([
        'id_place_minumale' => $id,
        'place_minumale' => $place_minumale
      ]);

      header("Location: " . BASE_URL . "/admin/Compagnies/place_limite");
      exit;
    }
  }

  // Fonction pour le mode Support Technique (Impersonation)
  public function impersonate($id_compagnie)
  {
    // Sécurité: Seul un super_admin peut utiliser cette fonction
    if ($_SESSION['droit'] !== 'super_admin') {
        header("Location: " . BASE_URL . "/admin/Compagnies");
        exit;
    }

    // Sauvegarder l'identité du Super Admin
    $_SESSION['super_admin_id'] = $_SESSION['id_utilisateur'];
    $_SESSION['super_admin_droit'] = $_SESSION['droit'];
    $_SESSION['super_admin_nom'] = $_SESSION['nom'];

    // Récupérer le nom de la compagnie pour l'affichage (optionnel mais recommandé)
    $compagnieModel = new Compagnie();
    $sql = 'SELECT nom_compagnie FROM compagnie WHERE id_compagnie = :id';
    $stmt = $compagnieModel->bdd()->prepare($sql);
    $stmt->execute([':id' => $id_compagnie]);
    $compInfos = $stmt->fetch(PDO::FETCH_ASSOC);
    $nom_c = $compInfos ? $compInfos['nom_compagnie'] : 'Inconnue';

    // Remplacer temporairement la session
    $_SESSION['droit'] = 'Admin';
    $_SESSION['id_compagnie'] = $id_compagnie;
    $_SESSION['nom'] = 'Support (' . $nom_c . ')';

    // Rediriger vers le tableau de bord de la compagnie
    header("Location: " . BASE_URL . "/admin/Homes");
    exit;
  }

  // Fonction pour quitter le mode Support Technique
  public function leave_impersonate()
  {
    // Vérifier si on est en mode support
    if (isset($_SESSION['super_admin_id'])) {
        // Restaurer l'identité du Super Admin
        $_SESSION['id_utilisateur'] = $_SESSION['super_admin_id'];
        $_SESSION['droit'] = $_SESSION['super_admin_droit'];
        $_SESSION['nom'] = $_SESSION['super_admin_nom'];
        
        // Supprimer l'id_compagnie temporaire
        unset($_SESSION['id_compagnie']);

        // Nettoyer les variables de sauvegarde
        unset($_SESSION['super_admin_id']);
        unset($_SESSION['super_admin_droit']);
        unset($_SESSION['super_admin_nom']);
    }

    // Rediriger vers la liste des compagnies
    header("Location: " . BASE_URL . "/admin/Compagnies");
    exit;
  }
}
