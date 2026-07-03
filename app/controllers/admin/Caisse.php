<?php
class Caisse extends Controller
{
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
            $id = (int)$_POST['id_caisse'];
            $status_caisse = (int)$_POST['newStatut'];

            // 🔒 Un chef d'escale ne peut clôturer qu'une caisse de sa propre agence,
            // même si la requête est trafiquée pour cibler l'id_caisse d'une autre ville.
            $autorise = true;
            if (($_SESSION['droit'] ?? null) === 'chef_d_escale') {
                $caisseCible = $liste_gare->FetchSelectWhere(
                    "c.id_caisse",
                    "caisse c INNER JOIN agence a ON c.id_agence = a.idAgence",
                    "c.id_caisse = :id_caisse AND a.id_compagnie = :id_compagnie AND a.localite = :ville",
                    [":id_caisse" => $id, ":id_compagnie" => $id_compagnie, ":ville" => $_SESSION['ville']]
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
            // Un chef d'escale ne doit voir/choisir que sa propre agence, pas celles des autres
            $listes = $liste_gare->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie AND localite = :ville',
                ['id_compagnie' => $id_compagnie, 'ville' => $_SESSION['ville']]
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

            $liste_gare->saveCaisse();
        }
        $this->view('admin/add_caisse', ['listes' => $listes]);
    }



    

    public function getSommeBillets($pdo, $compagnieId, $ville, $periode = 'jour')
    {
        $sql = "
    SELECT SUM(CAST(REGEXP_REPLACE(c.montant_payer, '[[:space:]]|FCFA', '') AS UNSIGNED)) as total
    FROM billets b
    INNER JOIN client c ON b.id_client = c.idClient
    WHERE b.id_compagnie = :compagnie
      AND b.validation_billets = 'valider'
      AND b.departId = :ville
";


        if ($periode === 'jour') {
            $sql .= " AND b.date_reservation = CURDATE()";
        } elseif ($periode === 'mois') {
            $sql .= " AND MONTH(b.date_reservation) = MONTH(CURDATE())
                  AND YEAR(b.date_reservation) = YEAR(CURDATE())";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'compagnie' => $compagnieId,
            'ville'     => $ville
        ]);

        return $stmt->fetchColumn() ?: 0;
    }


   public function getSommeColis($pdo, $compagnieId, $ville, $periode = 'jour')
{
    $sql = "
        SELECT SUM(fraix_transaction) as total
        FROM colis
        WHERE id_compagnie = :compagnie
          AND provient_de = :ville
    ";

    if ($periode === 'jour') {
        $sql .= " AND date_enregistrement = CURDATE()";
    } elseif ($periode === 'mois') {
        $sql .= " AND MONTH(date_enregistrement) = MONTH(CURDATE())
                  AND YEAR(date_enregistrement) = YEAR(CURDATE())";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'compagnie' => $compagnieId,
        'ville'     => $ville
    ]);

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

        // Si c’est un Admin régional → filtre par localité
        if ($role === 'chef_d_escale') {
            $condition .= ' AND a.localite = :ville';
            $params['ville'] = $ville;
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

            $caisse->total_jour = $this->getSommeBillets($pdo, $id_compagnie, $caisse->localite, 'jour');
            $caisse->total_mois = $this->getSommeBillets($pdo, $id_compagnie, $caisse->localite, 'mois');
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

        // Si c’est un Admin régional → filtre par localité
        if ($role === 'chef_d_escale') {
            $condition .= ' AND a.localite = :ville';
            $params['ville'] = $ville;
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

            $caisse->total_jour = $this->getSommeColis($pdo, $id_compagnie, $caisse->localite, 'jour');
            $caisse->total_mois = $this->getSommeColis($pdo, $id_compagnie, $caisse->localite, 'mois');
        }


        $this->view('admin/bilant_caisse_colis', ['liste_caisse' => $liste_caisse]);
    }
}
