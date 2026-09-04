<?php
class Salaires extends Controller
{
    public function __construct()
    {
        // Cette permission gouverne TOUTE la visibilité du module, y compris son
        // propre salaire (cf. GESTION_SALAIRES.md) : sans elle, personne d'autre
        // qu'Admin/PDG/super_admin (qui l'ont par défaut) n'accède à cette page.
        $this->requirePermission('Salaire_apercu');
    }

    // La permission ci-dessus ne donne que la LECTURE. La création/modification
    // d'une fiche employé et des montants de salaire reste une action financière
    // réservée à l'encadrement, comme Depenses.php le fait déjà pour la validation
    // des dépenses.
    private function requireGestionnaire()
    {
        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'super_admin'], true)) {
            (new Employe())->set_flash("Action réservée à l'administration.", "danger");
            header("Location: " . BASE_URL . "/admin/Salaires");
            exit;
        }
    }

    public function index()
    {
        $model = new Employe();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
            $this->requireGestionnaire();
            $model->saveEmployeHorsSysteme();
            header("Location: " . BASE_URL . "/admin/Salaires");
            exit;
        }

        // Gares de la compagnie, pour le formulaire d'ajout/édition (rattachement
        // du personnel hors-système à une gare) -- réservé Admin/PDG/super_admin,
        // un chef d'escale qui verrait cette page (lecture seule) n'en a pas besoin.
        $listeAgences = [];
        if (in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'super_admin'], true)) {
            $listeAgences = $model->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                [':id_compagnie' => $_SESSION['id_compagnie'] ?? null]
            );
        }

        $this->view('admin/salaires', [
            'listeEmployes' => $model->getEmployesVisibles(),
            'listeAgences' => $listeAgences,
            'peutGerer' => in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'super_admin'], true)
        ]);
    }

    public function update()
    {
        $this->requireGestionnaire();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Employe();
            $id = $_POST['id_employe'];

            $salaire_base = $_POST['salaire_base'] ?? '';
            if ($salaire_base === '' || !is_numeric($salaire_base) || $salaire_base < 0) {
                $model->set_flash("Le salaire de base doit être un nombre positif.", "danger");
                header("Location: " . BASE_URL . "/admin/Salaires");
                exit;
            }

            $data = [
                'poste' => $_POST['poste'],
                'salaire_base' => $salaire_base,
                'id_agence' => !empty($_POST['id_agence']) ? $_POST['id_agence'] : null,
                'statut' => $_POST['statut'] ?? 'actif'
            ];
            // Le nom n'est éditable ici que pour le personnel hors-système (sinon il
            // vient de utilisateur/chauffeur) : le champ n'est présent dans le POST
            // que dans ce cas (cf. app/views/admin/salaires.view.php).
            if (isset($_POST['nom_prenom'])) {
                $data['nom_prenom'] = $_POST['nom_prenom'];
            }

            $model->updateSalaire($id, $data);
            $model->set_flash("Fiche employé mise à jour avec succès.", "info");
            header("Location: " . BASE_URL . "/admin/Salaires");
            exit;
        }
    }

    public function generer_bulletin()
    {
        $this->requireGestionnaire();

        $model = new Employe();
        $bulletinModel = new BulletinPaie();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_employe'], $_POST['periode'])) {
            $employe = $model->getEmployeVisibleById($_POST['id_employe']);
            if (!$employe) {
                $bulletinModel->set_flash("Employé introuvable.", "danger");
                header("Location: " . BASE_URL . "/admin/Salaires");
                exit;
            }

            $bulletinModel->genererBulletin(
                $employe->id_employe,
                $_POST['periode'],
                $employe->salaire_base,
                $_SESSION['id_utilisateur'] ?? null
            );
            $bulletinModel->set_flash("Bulletin généré avec succès.", "success");
        } else {
            $bulletinModel->set_flash("Données invalides pour la génération du bulletin.", "danger");
        }

        header("Location: " . BASE_URL . "/admin/Salaires/liste_bulletins");
        exit;
    }

    public function liste_bulletins()
    {
        $bulletinModel = new BulletinPaie();
        $this->view('admin/liste_bulletins', [
            'listeBulletins' => $bulletinModel->getBulletinsVisibles()
        ]);
    }

    public function telecharger_bulletin($id)
    {
        $bulletinModel = new BulletinPaie();
        $bulletin = $bulletinModel->getBulletinVisibleById($id);

        if (!$bulletin) {
            $bulletinModel->set_flash("Bulletin introuvable.", "danger");
            header("Location: " . BASE_URL . "/admin/Salaires/liste_bulletins");
            exit;
        }

        ob_start();
        include ROOT . '/app/views/admin/pdf/bulletin_paie.php';
        $html = ob_get_clean();

        $opt = new \Dompdf\Options();
        $opt->setChroot(ROOT);
        $opt->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($opt);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('bulletin_paie_' . $bulletin->periode . '.pdf', ['Attachment' => true]);
        exit;
    }
}
