<?php
class Reclamations extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public function index()
  {
      $db = new Liste_gare();
      $colisData = null;

      // 1. Recherche du colis
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
          $code = trim($_POST['code']);
          $id_compagnie = $_SESSION['id_compagnie'];

          $colisData = $db->FetchSelectWhere(
              'c.*, ex.expediteur as expediteur_nom, ex.numero_exp as expediteur_tel, dest.destinataire as destinataire_nom, dest.numero_dest as destinataire_tel',
              'colis c 
               LEFT JOIN expediteurs ex ON c.id_expediteur = ex.id_expediteur
               LEFT JOIN destinataires dest ON c.id_destinataire = dest.id_destinataire',
              'c.code_colis = :code AND c.id_compagnie = :id_compagnie',
              ['code' => $code, 'id_compagnie' => $id_compagnie]
          );

          if (!$colisData) {
              $db->set_swal("Introuvable", "Aucun colis trouvé avec le code: $code.", "error", "#dc3545");
          }
      }

      // 2. Traitement d'une nouvelle réclamation
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reclamation'])) {
          if (!csrf_verify()) {
              $db->set_flash("Session expirée, merci de réessayer.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          $id_colis = $_POST['id_colis'] ?? null;
          $motif = trim($_POST['motif_reclamation'] ?? '');
          $montant = (int)($_POST['montant_remboursement'] ?? 0);
          $id_compagnie = $_SESSION['id_compagnie'];

          if (!$id_colis || $montant <= 0) {
              $db->set_flash("Montant de remboursement invalide.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          // Le colis ciblé doit appartenir à la compagnie de l'utilisateur connecté :
          // sans ce filtre, n'importe quel id_colis posté (même d'une autre compagnie)
          // était accepté, permettant de réclamer/rembourser un colis qui n'est pas le sien.
          $colisExiste = $db->FetchSelectWhere(
              'id_colis',
              'colis',
              'id_colis = :id_colis AND id_compagnie = :id_compagnie',
              [':id_colis' => $id_colis, ':id_compagnie' => $id_compagnie]
          );

          if (!$colisExiste) {
              $db->set_flash("Colis introuvable.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          $update = $db->insertion_update_simple(
              "UPDATE colis SET
                  reclamer = 1,
                  date_reclamer = NOW(),
                  motif_reclamation = :motif,
                  montant_remboursement = :montant,
                  status_reclamation = 'En attente'
               WHERE id_colis = :id_colis AND id_compagnie = :id_compagnie",
              [
                  ':motif' => $motif,
                  ':montant' => $montant,
                  ':id_colis' => $id_colis,
                  ':id_compagnie' => $id_compagnie
              ]
          );

          $db->set_flash("La réclamation a été soumise avec succès.", "success");
          header("Location: " . BASE_URL . "/admin/Reclamations");
          exit;
      }

      // 3. Mise à jour du statut
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
          if (!csrf_verify()) {
              $db->set_flash("Session expirée, merci de réessayer.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          if (!isset($_SESSION['droit']) || !in_array($_SESSION['droit'], ['Admin', 'chef_d_escale'])) {
              $db->set_flash("Vous n'avez pas l'autorisation d'approuver ou gérer les réclamations.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          $id_colis = $_POST['id_colis_status'] ?? null;
          $status = $_POST['status_reclamation'] ?? null;
          $id_compagnie = $_SESSION['id_compagnie'];

          if (!$id_colis || !$status) {
              $db->set_flash("Requête invalide.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          $pdo = $db->connect();

          try {
              $pdo->beginTransaction();

              // Verrouille la ligne colis le temps de la transaction : empêche deux requêtes
              // concurrentes (double clic, deux agents) de rembourser deux fois la même
              // réclamation. Le filtre id_compagnie garantit aussi qu'on ne peut agir que sur
              // un colis de sa propre compagnie (avant, id_colis était accepté sans ce filtre).
              $stmt = $pdo->prepare(
                  "SELECT montant_remboursement, status_reclamation FROM colis
                   WHERE id_colis = :id_colis AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
              );
              $stmt->execute([':id_colis' => $id_colis, ':id_compagnie' => $id_compagnie]);
              $colis = $stmt->fetch(PDO::FETCH_OBJ);

              if (!$colis) {
                  $pdo->rollBack();
                  $db->set_flash("Colis introuvable.", "danger");
                  header("Location: " . BASE_URL . "/admin/Reclamations");
                  exit;
              }

              // Si on veut rembourser, on doit vérifier la CAISSE !
              if ($status === 'Remboursé' && $colis->status_reclamation !== 'Remboursé') {
                  $id_caisse_a_debiter = null;

                  if ($_SESSION['droit'] === 'Admin') {
                      // Logique Admin : on prend l'ID de la caisse envoyée par le select,
                      // en vérifiant qu'elle appartient bien à sa compagnie.
                      $id_caisse_a_debiter = $_POST['admin_id_caisse'] ?? null;
                      if (empty($id_caisse_a_debiter)) {
                          $pdo->rollBack();
                          $db->set_flash("Erreur : Veuillez sélectionner une caisse pour effectuer le remboursement.", "danger");
                          header("Location: " . BASE_URL . "/admin/Reclamations");
                          exit;
                      }

                      $stmtCaisse = $pdo->prepare(
                          "SELECT id_caisse FROM caisse WHERE id_caisse = :id_caisse AND id_compagnie = :id_compagnie LIMIT 1"
                      );
                      $stmtCaisse->execute([':id_caisse' => $id_caisse_a_debiter, ':id_compagnie' => $id_compagnie]);
                      if (!$stmtCaisse->fetch()) {
                          $pdo->rollBack();
                          $db->set_flash("Caisse invalide.", "danger");
                          header("Location: " . BASE_URL . "/admin/Reclamations");
                          exit;
                      }
                  } else {
                      // Logique Chef d'escale : on prend automatiquement sa ville
                      $ville = $_SESSION['ville'] ?? null;
                      if (empty($ville)) {
                          $pdo->rollBack();
                          $db->set_flash("Opération bloquée : Vous n'êtes pas affecté à une gare.", "danger");
                          header("Location: " . BASE_URL . "/admin/Reclamations");
                          exit;
                      }

                      $stmtCaisse = $pdo->prepare(
                          "SELECT c.id_caisse FROM caisse c
                           LEFT JOIN agence a ON c.id_agence = a.idAgence
                           WHERE a.localite = :ville AND c.id_compagnie = :id_compagnie AND c.status_caisse = 1
                           LIMIT 1"
                      );
                      $stmtCaisse->execute([':ville' => $ville, ':id_compagnie' => $id_compagnie]);
                      $caisseOuverte = $stmtCaisse->fetch(PDO::FETCH_OBJ);

                      if (!$caisseOuverte) {
                          $pdo->rollBack();
                          $db->set_flash("Impossible de rembourser ! Aucune caisse n'est actuellement ouverte pour votre gare ($ville).", "danger");
                          header("Location: " . BASE_URL . "/admin/Reclamations");
                          exit;
                      }
                      $id_caisse_a_debiter = $caisseOuverte->id_caisse;
                  }

                  // On débite la caisse (en augmentant le montant_rembourse)
                  $pdo->prepare(
                      "UPDATE caisse SET montant_rembourse = montant_rembourse + :montant WHERE id_caisse = :id_caisse"
                  )->execute([':montant' => $colis->montant_remboursement, ':id_caisse' => $id_caisse_a_debiter]);
              }

              // Mise à jour du colis
              $pdo->prepare(
                  "UPDATE colis SET status_reclamation = :status WHERE id_colis = :id_colis AND id_compagnie = :id_compagnie"
              )->execute([':status' => $status, ':id_colis' => $id_colis, ':id_compagnie' => $id_compagnie]);

              $pdo->commit();
              $db->set_flash("Le statut de la réclamation a été modifié avec succès.", "success");
          } catch (Throwable $e) {
              $pdo->rollBack();
              $db->set_flash("Erreur lors de la mise à jour : " . $e->getMessage(), "danger");
          }

          header("Location: " . BASE_URL . "/admin/Reclamations");
          exit;
      }

      // 4. Liste de toutes les réclamations pour la compagnie
      $liste_reclamations = $db->FetchSelectWheres(
          'c.*, ex.expediteur as expediteur_nom, dest.destinataire as destinataire_nom, a.localite as destination',
          'colis c 
           LEFT JOIN expediteurs ex ON c.id_expediteur = ex.id_expediteur
           LEFT JOIN destinataires dest ON c.id_destinataire = dest.id_destinataire
           LEFT JOIN agence a ON c.id_agence = a.idAgence',
          'c.reclamer = 1 AND c.id_compagnie = :id_compagnie ORDER BY c.date_reclamer DESC',
          ['id_compagnie' => $_SESSION['id_compagnie']]
      );

      // 5. Pour l'Admin, on liste les caisses ouvertes pour le select
      $caisses_ouvertes = [];
      if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin') {
          $caisses_ouvertes = $db->FetchSelectWheres(
              'c.id_caisse, a.localite, c.reference_caise, c.montant_billets, c.montant_colis, c.montant_rembourse',
              'caisse c JOIN agence a ON c.id_agence = a.idAgence',
              'c.id_compagnie = :id_compagnie AND c.status_caisse = 1',
              [':id_compagnie' => $_SESSION['id_compagnie']]
          );
      }

      $this->view('admin/reclamation', [
          'colisData' => $colisData,
          'liste_reclamations' => $liste_reclamations,
          'caisses_ouvertes' => $caisses_ouvertes
      ]);
  }
}
