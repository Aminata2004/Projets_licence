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
          $id_colis = $_POST['id_colis'];
          $motif = trim($_POST['motif_reclamation']);
          $montant = (int)$_POST['montant_remboursement'];

          $update = $db->insertion_update_simple(
              "UPDATE colis SET 
                  reclamer = 1, 
                  date_reclamer = NOW(), 
                  motif_reclamation = :motif, 
                  montant_remboursement = :montant, 
                  status_reclamation = 'En attente'
               WHERE id_colis = :id_colis",
              [
                  ':motif' => $motif,
                  ':montant' => $montant,
                  ':id_colis' => $id_colis
              ]
          );

          $db->set_flash("La réclamation a été soumise avec succès.", "success");
          header("Location: " . BASE_URL . "/admin/Reclamations");
          exit;
      }
      
      // 3. Mise à jour du statut
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
          
          if (!isset($_SESSION['droit']) || !in_array($_SESSION['droit'], ['Admin', 'chef_d_escale'])) {
              $db->set_flash("Vous n'avez pas l'autorisation d'approuver ou gérer les réclamations.", "danger");
              header("Location: " . BASE_URL . "/admin/Reclamations");
              exit;
          }

          $id_colis = $_POST['id_colis_status'];
          $status = $_POST['status_reclamation'];
          
          // On récupère les infos de ce colis pour vérifier son état actuel et le montant
          $colis = $db->FetchSelectWhere('montant_remboursement, status_reclamation', 'colis', 'id_colis = :id_colis', [':id_colis' => $id_colis]);
          
          // Si on veut rembourser, on doit vérifier la CAISSE !
          if ($status === 'Remboursé' && $colis && $colis->status_reclamation !== 'Remboursé') {
              $id_caisse_a_debiter = null;
              $id_compagnie = $_SESSION['id_compagnie'];
              
              if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin') {
                  // Logique Admin : on prend l'ID de la caisse envoyée par le select
                  $id_caisse_a_debiter = $_POST['admin_id_caisse'] ?? null;
                  if (empty($id_caisse_a_debiter)) {
                      $db->set_flash("Erreur : Veuillez sélectionner une caisse pour effectuer le remboursement.", "danger");
                      header("Location: " . BASE_URL . "/admin/Reclamations");
                      exit;
                  }
              } else {
                  // Logique Chef d'escale : on prend automatiquement sa ville
                  $ville = $_SESSION['ville'] ?? null;
                  if (empty($ville)) {
                      $db->set_flash("Opération bloquée : Vous n'êtes pas affecté à une gare.", "danger");
                      header("Location: " . BASE_URL . "/admin/Reclamations");
                      exit;
                  }
                  
                  $caisseOuverte = $db->FetchSelectWhere(
                      'c.id_caisse', 
                      'caisse c LEFT JOIN agence a ON c.id_agence = a.idAgence', 
                      'a.localite = :ville AND c.id_compagnie = :id_compagnie AND c.status_caisse = 1', 
                      [':ville' => $ville, ':id_compagnie' => $id_compagnie]
                  );
                  
                  if (!$caisseOuverte) {
                      $db->set_flash("Impossible de rembourser ! Aucune caisse n'est actuellement ouverte pour votre gare ($ville).", "danger");
                      header("Location: " . BASE_URL . "/admin/Reclamations");
                      exit;
                  }
                  $id_caisse_a_debiter = $caisseOuverte->id_caisse;
              }

              // On débite la caisse (en augmentant le montant_rembourse)
              $db->insertion_update_simple(
                  "UPDATE caisse SET montant_rembourse = montant_rembourse + :montant WHERE id_caisse = :id_caisse",
                  [':montant' => $colis->montant_remboursement, ':id_caisse' => $id_caisse_a_debiter]
              );
          }

          // Mise à jour du colis
          $db->insertion_update_simple(
              "UPDATE colis SET status_reclamation = :status WHERE id_colis = :id_colis",
              [':status' => $status, ':id_colis' => $id_colis]
          );
          
          $db->set_flash("Le statut de la réclamation a été modifié avec succès.", "success");
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
