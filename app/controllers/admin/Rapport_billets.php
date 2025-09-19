<?php
class Rapport_billets extends Controller
{
    private $rapportBilletModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->rapportBilletModel = new Rapport_billet;
    }

    public function index()
    {
        if (!isset($_SESSION['id_compagnie'])) {
            die("⚠️ Aucune compagnie trouvée dans la session");
        }

        $compagnieId = $_SESSION['id_compagnie'];
        $mois = date('Y-m'); // mois courant

        // Récupération des totaux
        $totalPresentiel = $this->rapportBilletModel->getTotalParType($compagnieId, 'presentiel');
        $totalEnLigne = $this->rapportBilletModel->getTotalParType($compagnieId, 'en_ligne');
        $totalRepporte = $this->rapportBilletModel->getTotalParType($compagnieId, 'repporte');

        // Récupération par mois
        $statsParMois = $this->rapportBilletModel->getParMois($compagnieId);

        // Récupération billets par localité et gare selon le rôle
        if ($_SESSION['droit'] === 'Admin') {
            // Admin voit toutes les localités
            $billetsParGare = $this->rapportBilletModel->getSommeBilletsParLocaliteEtGare($compagnieId, null, $mois);
        } elseif ($_SESSION['droit'] === 'Admin_regionale') {
            // Admin régionale voit seulement sa localité
            $localite = $_SESSION['ville'];
            $billetsParGare = $this->rapportBilletModel->getSommeBilletsParLocaliteEtGare($compagnieId, $localite, $mois);
        } else {
            $billetsParGare = [];
        }

        // Fusionner tout dans $data
        $data = [
            'totalPresentiel' => $totalPresentiel,
            'totalEnLigne' => $totalEnLigne,
            'totalRepporte' => $totalRepporte,
            'statsParMois' => $statsParMois,
            'billetsParGare' => $billetsParGare
        ];

        // Débogage si besoin
        // var_dump($data['billetsParGare']); exit;

        // Appel de la vue
        $this->view('/admin/rapport_billets', $data);
    }


    public function rapport_billets()
    {
        $this->index();
    }

    public function rapport_annuel()
    {
        if (!isset($_SESSION['id_compagnie'])) {
            die("⚠️ Aucune compagnie trouvée dans la session");
        }

        $compagnieId = $_SESSION['id_compagnie'];
        $annee = date('Y'); // ou depuis GET pour filtre dynamique

        // Totaux annuels
        $totalPresentiel = $this->rapportBilletModel->getTotalParTypeAnnuel($compagnieId, 'presentiel', $annee);
        $totalEnLigne    = $this->rapportBilletModel->getTotalParTypeAnnuel($compagnieId, 'en_ligne', $annee);
        $totalRepporte   = $this->rapportBilletModel->getTotalParTypeAnnuel($compagnieId, 'repporte', $annee);

        // Statistiques annuelles par type
        $statsParAnnee = $this->rapportBilletModel->getParAnnee($compagnieId, $annee);

        // Billets par gare et localité
        if ($_SESSION['droit'] === 'Admin') {
            $billetsParGare = $this->rapportBilletModel->getSommeBilletsParLocaliteEtGareAnnuel($compagnieId, null, $annee);
        } elseif ($_SESSION['droit'] === 'Admin_regionale') {
            $localite = $_SESSION['ville'];
            $billetsParGare = $this->rapportBilletModel->getSommeBilletsParLocaliteEtGareAnnuel($compagnieId, $localite, $annee);
        } else {
            $billetsParGare = [];
        }

        $data = [
            'annee' => $annee,
            'totalPresentiel' => $totalPresentiel,
            'totalEnLigne' => $totalEnLigne,
            'totalRepporte' => $totalRepporte,
            'statsParAnnee' => $statsParAnnee,
            'billetsParGare' => $billetsParGare
        ];

        $this->view('/admin/rapport_billets_annuel', $data);
    }
}
