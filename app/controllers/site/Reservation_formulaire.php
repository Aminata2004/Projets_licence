<?php
class Reservation_formulaire extends Controller {
    public function index() {
        $id = $_GET['id'] ?? null;
        $trajet = null;

        if ($id) {
            $programmeModel = new Programme(); // Ton modèle
            $programme = $programmeModel->findById($id); // Assure-toi que findById existe

            if ($programme) {
                $trajet = [
                    'depart' => $programme->idDepart,
                    'destination' => $programme->idDestination,
                    'heure_depart' => $programme->heureDepart,
                    'prix' => $programme->prix
                ];
            }
        }

        $this->view('site/formulaire_reservation', ['trajet' => $trajet]);
    }
}
