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

        $droitLabels = [
            'super_admin'   => 'Super administrateur',
            'Admin'         => 'Administrateur',
            'PDG'           => 'PDG (superviseur)',
            'chef_d_escale' => "Chef d'escale",
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
                $utilisateurs = $configuration->FetchSelectWheres(
                    $userColumns,
                    'utilisateur INNER JOIN agence ON agence.idAgence = utilisateur.id_agence',
                    "agence.id_compagnie = :id_compagnie AND utilisateur.droit != 'super_admin'",
                    ['id_compagnie' => $id_compagnie]
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

        $this->view('admin/employes', [
            'employes'             => $employes,
            'peutVoirUtilisateurs' => $peutVoirUtilisateurs,
            'peutVoirChauffeurs'   => $peutVoirChauffeurs,
        ]);
    }

    public function printCard($type, $id, $format = 1)
    {
        $this->requireLogin();
        $configuration = new Configuration($_SESSION['id_utilisateur']);
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
                if (!empty($u['id_agence'])) {
                    $agence = $configuration->SelectAllData('*', 'agence WHERE idAgence = ' . (int)$u['id_agence']);
                    if (!empty($agence)) {
                        $employe['affectation'] = $agence[0]->numeroGare;
                        $employe['localite'] = $agence[0]->localite;
                    }
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
                if (!empty($c->id_car)) {
                    $car = $chauffeursModel->SelectAllData('*', 'car WHERE id_car = ' . (int)$c->id_car);
                    if (!empty($car)) {
                        $employe['affectation'] = 'Car : ' . $car[0]->numero_car;
                    }
                }
            }
        }

        if (!$employe) {
            $configuration->set_flash("Employé introuvable.", "danger");
            $this->redirect("admin/Employes/index");
            return;
        }

        $compagnie = null;
        if ($id_compagnie) {
            $comp = $configuration->SelectAllData('*', 'compagnie WHERE id_compagnie = ' . (int)$id_compagnie);
            if (!empty($comp)) {
                $compagnie = $comp[0];
            }
        }

        $this->view('admin/print_card', [
            'employe' => $employe,
            'format' => (int)$format,
            'compagnie' => $compagnie
        ]);
    }
}
