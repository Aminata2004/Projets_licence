<?php

// class Homes extends Controller
// {

//     public function __construct()
//     {
//         $this->requireLogin();
//     }


//     public function home()
//     {
//         // Code à exécuter pour la page admin home

//         $this->view('admin/home');
//     }
// }


class Homes extends Controller
{
    private $homeModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->homeModel = new Home; // Charger le modèle
    }

    public function home()
    {
        $session = [
            'id_utilisateur' => $_SESSION['id_utilisateur']
        ];

        // Récupérer les données depuis le modèle
        $billetsJour    = $this->homeModel->getBilletsJournalier($_SESSION['id_utilisateur']);
        $voyagesJour    = $this->homeModel->getVoyagesProgrammes();
        $colisMensuel   = $this->homeModel->getColisMensuel($session); // fonction unique pour tous les statuts mensuels

        // Envoyer les données à la vue
        $this->view('admin/home', [
            'billetsJour' => $billetsJour,
            'voyagesJour' => $voyagesJour,
            'colisMensuel' => $colisMensuel
        ]);
    }
}

