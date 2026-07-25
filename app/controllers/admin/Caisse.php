<?php
class Caisse extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // VUE PRINCIPALE (caisse d'agence – accès Admin / chef d'escale)
    // ─────────────────────────────────────────────────────────────────────────

    public function index()
    {
        $liste_gare = new Liste_gare();
        $id_compagnie = $_SESSION['id_compagnie'];

        $liste_caisse = $liste_gare->FetchSelectWheres(
            '*',
            'caisse c INNER JOIN agence a ON c.id_agence = a.idAgence',
            'a.id_compagnie = :id_compagnie',
            ['id_compagnie' => $id_compagnie]
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_caisse'], $_POST['newStatut'])) {
            if (!csrf_verify()) {
                $liste_gare->set_flash("Session expirée, merci de réessayer.", "danger");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            $id = (int)$_POST['id_caisse'];
            $status_caisse = (int)$_POST['newStatut'];

            // 🔒 Un chef d'escale ne peut clôturer que la caisse de sa propre gare précise
            // (idAgence), pas seulement de sa ville — une ville peut avoir plusieurs gares,
            // et un chef d'escale de Segou (gare 1) ne gère pas les autres gares de Segou.
            $autorise = true;
            if (($_SESSION['droit'] ?? null) === 'chef_d_escale') {
                $caisseCible = $liste_gare->FetchSelectWhere(
                    "c.id_caisse",
                    "caisse c INNER JOIN agence a ON c.id_agence = a.idAgence",
                    "c.id_caisse = :id_caisse AND a.id_compagnie = :id_compagnie AND a.idAgence = :id_agence",
                    [":id_caisse" => $id, ":id_compagnie" => $id_compagnie, ":id_agence" => $_SESSION['id_agence']]
                );
                $autorise = (bool) $caisseCible;
            }

            if (!$autorise) {
                $liste_gare->set_flash("Vous ne pouvez clôturer que la caisse de votre propre agence.", "danger");
            } else {
                // Enregistrer la date de fermeture
                $date_fermeture = date('Y-m-d');

                $result = $liste_gare->insertion_update_simple(
                    "UPDATE caisse SET status_caisse = 0, date_fermeture = :date_fermeture WHERE id_caisse = :id_caisse",
                    [
                        ":date_fermeture"  => $date_fermeture,
                        ":id_caisse"       => $id
                    ]
                );

                if ($result !== false) {
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    $liste_gare->set_flash("Erreur lors de la mise à jour du statut.", "danger");
                }
            }
        }


        $this->view('admin/caisse', ['liste_caisse' => $liste_caisse]);
    }
    public function add_caisse()
    {
        $liste_gare = new Liste_gare();
        $id_compagnie = $_SESSION['id_compagnie'];

        if (($_SESSION['droit'] ?? null) === 'chef_d_escale') {
            // Un chef d'escale ne doit voir/choisir que sa propre gare précise (idAgence),
            // pas toutes les gares de sa ville
            $listes = $liste_gare->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie AND idAgence = :id_agence',
                ['id_compagnie' => $id_compagnie, 'id_agence' => $_SESSION['id_agence']]
            );
        } else {
            $listes = $liste_gare->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                ['id_compagnie' => $id_compagnie]
            );
        }

        if (isset($_POST["saveAgence"])) {
            if (!csrf_verify()) {
                $liste_gare->set_flash("Session expirée, merci de réessayer.", "danger");
            } else {
                $liste_gare->saveCaisse();
            }
        }
        $this->view('admin/add_caisse', ['listes' => $listes]);
    }



    

    // $numeroGare : quand fourni, restreint la somme à cette gare précise (une ville peut
    // avoir plusieurs gares) ; sinon, agrège sur toute la ville (vue Admin).
    public function getSommeBillets($pdo, $compagnieId, $ville, $periode = 'jour', $numeroGare = null)
    {
        $sql = "
    SELECT SUM(CAST(REPLACE(REPLACE(c.montant_payer, ' ', ''), 'FCFA', '') AS DECIMAL(12,2))) as total
    FROM billets b
    INNER JOIN client c ON b.id_client = c.idClient
    WHERE b.id_compagnie = :compagnie
      AND b.validation_billets = 'valider'
      AND (b.status_billets IS NULL OR b.status_billets != 'annule')
      AND b.departId = :ville
";

        $params = [
            'compagnie' => $compagnieId,
            'ville'     => $ville
        ];

        if ($numeroGare !== null) {
            $sql .= " AND b.num_gare = :numero_gare";
            $params['numero_gare'] = $numeroGare;
        }

        if ($periode === 'jour') {
            $sql .= " AND b.date_reservation = CURDATE()";
        } elseif ($periode === 'mois') {
            $sql .= " AND MONTH(b.date_reservation) = MONTH(CURDATE())
                  AND YEAR(b.date_reservation) = YEAR(CURDATE())";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() ?: 0;
    }


   public function getSommeColis($pdo, $compagnieId, $ville, $periode = 'jour', $numeroGare = null)
{
    $sql = "
        SELECT SUM(fraix_transaction) as total
        FROM colis
        WHERE id_compagnie = :compagnie
          AND provient_de = :ville
    ";

    $params = [
        'compagnie' => $compagnieId,
        'ville'     => $ville
    ];

    if ($numeroGare !== null) {
        $sql .= " AND num_gare = :numero_gare";
        $params['numero_gare'] = $numeroGare;
    }

    if ($periode === 'jour') {
        $sql .= " AND date_enregistrement = CURDATE()";
    } elseif ($periode === 'mois') {
        $sql .= " AND MONTH(date_enregistrement) = MONTH(CURDATE())
                  AND YEAR(date_enregistrement) = YEAR(CURDATE())";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() ?: 0;
}




    public function bilant_caisse_billets()
    {
        $liste_gare = new Liste_gare();
        $id_compagnie = $_SESSION['id_compagnie'];
        $ville        = $_SESSION['ville']; // Agence connectée
        $role         = $_SESSION['droit'];  // Exemple: 'Admin' ou 'chef_d_escale'

        // Base query
        $condition = 'a.id_compagnie = :id_compagnie';
        $params    = ['id_compagnie' => $id_compagnie];

        // Un chef d'escale ne gère que sa gare précise (idAgence), pas toutes les gares
        // de sa ville — une ville peut avoir plusieurs gares distinctes.
        if ($role === 'chef_d_escale') {
            $condition .= ' AND a.idAgence = :id_agence';
            $params['id_agence'] = $_SESSION['id_agence'];
        }

        // Récupère toutes les caisses
        $liste_caisse = $liste_gare->FetchSelectWheres(
            'c.*, a.localite, a.numeroGare',
            'caisse c INNER JOIN agence a ON c.id_agence = a.idAgence',
            $condition,
            $params
        );
        // Debug: vérifier les données récupérées

        // 🔥 Pour chaque caisse → calcul des montants
        $pdo = $liste_gare->connect();

        foreach ($liste_caisse as &$caisse) {

            $caisse->total_jour = $this->getSommeBillets($pdo, $id_compagnie, $caisse->localite, 'jour', $caisse->numeroGare);
            $caisse->total_mois = $this->getSommeBillets($pdo, $id_compagnie, $caisse->localite, 'mois', $caisse->numeroGare);
        }


        $this->view('admin/bilant_caisse_billets', ['liste_caisse' => $liste_caisse]);
    }


    public function bilant_caisse_colis()
    {
        $liste_gare = new Liste_gare();
        $id_compagnie = $_SESSION['id_compagnie'];
        $ville        = $_SESSION['ville']; // Agence connectée
        $role         = $_SESSION['droit'];  // Exemple: 'Admin' ou 'chef_d_escale'

        // Base query
        $condition = 'a.id_compagnie = :id_compagnie';
        $params    = ['id_compagnie' => $id_compagnie];

        // Un chef d'escale ne gère que sa gare précise (idAgence), pas toutes les gares
        // de sa ville — une ville peut avoir plusieurs gares distinctes.
        if ($role === 'chef_d_escale') {
            $condition .= ' AND a.idAgence = :id_agence';
            $params['id_agence'] = $_SESSION['id_agence'];
        }

        // Récupère toutes les caisses
        $liste_caisse = $liste_gare->FetchSelectWheres(
            'c.*, a.localite, a.numeroGare',
            'caisse c INNER JOIN agence a ON c.id_agence = a.idAgence',
            $condition,
            $params
        );
        // Debug: vérifier les données récupérées

        // 🔥 Pour chaque caisse → calcul des montants
        $pdo = $liste_gare->connect();

        foreach ($liste_caisse as &$caisse) {

            $caisse->total_jour = $this->getSommeColis($pdo, $id_compagnie, $caisse->localite, 'jour', $caisse->numeroGare);
            $caisse->total_mois = $this->getSommeColis($pdo, $id_compagnie, $caisse->localite, 'mois', $caisse->numeroGare);
        }


        $this->view('admin/bilant_caisse_colis', ['liste_caisse' => $liste_caisse]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CAISSE INDIVIDUELLE – OPÉRATEUR (billettère / agent colis)
    // ─────────────────────────────────────────────────────────────────────────

    /** Dashboard de la caisse personnelle de l'utilisateur connecté. */
    public function ma_caisse()
    {
        $idUser        = (int)($_SESSION['id_utilisateur'] ?? 0);
        $model         = new Caisse_utilisateur();
        $caisse        = $model->getCaisseOuverte($idUser);
        // Caisse fermée aujourd'hui mais pas encore versée : doit rester visible (avec un
        // bouton "Verser") tant qu'aucune nouvelle caisse n'est ouverte.
        $caisseFermee  = $caisse ? null : $model->getCaisseFermeeNonVersee($idUser);
        $journal       = $caisse ? $model->getJournal($caisse->id_caisse_user) : [];
        $historique    = $model->getHistoriqueCaisses($idUser, 20);

        $this->view('admin/ma_caisse', [
            'caisse'       => $caisse,
            'caisseFermee' => $caisseFermee,
            'journal'      => $journal,
            'historique'   => $historique,
        ]);
    }

    /**
     * Formulaire + traitement d'ouverture de la caisse individuelle.
     *
     * Un Admin n'a pas de gare fixe en session (contrairement à chef_d_escale/Utilisateur) :
     * il doit choisir la gare concernée dans le formulaire, comme pour une vente de billet
     * (voir Add_billet::resolveDepart()). Sans ce choix, ouvrirCaisse() n'avait aucune gare
     * à enregistrer et refusait l'ouverture ("Informations de session manquantes.").
     */
    public function ouvrir_caisse_user()
    {
        $model     = new Caisse_utilisateur();
        $idUser    = (int)($_SESSION['id_utilisateur'] ?? 0);
        $existante = $model->getCaisseOuverte($idUser);
        $estAdmin  = ($_SESSION['droit'] ?? null) === 'Admin';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ouvrir_caisse'])) {
            if (!csrf_verify()) {
                $model->set_flash("Session expirée, réessayez.", "danger");
            } elseif ($existante) {
                $model->set_flash("Vous avez déjà une caisse ouverte (Réf : {$existante->reference}).", "warning");
            } else {
                $ok = $model->ouvrirCaisse();
                if ($ok) {
                    header("Location: " . BASE_URL . "/admin/Caisse/ma_caisse");
                    exit;
                }
            }
        }

        $listeAgences = [];
        if ($estAdmin) {
            $listeAgences = $model->FetchSelectWheres(
                'idAgence, localite, numeroGare',
                'agence',
                'id_compagnie = :id_compagnie',
                [':id_compagnie' => $_SESSION['id_compagnie'] ?? null]
            );
        }

        $this->view('admin/ouvrir_caisse_user', [
            'existante'    => $existante,
            'estAdmin'     => $estAdmin,
            'listeAgences' => $listeAgences,
        ]);
    }

    /** Formulaire + traitement de fermeture de la caisse individuelle. */
    public function fermer_caisse_user()
    {
        $idUser = (int)($_SESSION['id_utilisateur'] ?? 0);
        $model  = new Caisse_utilisateur();
        $caisse = $model->getCaisseOuverte($idUser);

        if (!$caisse) {
            $model->set_flash("Aucune caisse ouverte à fermer.", "warning");
            header("Location: " . BASE_URL . "/admin/Caisse/ma_caisse");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fermer_caisse'])) {
            $ok = $model->fermerCaisse();
            if ($ok) {
                header("Location: " . BASE_URL . "/admin/Caisse/ma_caisse");
                exit;
            }
        }

        $montantAttendu = (float)$caisse->montant_initial + (float)$caisse->total_billets + (float)$caisse->total_colis;
        $this->view('admin/fermer_caisse_user', [
            'caisse'          => $caisse,
            'montant_attendu' => $montantAttendu,
        ]);
    }

    /** Versement de l'opérateur vers le chef d'escale. */
    public function verser()
    {
        $idUser      = (int)($_SESSION['id_utilisateur'] ?? 0);
        $idAgence    = (int)($_SESSION['id_agence'] ?? 0);
        $idCompagnie = (int)($_SESSION['id_compagnie'] ?? 0);
        $model       = new Caisse_utilisateur();

        $pdo  = $model->connect();
        $stmt = $pdo->prepare("
            SELECT * FROM caisse_utilisateur
            WHERE id_utilisateur = :u AND date_service = CURDATE() AND statut = 'fermee'
            LIMIT 1
        ");
        $stmt->execute([':u' => $idUser]);
        $caisse = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$caisse) {
            $model->set_flash("Fermez d'abord votre caisse avant de procéder au versement.", "warning");
            header("Location: " . BASE_URL . "/admin/Caisse/ma_caisse");
            exit;
        }

        $chefs = $model->getChefsDEscale($idAgence, $idCompagnie);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['effectuer_versement'])) {
            if (!csrf_verify()) {
                $model->set_flash("Session expirée.", "danger");
            } else {
                $ok = $model->creerVersement();
                if ($ok) {
                    header("Location: " . BASE_URL . "/admin/Caisse/ma_caisse");
                    exit;
                }
            }
        }

        $this->view('admin/versements', ['caisse' => $caisse, 'chefs' => $chefs, 'mode' => 'creer']);
    }

    /** Chef d'escale : valide ou rejette un versement (POST). */
    public function valider_versement()
    {
        $model = new Caisse_utilisateur();
        if (!csrf_verify()) {
            $model->set_flash("Session expirée.", "danger");
            header("Location: " . BASE_URL . "/admin/Caisse/caisses_escale");
            exit;
        }
        $idVersement = (int)($_POST['id_versement'] ?? 0);
        $action      = $_POST['action'] ?? '';
        $model->validerVersement($idVersement, $action);
        header("Location: " . BASE_URL . "/admin/Caisse/caisses_escale");
        exit;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CHEF D'ESCALE
    // ─────────────────────────────────────────────────────────────────────────

    /** Vue chef d'escale : toutes les caisses + versements en attente. */
    public function caisses_escale()
    {
        $idAgence = (int)($_SESSION['id_agence'] ?? 0);
        $idChef   = (int)($_SESSION['id_utilisateur'] ?? 0);
        $date     = $_GET['date'] ?? date('Y-m-d');
        $model    = new Caisse_utilisateur();

        $caisses           = $model->getCaissesEscale($idAgence, $date);
        $versementsAttente = $model->getVersementsEnAttente($idChef, $idAgence);
        $historiqueVers    = $model->getHistoriqueVersements($idChef, $idAgence);

        $totalBillets = $totalColis = $totalEcarts = 0;
        foreach ($caisses as $c) {
            $totalBillets += (float)$c->total_billets;
            $totalColis   += (float)$c->total_colis;
            $totalEcarts  += isset($c->ecart) ? (float)$c->ecart : 0;
        }

        $this->view('admin/caisses_escale', [
            'caisses'            => $caisses,
            'versements_attente' => $versementsAttente,
            'historique_vers'    => $historiqueVers,
            'date'               => $date,
            'total_billets'      => $totalBillets,
            'total_colis'        => $totalColis,
            'total_ecarts'       => $totalEcarts,
            'grand_total'        => $totalBillets + $totalColis,
        ]);
    }

    /** Chef d'escale : clôture la journée et génère le rapport. */
    public function cloture_escale()
    {
        $model    = new Caisse_utilisateur();
        $idAgence = (int)($_SESSION['id_agence'] ?? 0);
        $date     = $_GET['date'] ?? date('Y-m-d');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cloturer'])) {
            if (!csrf_verify()) {
                $model->set_flash("Session expirée.", "danger");
            } else {
                $ok = $model->cloturerEscale();
                if ($ok) {
                    header("Location: " . BASE_URL . "/admin/Caisse/caisses_escale");
                    exit;
                }
            }
        }

        $caisses = $model->getCaissesEscale($idAgence, $date);
        $totalBillets = $totalColis = $totalEcarts = 0;
        foreach ($caisses as $c) {
            $totalBillets += (float)$c->total_billets;
            $totalColis   += (float)$c->total_colis;
            $totalEcarts  += isset($c->ecart) ? (float)$c->ecart : 0;
        }

        $pdo  = $model->connect();
        $stmt = $pdo->prepare("SELECT * FROM clotures_escale WHERE id_agence = :agence ORDER BY date_cloture DESC LIMIT 30");
        $stmt->execute([':agence' => $idAgence]);
        $historique_clotures = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view('admin/cloture_escale', [
            'caisses'             => $caisses,
            'date'                => $date,
            'total_billets'       => $totalBillets,
            'total_colis'         => $totalColis,
            'total_ecarts'        => $totalEcarts,
            'grand_total'         => $totalBillets + $totalColis,
            'historique_clotures' => $historique_clotures,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROPRIÉTAIRE / ADMIN
    // ─────────────────────────────────────────────────────────────────────────

    /** Vue consolidée pour le propriétaire : toutes les escales. */
    public function rapport_proprietaire()
    {
        $idCompagnie = (int)($_SESSION['id_compagnie'] ?? 0);
        $date        = $_GET['date'] ?? date('Y-m-d');
        $model       = new Caisse_utilisateur();
        $rapport     = $model->getRapportProprietaire($idCompagnie, $date);

        $grandTotal = $totalBillets = $totalColis = $totalEcarts = 0;
        foreach ($rapport as $r) {
            $totalBillets += (float)$r->total_billets;
            $totalColis   += (float)$r->total_colis;
            $totalEcarts  += (float)$r->total_ecarts;
        }
        $grandTotal = $totalBillets + $totalColis;

        $this->view('admin/rapport_proprietaire', [
            'rapport'       => $rapport,
            'date'          => $date,
            'grand_total'   => $grandTotal,
            'total_billets' => $totalBillets,
            'total_colis'   => $totalColis,
            'total_ecarts'  => $totalEcarts,
        ]);
    }
}
