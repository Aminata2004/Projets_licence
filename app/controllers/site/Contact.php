<?php

class Contact extends Controller {
    public function index() {
        $Compagnie = new Compagnie();
        $Programme = new Programme();

        // Vrais chiffres (au lieu des "50+ / 15 / 10K+" fictifs codés en dur).
        $listecompagnie = $Compagnie->SelectAllData('*', "compagnie");
        $destinationsGlobales = [];
        if (!empty($listecompagnie)) {
            foreach ($listecompagnie as $c) {
                $programmes = $Programme->getByCompagnie($c->id_compagnie);
                foreach ($programmes as $p) {
                    $destinationsGlobales[$p->destinationLocalite] = true;
                }
            }
        }

        $stats = [
            'destinations' => count($destinationsGlobales),
            'compagnies'   => count($listecompagnie),
            'clients'      => (int) $Compagnie->selectCount('*', 'client')
        ];

        $this->view('site/contact', [
            'stats' => $stats
        ]);
    }
}

