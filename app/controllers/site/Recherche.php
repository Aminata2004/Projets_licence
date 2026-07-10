<?php

class Recherche extends Controller {
    public function index() {
        $Programme = new Programme();
        $Compagnie = new Compagnie();

        $depart = trim($_GET['depart'] ?? '');
        $destination = trim($_GET['destination'] ?? '');
        $id_compagnie = trim($_GET['compagnie'] ?? '');
        $date = trim($_GET['date'] ?? '');

        $resultats = $Programme->rechercher($depart, $destination, $id_compagnie);

        $this->view('site/recherche', [
            'resultats' => $resultats,
            'depart' => $depart,
            'destination' => $destination,
            'id_compagnie' => $id_compagnie,
            'date' => $date,
            'listecompagnie' => $Compagnie->SelectAllData('*', 'compagnie'),
            'villes' => $Programme->getVillesDisponibles()
        ]);
    }
}
