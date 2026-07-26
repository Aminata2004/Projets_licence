<?php

class Locations_cars extends Controller
{
    public function __construct()
    {
        $this->requireLogin();

        // Reserve a Admin/chef_d_escale (comme Depenses) : Location_gestion seul ne suffit
        // pas, sinon un Utilisateur simple a qui cette permission serait assignee par
        // erreur y aurait quand meme acces.
        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'chef_d_escale'], true)) {
            (new Configuration())->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $this->requirePermission('Location_gestion');
    }

    public function index()
    {
        $model = new Location_car();
        $id_compagnie = $_SESSION['id_compagnie'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_location'])) {
            $model->saveLocation();
            header("Location: " . BASE_URL . "/admin/Locations_cars");
            exit;
        }

        // Liste des gares, utilisee par l'Admin pour choisir la gare de depart
        $listeAgences = [];
        if ($_SESSION['droit'] === 'Admin') {
            $listeAgences = $model->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                ['id_compagnie' => $id_compagnie]
            );
        }

        $this->view('admin/locations_cars', [
            'listeLocations' => $model->getLocations(),
            'listeAgences'   => $listeAgences,
        ]);
    }

    // Renvoie en JSON les cars disponibles pour la gare de depart / periode demandees,
    // pour peupler dynamiquement le menu deroulant du formulaire.
    public function ajaxCarsDisponibles()
    {
        header('Content-Type: application/json; charset=utf-8');

        $droit = $_SESSION['droit'] ?? null;
        $id_agence_depart = $droit === 'chef_d_escale' ? $_SESSION['id_agence'] : (int)($_POST['id_agence_depart'] ?? 0);
        $date_depart = $_POST['date_depart'] ?? '';
        $date_retour_prevu = $_POST['date_retour_prevu'] ?? '';

        if (!$id_agence_depart || !$date_depart || !$date_retour_prevu) {
            echo json_encode(['error' => 'Gare de départ et dates requises.']);
            exit;
        }
        if ($date_retour_prevu < $date_depart) {
            echo json_encode(['error' => 'La date de retour prévu ne peut pas être avant la date de départ.']);
            exit;
        }

        $model = new Location_car();
        $limiterAGare = ($droit === 'chef_d_escale') && $date_depart === date('Y-m-d');
        $cars = $model->carsDisponibles($id_agence_depart, $date_depart, $date_retour_prevu, $limiterAGare);

        echo json_encode([
            'cars' => array_map(fn($c) => [
                'id_car'     => (int)$c->id_car,
                'numero_car' => $c->numero_car,
                'matriculle' => $c->matriculle,
            ], $cars)
        ]);
        exit;
    }

    public function valider($id = null)
    {
        if ($id === null) {
            header("Location: " . BASE_URL . "/admin/Locations_cars");
            exit;
        }

        $model = new Location_car();
        if (($_SESSION['droit'] ?? null) !== 'Admin') {
            $model->set_flash("Accès réservé à l'Admin de la compagnie.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $model->validerLocation($id);
        header("Location: " . BASE_URL . "/admin/Locations_cars");
        exit;
    }

    public function rejeter($id = null)
    {
        if ($id === null) {
            header("Location: " . BASE_URL . "/admin/Locations_cars");
            exit;
        }

        $model = new Location_car();
        if (($_SESSION['droit'] ?? null) !== 'Admin') {
            $model->set_flash("Accès réservé à l'Admin de la compagnie.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $model->rejeterLocation($id);
        header("Location: " . BASE_URL . "/admin/Locations_cars");
        exit;
    }
}
