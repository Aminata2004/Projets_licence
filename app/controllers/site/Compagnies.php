<?php

class Compagnies extends Controller {
    public function index() {
        $Compagnie = new Compagnie();
        $Programme = new Programme();
        $listecompagnie = $Compagnie->SelectAllData('*', "compagnie");

        // Vrais chiffres par compagnie (au lieu des rand() fictifs affichés avant) :
        // nombre de trajets programmés et de destinations distinctes desservies.
        $statsParCompagnie = [];
        if (!empty($listecompagnie)) {
            foreach ($listecompagnie as $c) {
                $programmes = $Programme->getByCompagnie($c->id_compagnie);
                $destinations = [];
                foreach ($programmes as $p) {
                    $destinations[$p->destinationLocalite] = true;
                }
                $statsParCompagnie[$c->id_compagnie] = [
                    'trajets' => count($programmes),
                    'destinations' => count($destinations)
                ];
            }
        }

        $this->view('site/compagnies', [
            'listecompagnie' => $listecompagnie,
            'statsParCompagnie' => $statsParCompagnie
        ]);
    }
}
