<?php
class Add_billets extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $model = new Add_billet();
        date_default_timezone_set('Africa/Bamako');
        $data['destinations'] = $model->getDestinationsWithHeuresAndEscales();

        if (($_SESSION['droit'] ?? null) === 'Admin') {
            $data['agences'] = $model->getAgencesByCompagnie();
            $data['idDepartSelectionne'] = $_POST['idDepart'] ?? $_GET['idDepart'] ?? null;

            // Destinations de TOUTES les gares de la compagnie, préchargées d'un coup :
            // changer la gare de départ ne fait plus qu'échanger quel jeu de données JS est
            // utilisé (instantané), au lieu de recharger toute la page (location.href avant).
            $data['destinationsParGare'] = [];
            foreach ($data['agences'] as $agence) {
                $data['destinationsParGare'][$agence['idAgence']] = $model->getDestinationsWithHeuresAndEscales($agence['idAgence']);
            }
        }

        if (isset($_POST['save'])) {
            $result = $model->saveBillets();

            if ($result === true) {
                $_SESSION['flash'] = "Réservation enregistrée !";
                $this->view('admin/add_billets');
                exit;
            } else {
                $data['erreurs'] = $result; // tableau d'erreurs
            }
        }


        // var_dump($data['destinations']);exit;
    
        $this->view('admin/add_billets', $data);
    }
}
