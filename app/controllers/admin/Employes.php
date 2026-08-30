<?php
class Employes extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    // Vue unifiee "Employes" : regroupe les comptes utilisateur (avec leur droit/fonction)
    // et les chauffeurs de la compagnie, chacun visible seulement si l'utilisateur connecte
    // a la permission correspondante (memes permissions que les pages Configurations et
    // Chauffeurs_cars deja existantes, dont on reprend ici les memes requetes de filtrage
    // par compagnie).
    public function index()
    {
        $configuration = new Configuration($_SESSION['id_utilisateur']);

        $peutVoirUtilisateurs = $configuration->userHasPermission('utilisateur_apercu');
        $peutVoirChauffeurs = $configuration->userHasPermission('Configuration_gestion_car/chauffeur');

        if (!$peutVoirUtilisateurs && !$peutVoirChauffeurs) {
            $configuration->set_flash("Accès refusé : vous n'avez pas la permission nécessaire.", "danger");
            $this->redirect("admin/Homes/home");
            return;
        }

        $role = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;

        $employes = $this->buildEmployesListe($configuration, $peutVoirUtilisateurs, $peutVoirChauffeurs);

        $this->view('admin/employes', [
            'employes'             => $employes,
            'peutVoirUtilisateurs' => $peutVoirUtilisateurs,
            'peutVoirChauffeurs'   => $peutVoirChauffeurs,
        ]);
    }

    // Construit la liste unifiee des employes (utilisateurs + chauffeurs) de la compagnie
    // courante. Partagee entre l'affichage de la page (index()) et l'export PDF
    // (printListPdf()) pour ne pas dupliquer les requetes de recuperation.
    private function buildEmployesListe(Configuration $configuration, $peutVoirUtilisateurs, $peutVoirChauffeurs)
    {
        $role = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;

        $droitLabels = [
            'super_admin'   => 'Super administrateur',
            'Admin'         => 'Administrateur',
            'PDG'           => 'PDG (superviseur)',
            'chef_d_escale' => "Chef d'escale",
            'secretaire'    => 'Secrétaire',
            'Utilisateur'   => 'Utilisateur',
        ];

        $employes = [];

        if ($peutVoirUtilisateurs) {
            $userColumns = 'utilisateur.idUser, utilisateur.utilisateurs, utilisateur.emailUser, utilisateur.telephone,
                utilisateur.droit, utilisateur.profile, utilisateur.status, utilisateur.photo, agence.numeroGare';

            if ($role === 'super_admin') {
                $utilisateurs = $configuration->FetchSelectWheres(
                    $userColumns,
                    'utilisateur LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence',
                    "utilisateur.droit != 'super_admin'"
                );
            } else {
                // LEFT JOIN (et non INNER JOIN) car les comptes Admin/PDG n'ont pas
                // forcement d'agence assignee : ils sont rattaches directement a la
                // compagnie via utilisateur.id_compagnie (cf. Configuration::saveUtilisateur()).
                $utilisateurs = $configuration->FetchSelectWheres(
                    $userColumns,
                    'utilisateur LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence',
                    "utilisateur.droit != 'super_admin' AND (
                        (utilisateur.droit IN ('Admin', 'PDG', 'secretaire') AND utilisateur.id_compagnie = :id_compagnie_droit)
                        OR (utilisateur.droit NOT IN ('Admin', 'PDG', 'secretaire') AND agence.id_compagnie = :id_compagnie_agence)
                    )",
                    ['id_compagnie_droit' => $id_compagnie, 'id_compagnie_agence' => $id_compagnie]
                );
            }

            foreach ($utilisateurs as $u) {
                $fonction = $droitLabels[$u->droit] ?? $u->droit;
                if ($u->droit === 'Utilisateur' && !empty($u->profile)) {
                    $service = $u->profile === 'billet'
                        ? 'Billetterie'
                        : ($u->profile === 'colis' ? 'Colis / Courrier' : $u->profile);
                    $fonction .= ' - ' . $service;
                }

                $employes[] = [
                    'id'          => $u->idUser,
                    'type'        => 'Utilisateur',
                    'nom'         => $u->utilisateurs,
                    'fonction'    => $fonction,
                    'contact'     => $u->emailUser,
                    'telephone'   => $u->telephone ?: '—',
                    'affectation' => $u->numeroGare ?? '—',
                    'statut'      => ((int)$u->status === 1) ? 'Actif' : 'Inactif',
                    'photo'       => $u->photo ?? null,
                ];
            }
        }

        if ($peutVoirChauffeurs) {
            $chauffeursModel = new Chauffeurs_car();

            if ($role === 'super_admin') {
                $chauffeurs = $chauffeursModel->SelectAllData(
                    '*',
                    'chauffeur INNER JOIN car ON chauffeur.id_car = car.id_car'
                );
            } else {
                $chauffeurs = $chauffeursModel->FetchSelectWheres(
                    '*',
                    'chauffeur INNER JOIN car ON chauffeur.id_car = car.id_car',
                    'car.id_compagnie = :id_compagnie',
                    ['id_compagnie' => $id_compagnie]
                );
            }

            foreach ($chauffeurs as $c) {
                $employes[] = [
                    'id'          => $c->id_chauffeur,
                    'type'        => 'Chauffeur',
                    'nom'         => $c->nom_prenom,
                    'fonction'    => 'Chauffeur',
                    'contact'     => '—',
                    'telephone'   => $c->numero,
                    'affectation' => 'Car : ' . $c->numero_car,
                    'statut'      => 'Actif',
                    'photo'       => $c->photo ?? null,
                ];
            }
        }

        return $employes;
    }

    // Export PDF de la liste complete des employes affiches sur la page (memes
    // permissions et memes donnees que index()), telechargeable en un clic.
    public function printListPdf()
    {
        $this->requireLogin();
        $configuration = new Configuration($_SESSION['id_utilisateur']);

        $peutVoirUtilisateurs = $configuration->userHasPermission('utilisateur_apercu');
        $peutVoirChauffeurs = $configuration->userHasPermission('Configuration_gestion_car/chauffeur');

        if (!$peutVoirUtilisateurs && !$peutVoirChauffeurs) {
            $configuration->set_flash("Accès refusé : vous n'avez pas la permission nécessaire.", "danger");
            $this->redirect("admin/Homes/home");
            return;
        }

        $employes = $this->buildEmployesListe($configuration, $peutVoirUtilisateurs, $peutVoirChauffeurs);
        $compagnie = $this->getCompagnieCourante($configuration);

        ob_start();
        include ROOT . '/app/views/admin/pdf/employes_liste.php';
        $html = ob_get_clean();

        $opt = new \Dompdf\Options();
        $opt->setChroot(ROOT);
        $opt->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($opt);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream('liste_employes_' . date('Ymd_His') . '.pdf', ['Attachment' => true]);
        exit;
    }

    // Resout un employe (Utilisateur ou Chauffeur) vers le tableau attendu par la vue
    // print_card. Partagee entre l'impression d'une seule carte et l'impression
    // groupee (selection multiple), pour ne pas dupliquer les requetes de resolution.
    // Applique le meme perimetre de compagnie que la liste (index()) : un compte non
    // super_admin ne peut resoudre que les employes de sa propre compagnie.
    private function resolveEmploye($type, $id, Configuration $configuration)
    {
        $role = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $employe = null;

        if ($type === 'Utilisateur') {
            $u = $configuration->getUserById($id);
            if ($u) {
                $employe = [
                    'type' => 'Utilisateur',
                    'nom' => $u['utilisateurs'],
                    'fonction' => $u['droit'],
                    'contact' => $u['emailUser'],
                    'telephone' => $u['telephone'],
                    'affectation' => '',
                    'localite' => '',
                    'photo' => $u['photo'] ?? null,
                ];
                // Admin/PDG sont rattaches directement a la compagnie via
                // utilisateur.id_compagnie (pas d'agence assignee), cf. buildEmployesListe().
                if (in_array($u['droit'], ['Admin', 'PDG', 'secretaire'], true)) {
                    $employeCompagnie = $u['id_compagnie'] ?? null;
                } else {
                    $employeCompagnie = null;
                    if (!empty($u['id_agence'])) {
                        $agence = $configuration->SelectAllData('*', 'agence WHERE idAgence = ' . (int)$u['id_agence']);
                        if (!empty($agence)) {
                            $employe['affectation'] = $agence[0]->numeroGare;
                            $employe['localite'] = $agence[0]->localite;
                            $employeCompagnie = $agence[0]->id_compagnie ?? null;
                        }
                    }
                }
                if ($role !== 'super_admin' && (int)$employeCompagnie !== (int)$id_compagnie) {
                    return null;
                }
            }
        } elseif ($type === 'Chauffeur') {
            $chauffeursModel = new Chauffeurs_car();
            $c = $chauffeursModel->SelectAllData('*', 'chauffeur WHERE id_chauffeur = ' . (int)$id);
            if (!empty($c)) {
                $c = $c[0];
                $employe = [
                    'type' => 'Chauffeur',
                    'nom' => $c->nom_prenom,
                    'fonction' => 'Chauffeur',
                    'contact' => '—',
                    'telephone' => $c->numero,
                    'photo' => $c->photo ?? null,
                    'affectation' => '',
                    'localite' => ''
                ];
                $carCompagnie = null;
                if (!empty($c->id_car)) {
                    $car = $chauffeursModel->SelectAllData('*', 'car WHERE id_car = ' . (int)$c->id_car);
                    if (!empty($car)) {
                        $employe['affectation'] = 'Car : ' . $car[0]->numero_car;
                        $carCompagnie = $car[0]->id_compagnie ?? null;
                    }
                }
                if ($role !== 'super_admin' && (int)$carCompagnie !== (int)$id_compagnie) {
                    return null;
                }
            }
        }

        return $employe;
    }

    private function getCompagnieCourante(Configuration $configuration)
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) {
            return null;
        }
        $comp = $configuration->SelectAllData('*', 'compagnie WHERE id_compagnie = ' . (int)$id_compagnie);
        return !empty($comp) ? $comp[0] : null;
    }

    public function printCard($type, $id, $format = 1)
    {
        $this->requireLogin();
        $configuration = new Configuration($_SESSION['id_utilisateur']);

        $employe = $this->resolveEmploye($type, $id, $configuration);

        if (!$employe) {
            $configuration->set_flash("Employé introuvable.", "danger");
            $this->redirect("admin/Employes/index");
            return;
        }

        $this->view('admin/print_card', [
            'employes' => [$employe],
            'format' => (int)$format,
            'compagnie' => $this->getCompagnieCourante($configuration)
        ]);
    }

    // Impression groupee : depuis la selection multiple de la liste des employes,
    // imprime jusqu'a 4 badges par feuille A4 (pagination automatique au-dela).
    public function printSelection()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
            $this->redirect("admin/Employes/index");
            return;
        }

        $configuration = new Configuration($_SESSION['id_utilisateur']);
        $selection = $_POST['selection'] ?? [];
        $format = (int)($_POST['format'] ?? 1);
        if ($format < 1 || $format > 1) {
            $format = 1;
        }

        $employes = [];
        if (is_array($selection)) {
            foreach ($selection as $item) {
                if (!is_string($item) || strpos($item, ':') === false) {
                    continue;
                }
                [$type, $id] = explode(':', $item, 2);
                if (!in_array($type, ['Utilisateur', 'Chauffeur'], true) || !ctype_digit((string)$id)) {
                    continue;
                }
                $employe = $this->resolveEmploye($type, (int)$id, $configuration);
                if ($employe) {
                    $employes[] = $employe;
                }
            }
        }

        if (empty($employes)) {
            $configuration->set_flash("Aucun employé sélectionné.", "danger");
            $this->redirect("admin/Employes/index");
            return;
        }

        $this->view('admin/print_card', [
            'employes' => $employes,
            'format' => $format,
            'compagnie' => $this->getCompagnieCourante($configuration)
        ]);
    }
}
