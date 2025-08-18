<?php

class Personnes extends Controller {

    public function index()  {
        $perso=new Personne();
        if (isset($_POST["submit"])) {
            # code...
           $perso-> inscription(["nom","prenom","contact","email"]);
        }
        $this->view("inscription");
    }
  
}


?>