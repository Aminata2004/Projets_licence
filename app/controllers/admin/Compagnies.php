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

  // place_limite()/edit1() gèrent la limite de places de demain d'UNE compagnie : contrairement
  // au reste du contrôleur, l'Admin de sa propre compagnie doit pouvoir y accéder (le lien est
  // d'ailleurs déjà présent dans la sidebar admin normale) — seul super_admin doit voir/éditer
  // les autres compagnies.
  private function requireAdminOrSuperAdmin()
  {
    if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
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
    $erreurLogo = null; // message d'échec d'upload, affiché APRES editCompagnie() ci-dessous

    // Vérifier si un nouveau logo est envoyé
    if (!empty($_FILES['logo']['name'])) {

        $erreurUpload = $_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($erreurUpload !== UPLOAD_ERR_OK) {
            // Cause la plus fréquente d'échec silencieux constatée en prod : le code
            // ignorait ce code d'erreur et gardait l'ancien logo sans jamais le signaler.
            $messagesErreur = [
                UPLOAD_ERR_INI_SIZE   => "Logo trop volumineux (limite serveur upload_max_filesize dépassée).",
                UPLOAD_ERR_FORM_SIZE  => "Logo trop volumineux (limite du formulaire dépassée).",
                UPLOAD_ERR_PARTIAL    => "Le fichier n'a été que partiellement envoyé.",
                UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire d'upload manquant côté serveur.",
                UPLOAD_ERR_CANT_WRITE => "Échec d'écriture du fichier temporaire côté serveur.",
                UPLOAD_ERR_EXTENSION  => "Upload bloqué par une extension PHP du serveur.",
            ];
            $erreurLogo = "Logo non enregistré : " . ($messagesErreur[$erreurUpload] ?? "erreur d'upload inconnue (code $erreurUpload).");
        } else {

            // app/controllers/admin/ est a 3 niveaux sous la racine du projet (contrairement
            // a app/models/, a 2 niveaux) : dirname(__DIR__, 2) pointait par erreur vers
            // app/public/... au lieu de public/...
            $dossier = dirname(__DIR__, 3) . '/public/images/logos/';
            $nom_fichier = time() . "_" . basename($_FILES['logo']['name']);
            $chemin = $dossier . $nom_fichier;

            $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
            $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $extensions_autorisees)) {
                $erreurLogo = "Logo non enregistré : format .$extension non autorisé (jpg, jpeg, png, webp uniquement).";
            } elseif (!is_dir($dossier)) {
                $erreurLogo = "Logo non enregistré : dossier introuvable côté serveur ($dossier).";
            } elseif (!is_writable($dossier)) {
                $proprietaire = function_exists('posix_getpwuid') ? (posix_getpwuid(fileowner($dossier))['name'] ?? fileowner($dossier)) : fileowner($dossier);
                $processus = function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? posix_geteuid()) : (function_exists('get_current_user') ? get_current_user() : '?');
                $erreurLogo = "Logo non enregistré : dossier non inscriptible ($dossier), propriétaire=$proprietaire, PHP tourne en tant que=$processus.";
            } elseif (!move_uploaded_file($_FILES['logo']['tmp_name'], $chemin)) {
                $err = error_get_last();
                $erreurLogo = "Logo non enregistré : échec de l'écriture du fichier ($chemin)" . (!empty($err['message']) ? " — " . $err['message'] : '') . ".";
            } else {
                // Le umask du process PHP peut produire un fichier non lisible par
                // le serveur web (403) selon l'hébergeur ; on force donc 0644.
                chmod($chemin, 0644);

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

    // editCompagnie() écrase toujours le flash avec un message de succès dès que
    // l'UPDATE SQL passe (même si $logo est resté l'ancien) : on le remplace ici,
    // en dernier, si l'upload a échoué, sinon l'échec restait invisible pour l'admin.
    if ($erreurLogo !== null) {
        $compagnie->set_flash($erreurLogo, 'danger');
    }

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
    $this->requireAdminOrSuperAdmin();

    // Instanciation du modèle
    $compagnie = new Compagnie();

    // super_admin voit/gère la limite de toutes les compagnies ; un Admin ne voit et
    // ne modifie que la limite de SA propre compagnie (évite qu'il voie/édite les
    // réglages des autres compagnies du SaaS).
    if (($_SESSION['droit'] ?? null) === 'super_admin') {
      $liste_place = $compagnie->SelectAllData(
        'place_minumale.*, compagnie.nom_compagnie',
        'place_minumale INNER JOIN compagnie ON place_minumale.id_compagnie = compagnie.id_compagnie'
      );
    } else {
      $liste_place = $compagnie->SelectAllDatas(
        'place_minumale.*, compagnie.nom_compagnie',
        'place_minumale INNER JOIN compagnie ON place_minumale.id_compagnie = compagnie.id_compagnie WHERE compagnie.id_compagnie = :ic',
        [':ic' => $_SESSION['id_compagnie'] ?? null]
      );
    }

    $this->view('admin/place_limite', ['liste_place' => $liste_place]);
  }

  public function edit1()
  {
    $this->requireAdminOrSuperAdmin();

    $compagnie = new Compagnie();

    if (isset($_POST['edit'])) {
      $place_minumale = $_POST["place_minumale"];
      $id = $_POST["id_place_minumale"]; // Récupération de l’ID depuis le formulaire

      // IDOR : un Admin ne doit pouvoir modifier que la ligne de SA propre compagnie,
      // même s'il poste un id_place_minumale appartenant à une autre compagnie.
      $idCompagnieRestrict = (($_SESSION['droit'] ?? null) === 'super_admin')
        ? null
        : ($_SESSION['id_compagnie'] ?? null);

      $compagnie->editPlace([
        'id_place_minumale' => $id,
        'place_minumale' => $place_minumale
      ], $idCompagnieRestrict);

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
