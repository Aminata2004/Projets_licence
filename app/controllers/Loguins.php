<?php
class Loguins extends Controller {
    public function index() {
        $loguin = new Loguin();
        if (isset($_POST["connexion"])) {
            $loguin->connecter();
        }
        $this->view("loguin");
    } 
}


