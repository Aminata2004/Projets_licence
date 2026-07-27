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
            $userColumns = 'utilisateur.idUser, utilisateur.utilisateurs, utilisateur.emailUser,
                utilisateur.droit, utilisateur.profile, utilisateur.status, agence.numeroGare';

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
                    'type'        => 'Utilisateur',
                    'nom'         => $u->utilisateurs,
                    'fonction'    => $fonction,
                    'contact'     => $u->emailUser,
                    'affectation' => $u->numeroGare ?? '—',
                    'statut'      => ((int)$u->status === 1) ? 'Actif' : 'Inactif',
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
                    'type'        => 'Chauffeur',
                    'nom'         => $c->nom_prenom,
                    'fonction'    => 'Chauffeur',
                    'contact'     => $c->numero,
                    'affectation' => 'Car : ' . $c->numero_car,
                    'statut'      => 'Actif',
                ];
            }
        }

        $this->view('admin/employes', [
            'employes'             => $employes,
            'peutVoirUtilisateurs' => $peutVoirUtilisateurs,
            'peutVoirChauffeurs'   => $peutVoirChauffeurs,
        ]);
    }
}
