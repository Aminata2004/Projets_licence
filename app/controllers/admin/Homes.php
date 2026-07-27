<?php

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
        $droit = $_SESSION['droit'];

        // Vue "plateforme" : le super_admin gère les compagnies, pas leur activité billet/colis
        if ($droit === 'super_admin') {
            $this->view('admin/home', [
                'mode'              => 'plateforme',
                'platformStats'     => $this->homeModel->getPlatformStats(),
                'compagniesOverview' => $this->homeModel->getCompagniesOverview()
            ]);
            return;
        }

        // Filtre par gare, réservé à l'Admin, et validé contre les gares de sa propre compagnie
        $listeGares = [];
        $gareId = null;
        $gareLabel = null;

        if ($droit === 'Admin') {
            $listeGares = $this->homeModel->getGaresCompagnie();

            if (!empty($_GET['gare'])) {
                foreach ($listeGares as $gare) {
                    if ((string)$gare->idAgence === (string)$_GET['gare']) {
                        $gareId = $gare->idAgence;
                        $gareLabel = $gare->localite;
                        break;
                    }
                }
            }
        }

        $profile = $_SESSION['profile'] ?? null;

        // Date consultee pour les indicateurs "du jour" : par defaut aujourd'hui, mais
        // consultable dans le passe comme deja possible sur Rapport Compagnie/Supervision
        // Escale (les chiffres d'un nouveau jour ne sont pas "perdus", juste plus affiches
        // par defaut puisque l'ecran montre "aujourd'hui" par defaut).
        $date = $_GET['date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date > date('Y-m-d')) {
            $date = date('Y-m-d');
        }

        // Un Utilisateur ne voit que le bloc de son service assigné (billet ou colis)
        $showBillets  = ($droit !== 'Utilisateur') || $profile === 'billet';
        $showColis    = ($droit !== 'Utilisateur') || $profile === 'colis';
        $showVoyages  = ($droit !== 'Utilisateur');
        $showTopGares = (in_array($droit, ['Admin', 'PDG'], true) && !$gareId);

        $data = [
            'mode'         => 'compagnie',
            'showBillets'  => $showBillets,
            'showColis'    => $showColis,
            'showVoyages'  => $showVoyages,
            'showTopGares' => $showTopGares,
            'listeGares'   => $listeGares,
            'gareId'       => $gareId,
            'gareLabel'    => $gareLabel,
            'date'         => $date,
        ];

        if ($showBillets) {
            $data['billetsJour'] = $this->homeModel->getBilletsJournalier($gareLabel, $date);
        }

        if ($showVoyages) {
            $data['voyagesJour'] = $this->homeModel->getVoyagesProgrammes($gareLabel, $gareId, $date);
        }

        if ($showColis) {
            $data['colisJour'] = $this->homeModel->getColisJournalier($gareLabel, $date);
        }

        if ($showTopGares) {
            $data['topGares'] = $this->homeModel->getTopGares();
        }

        // Aperçu du bénéfice du jour consulté, cliquable vers le tableau de bord financier détaillé
        if (in_array($droit, ['Admin', 'PDG'], true)) {
            $data['beneficeJour'] = (new Depense())->getBenefice('jour', $gareLabel, $date);
        }

        // Le chef d'escale voit la même répartition billets/colis, mais limitée à sa propre gare
        if ($droit === 'chef_d_escale') {
            $data['beneficeJour'] = (new Depense())->getBenefice('jour', $_SESSION['ville'] ?? null, $date);
        }

        // État de la caisse active du chef d'escale, cliquable vers la gestion de caisse
        if ($droit === 'chef_d_escale') {
            $data['caisseGare'] = $this->homeModel->getCaisseGare();
        }

        $data['activiteRecente'] = $this->homeModel->getActiviteRecente($gareLabel);

        // ── Données analytiques avancées (graphiques) ──────────────────
        if ($droit !== 'super_admin') {
            $data['performanceGlobale']  = $this->homeModel->getPerformanceGlobale();
            $data['tendance7j']          = $this->homeModel->getTendanceBillets7Jours();
            $data['revenus12Mois']       = $this->homeModel->getRevenusMensuels12Mois();
            $data['topTrajets']          = $this->homeModel->getTopTrajets();
            $data['evolutionColis6Mois'] = $this->homeModel->getEvolutionColis6Mois();
            $data['tauxRemplissage']     = $this->homeModel->getTauxRemplissage();
        }

        $this->view('admin/home', $data);
    }
}
