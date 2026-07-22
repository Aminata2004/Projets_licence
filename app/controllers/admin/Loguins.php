<?php
// class Loguins extends Controller {
//     public function index() {
//         $loguin = new Loguin();
//         if (isset($_POST["connexion"])) {
//             $loguin->connecter();
//         }
//         $this->view("loguin");
//     } 
// }

class Loguins extends Controller {
    public function index() {
        $model = new \Loguin();  // instancie ton modèle
        $model->seedSuperAdminsSiTableVide();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["connexion"])) {
            $model->connecter();
        }
        $this->view("admin/loguin");  // affiche le formulaire
    }
}



