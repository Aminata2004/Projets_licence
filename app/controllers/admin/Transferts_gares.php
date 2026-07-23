<?php

class Transferts_gares extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    // AJAX : liste des gares compatibles (même destination/heure/jour, même localité) pour la
    // programmation donnée, avec pour chacune un aperçu du transfert (montant, passagers, places).
    public function candidats($id_programmation)
    {
        $model = new Transfert_gare();

        $droit = $_SESSION['droit'] ?? null;
        if (!in_array($droit, ['chef_d_escale', 'Admin', 'super_admin'], true)) {
            echo json_encode(['error' => 'Accès refusé.']);
            exit;
        }

        // getGaresCompatibles() renvoie des tableaux associatifs (Model::fetchAll -> FETCH_ASSOC),
        // pas des objets.
        $gares = $model->getGaresCompatibles($id_programmation);

        $resultats = [];
        foreach ($gares as $gare) {
            $apercu = $model->getApercu($id_programmation, $gare['id_programmation']);
            $resultats[] = [
                'id_programmation' => $gare['id_programmation'],
                'numeroGare'       => $gare['numeroGare'],
                'places_libres'    => (int)$gare['places_libres'],
                'apercu'           => $apercu,
            ];
        }

        echo json_encode(['gares' => $resultats]);
        exit;
    }

    public function executer()
    {
        $model = new Transfert_gare();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->transfererPassagers(
                $_POST['id_programmation_source'] ?? null,
                $_POST['id_programmation_destination'] ?? null
            );
        }

        header("Location: " . BASE_URL . "/admin/Programmation_voyages/liste_programmer_voyage");
        exit;
    }

    // Historique des transferts déjà effectués (traçabilité permanente).
    public function historique()
    {
        $model = new Transfert_gare();

        $droit = $_SESSION['droit'] ?? null;
        if (!in_array($droit, ['chef_d_escale', 'Admin', 'super_admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $this->view('admin/transferts_gares', [
            'listeTransferts' => $model->getHistorique()
        ]);
    }
}
