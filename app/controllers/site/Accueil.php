<?php

class Accueil extends Controller {
    public function index() {
        $Compagnie= new Compagnie();
        $Programme = new Programme();
        $listecompagnie = $Compagnie->SelectAllData('*', "compagnie");

        // Un onglet "Destinations populaires" par compagnie (même sans trajet, pour afficher
        // un message "aucun trajet disponible"). Une compagnie peut avoir plusieurs horaires
        // sur le même trajet (ex: Bamako → Ségou à 8h, 14h et 23h) : on ne fait qu'une seule
        // carte par destination distincte, mais on garde TOUTES les heures de départ dessus
        // (le prix affiché est le moins élevé parmi ces horaires).
        $programmesParCompagnie = [];
        $destinationsGlobales = [];
        if (!empty($listecompagnie)) {
            foreach ($listecompagnie as $c) {
                $programmes = $Programme->getByCompagnie($c->id_compagnie);

                $destinationsUniques = [];
                foreach ($programmes as $p) {
                    $cle = $p->departLocalite . '→' . $p->destinationLocalite;
                    if (!isset($destinationsUniques[$cle])) {
                        $destinationsUniques[$cle] = (object) [
                            'departLocalite' => $p->departLocalite,
                            'destinationLocalite' => $p->destinationLocalite,
                            'prix' => $p->prix,
                            'heures' => [$p->heureDepart]
                        ];
                    } else {
                        $destinationsUniques[$cle]->heures[] = $p->heureDepart;
                        if ($p->prix < $destinationsUniques[$cle]->prix) {
                            $destinationsUniques[$cle]->prix = $p->prix;
                        }
                    }
                    $destinationsGlobales[$p->destinationLocalite] = true;
                }

                foreach ($destinationsUniques as $d) {
                    $d->heures = array_unique($d->heures);
                    sort($d->heures);
                }

                $programmesParCompagnie[$c->id_compagnie] = array_values($destinationsUniques);
            }
        }

        // Chiffres réels du bandeau d'accueil et de la barre de stats plus bas sur la page
        // (au lieu de valeurs fictives codées en dur : "50+", "15", "200+"...).
        // "Trajets quotidiens" = nombre de lignes dans "programmer", toutes compagnies
        // confondues : chaque ligne est un départ programmé qui se répète chaque jour.
        $heroStats = [
            'destinations' => count($destinationsGlobales),
            'compagnies'   => count($listecompagnie),
            'clients'      => (int) $Compagnie->selectCount('*', 'client'),
            'trajets'      => (int) $Programme->selectCount('*', 'programmer')
        ];

        // Slider du hero : toutes les images présentes dans hero-slides/ sont utilisées,
        // dans l'ordre alphabétique (slide-1.jpg, slide-2.jpg, ...). Il suffit de déposer
        // de nouveaux fichiers dans ce dossier pour les voir apparaître dans la rotation,
        // sans toucher au code. Fonctionne hors-ligne car les images sont stockées en local.
        $heroSlides = glob(ROOT . '/public/assets_site/img/hero-slides/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        sort($heroSlides);
        $heroSlides = array_map(function ($path) {
            return BASE_URL . '/assets_site/img/hero-slides/' . basename($path);
        }, $heroSlides);

        $this->view('site/acceuil', [
            'listecompagnie' => $listecompagnie,
            'programmesParCompagnie' => $programmesParCompagnie,
            'heroStats' => $heroStats,
            'villes' => $Programme->getVillesDisponibles(),
            'heroSlides' => $heroSlides
        ]);
    }
}

