<?php
class Historiques extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {

        $this->view('admin/historique_colis_enregistrer');
    }

    public function historique_colis_enregistrer()
    {

        $this->view('admin/historique_colis_enregistrer');
    }

    public function historique_colis_livre()
    {

        $this->view('admin/historique_colis_livre');
    }
    public function AjaxFiltreListecolis()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        $ville = $_SESSION['ville']; // Agence de provenance

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Date_debut = $_POST['Date_debut'] ?? '';
            $Date_fin   = $_POST['Date_fin'] ?? '';

            if ($Date_debut && $Date_fin) {
                $model = new Liste_du_jour();
                date_default_timezone_set('Africa/Bamako');

                // Sélection des colis filtrés par date et agence de provenance
                $resultats = $model->FetchSelectWheres(
                    '*',
                    'colis 
                 INNER JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
                 INNER JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
                 INNER JOIN agence a ON colis.id_agence = a.idAgence',
                    'colis.id_compagnie = :id_compagnie AND colis.provient_de = :ville AND DATE(colis.date_enregistrement) BETWEEN :date_debut AND :date_fin',
                    [
                        'id_compagnie' => $id_compagnie,
                        'ville'        => $ville,
                        'date_debut'   => $Date_debut,
                        'date_fin'     => $Date_fin
                    ]
                );

                // Pour débogage
                // Générer le HTML du tbody
                ob_start();
                foreach ($resultats as $item) {
                    echo '<tr class="text-center">';
                    echo '<td data-label="Expéditeur">' . htmlspecialchars($item->expediteur) . '</td>';
                    echo '<td data-label="Destinataire">' . htmlspecialchars($item->destinataire) . '</td>';
                    echo '<td data-label="Nom colis">' . htmlspecialchars($item->nom_colis) . '</td>';
                    echo '<td data-label="Valeur">' . number_format($item->valeur, 0, ',', ' ') . ' FCFA</td>';
                    echo '<td data-label="Frais de transaction">' . number_format($item->fraix_transaction, 0, ',', ' ') . ' FCFA</td>';
                    echo '<td data-label="Code colis">' . htmlspecialchars($item->code_colis) . '</td>';
                    echo '<td data-label="Status">' . htmlspecialchars($item->status) . '</td>';
                    echo '</tr>';
                }
                $tbody = ob_get_clean();

                echo json_encode(['tbody' => $tbody]);
                exit;
            } else {
                echo json_encode(['error' => 'Veuillez sélectionner une date de début et une date de fin.']);
                exit;
            }
        }
        echo json_encode(['error' => 'Requête invalide.']);
        exit;
    }


    public function AjaxFiltreListecolislivre()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        $ville = $_SESSION['ville']; // Ville de l'agence en session

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Date_debut = $_POST['Date_debut'] ?? '';
            $Date_fin   = $_POST['Date_fin'] ?? '';

            if ($Date_debut && $Date_fin) {
                $model = new Liste_du_jour();
                date_default_timezone_set('Africa/Bamako');

                // Sélection des colis filtrés par date, statut "livre" et provenance différente de la ville de session
                $resultats = $model->FetchSelectWheres(
                    '*',
                    'colis 
                 INNER JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
                 INNER JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
                 INNER JOIN agence a ON colis.id_agence = a.idAgence',
                    'colis.id_compagnie = :id_compagnie AND colis.provient_de != :ville AND colis.status = :status AND DATE(colis.date_enregistrement) BETWEEN :date_debut AND :date_fin',
                    [
                        'id_compagnie' => $id_compagnie,
                        'ville'        => $ville,
                        'status'       => 'livre',
                        'date_debut'   => $Date_debut,
                        'date_fin'     => $Date_fin
                    ]
                );

                // Générer le HTML du tbody
                ob_start();
                foreach ($resultats as $item) {
                    echo '<tr class="text-center">';
                    echo '<td data-label="Expéditeur">' . htmlspecialchars($item->expediteur) . '</td>';
                    echo '<td data-label="Destinataire">' . htmlspecialchars($item->destinataire) . '</td>';
                    echo '<td data-label="Nom colis">' . htmlspecialchars($item->nom_colis) . '</td>';
                    echo '<td data-label="Valeur">' . number_format($item->valeur, 0, ',', ' ') . ' FCFA</td>';
                    echo '<td data-label="Frais de transaction">' . number_format($item->fraix_transaction, 0, ',', ' ') . ' FCFA</td>';
                    echo '<td data-label="Code colis">' . htmlspecialchars($item->code_colis) . '</td>';
                    echo '<td data-label="Status"><span class="badge bg-success">Livré</span></td>';
                    echo '</tr>';
                }
                $tbody = ob_get_clean();

                echo json_encode(['tbody' => $tbody]);
                exit;
            } else {
                echo json_encode(['error' => 'Veuillez sélectionner une date de début et une date de fin.']);
                exit;
            }
        }
        echo json_encode(['error' => 'Requête invalide.']);
        exit;
    }
}
