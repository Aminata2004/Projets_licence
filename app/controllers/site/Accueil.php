<?php

class Accueil extends Controller {
    public function index() {   
        $Compagnie= new Compagnie();
        $listecompagnie = $Compagnie->SelectAllData('*', "compagnie");  
       $this->view('site/acceuil', ['listecompagnie' => $listecompagnie]);
    }
}

