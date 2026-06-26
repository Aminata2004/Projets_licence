<?php

class Compagnies extends Controller {
    public function index() {   
        $Compagnie = new Compagnie();
        $listecompagnie = $Compagnie->SelectAllData('*', "compagnie");  
        $this->view('site/compagnies', ['listecompagnie' => $listecompagnie]);
    }
}
