<?php
class Home extends Model
{
    /**
     * ─────────────────────────────────────────────────────────
     *  MÉTHODES ANALYTIQUES AVANCÉES
     * ─────────────────────────────────────────────────────────
     */

    /**
     * Tendance billets sur les 7 derniers jours (présentiel + en ligne).
     * Retourne un tableau indexé ['date', 'presentiel', 'en_ligne'].
     */
    public function getTendanceBillets7Jours()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return [];

        $sql = "SELECT
                    date_reservation                                                              AS jour,
                    SUM(CASE WHEN status_reservation = 'presentiel' THEN 1 ELSE 0 END)          AS presentiel,
                    SUM(CASE WHEN status_reservation = 'en_ligne' AND validation_billets = 'valider' THEN 1 ELSE 0 END) AS en_ligne
                FROM billets
                WHERE id_compagnie = :id_compagnie
                  AND date_reservation >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY date_reservation
                ORDER BY date_reservation ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $id_compagnie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Revenus mensuels des billets (12 derniers mois) pour le graphique en barres.
     * Retourne ['mois', 'total_fcfa'].
     */
    public function getRevenusMensuels12Mois()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return [];

        $sql = "SELECT
                    DATE_FORMAT(b.date_reservation, '%Y-%m')               AS mois,
                    SUM(CAST(REPLACE(REPLACE(c.montant_payer,' ',''),'FCFA','') AS UNSIGNED)) AS total_fcfa
                FROM billets b
                INNER JOIN client c ON b.id_client = c.idClient
                WHERE b.id_compagnie = :id_compagnie
                  AND b.date_reservation >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
                  AND b.status_reservation IN ('presentiel','en_ligne')
                GROUP BY mois
                ORDER BY mois ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $id_compagnie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Top 5 trajets par nombre de billets vendus ce mois.
     */
    public function getTopTrajets()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return [];

        $sql = "SELECT
                    CONCAT(departId, ' → ', destinationId) AS trajet,
                    COUNT(*) AS total_billets,
                    SUM(nombrePassages) AS total_passagers
                FROM billets
                WHERE id_compagnie = :id_compagnie
                  AND date_reservation BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND LAST_DAY(CURDATE())
                GROUP BY departId, destinationId
                ORDER BY total_billets DESC
                LIMIT 5";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $id_compagnie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Chiffres globaux de performance pour la compagnie : total billets, total colis, cars actifs, utilisateurs.
     */
    public function getPerformanceGlobale()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return [];

        $pdo = $this->connect();

        $totalBillets = (int)$pdo->prepare("SELECT COUNT(*) FROM billets WHERE id_compagnie = :c")
            ->execute([':c' => $id_compagnie]) ? $pdo->query("SELECT COUNT(*) FROM billets WHERE id_compagnie = $id_compagnie")->fetchColumn() : 0;

        // On reconstruit proprement
        $s = $pdo->prepare("SELECT COUNT(*) FROM billets WHERE id_compagnie = :c");
        $s->execute([':c' => $id_compagnie]);
        $totalBillets = (int)$s->fetchColumn();

        $s = $pdo->prepare("SELECT COUNT(*) FROM colis WHERE id_compagnie = :c");
        $s->execute([':c' => $id_compagnie]);
        $totalColis = (int)$s->fetchColumn();

        $s = $pdo->prepare("SELECT COUNT(*) FROM car WHERE id_compagnie = :c");
        $s->execute([':c' => $id_compagnie]);
        $totalCars = (int)$s->fetchColumn();

        $s = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE id_compagnie = :c AND status = 1");
        $s->execute([':c' => $id_compagnie]);
        $totalUsers = (int)$s->fetchColumn();

        $s = $pdo->prepare("SELECT COUNT(*) FROM agence WHERE id_compagnie = :c");
        $s->execute([':c' => $id_compagnie]);
        $totalGares = (int)$s->fetchColumn();

        // Revenus du mois en cours
        $s = $pdo->prepare("SELECT SUM(CAST(REPLACE(REPLACE(c.montant_payer,' ',''),'FCFA','') AS UNSIGNED))
                             FROM billets b INNER JOIN client c ON b.id_client = c.idClient
                             WHERE b.id_compagnie = :c AND b.status_reservation IN ('presentiel','en_ligne')
                               AND DATE_FORMAT(b.date_reservation,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')");
        $s->execute([':c' => $id_compagnie]);
        $revenusMois = (int)($s->fetchColumn() ?? 0);

        return compact('totalBillets', 'totalColis', 'totalCars', 'totalUsers', 'totalGares', 'revenusMois');
    }

    /**
     * Taux de remplissage moyen des cars (billets vendus / capacité totale) pour le mois.
     */
    public function getTauxRemplissage()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return 0;

        $sql = "SELECT
                    SUM(b.nombrePassages) AS passagers_mois,
                    SUM(c.nbr_place) AS capacite_totale
                FROM billets b
                INNER JOIN programmation_voyage pv
                    ON pv.id_horaire = b.Heur_departs
                   AND pv.id_compagnie = b.id_compagnie
                   AND pv.date_enregistre = b.jourVoyage
                INNER JOIN car c ON c.numero_car = pv.id_car_programmer
                WHERE b.id_compagnie = :id_compagnie
                  AND DATE_FORMAT(b.date_reservation,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $id_compagnie]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || (int)$row['capacite_totale'] === 0) return 0;
        return round(($row['passagers_mois'] / $row['capacite_totale']) * 100, 1);
    }

    /**
     * Évolution colis par statut sur les 6 derniers mois.
     */
    public function getEvolutionColis6Mois()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        if (!$id_compagnie) return [];

        $sql = "SELECT
                    DATE_FORMAT(date_enregistrement,'%Y-%m') AS mois,
                    status,
                    COUNT(*) AS total
                FROM colis
                WHERE id_compagnie = :id_compagnie
                  AND date_enregistrement >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH),'%Y-%m-01')
                GROUP BY mois, status
                ORDER BY mois ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $id_compagnie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ─────────────────────────────────────────────────────────
     *  MÉTHODES EXISTANTES (inchangées)
     * ─────────────────────────────────────────────────────────
     */

    /**
     * Récupère le nombre de billets réservés aujourd'hui,
     * scopé selon le rôle (compagnie / gare / utilisateur).
     * $gareVille permet à un Admin de filtrer sur une gare précise (localite).
     */
    public function getBilletsJournalier($gareVille = null)
    {
        $today = date('Y-m-d');

        $sql = "SELECT
                SUM(CASE WHEN status_reservation = 'presentiel' THEN 1 ELSE 0 END) AS total_presentiel,
                SUM(CASE WHEN status_reservation = 'en_ligne' AND validation_billets = 'valider' THEN 1 ELSE 0 END) AS total_en_ligne,
                SUM(CASE WHEN status_reservation = 'en_ligne' AND validation_billets = 'en_attente' THEN 1 ELSE 0 END) AS total_en_attente
            FROM billets
            WHERE date_reservation = :today
              AND id_compagnie = :compagnie";

        $params = [
            ':today' => $today,
            ':compagnie' => $_SESSION['id_compagnie']
        ];

        if ($_SESSION['droit'] === 'chef_d_escale') {
            $sql .= " AND departId = :ville";
            $params[':ville'] = $_SESSION['ville'];
        } elseif ($_SESSION['droit'] === 'Utilisateur') {
            $sql .= " AND idUser = :idUser";
            $params[':idUser'] = $_SESSION['id_utilisateur'];
        } elseif ($_SESSION['droit'] === 'Admin' && $gareVille) {
            $sql .= " AND departId = :ville";
            $params[':ville'] = $gareVille;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'presentiel' => $row ? (int)$row['total_presentiel'] : 0,
            'en_ligne'   => $row ? (int)$row['total_en_ligne'] : 0,
            'en_attente' => $row ? (int)$row['total_en_attente'] : 0
        ];
    }

    public function getVoyagesProgrammes($gareVille = null)
    {
        $today = date('Y-m-d');

        $sql = "SELECT COUNT(*) AS total
            FROM programmation_voyage
            WHERE date_enregistre = :today
              AND id_compagnie = :compagnie";

        $params = [
            ':today' => $today,
            ':compagnie' => $_SESSION['id_compagnie']
        ];

        if ($_SESSION['droit'] === 'chef_d_escale' || $_SESSION['droit'] === 'Utilisateur') {
            $sql .= " AND localite_user = :ville";
            $params[':ville'] = $_SESSION['ville'];
        } elseif ($_SESSION['droit'] === 'Admin' && $gareVille) {
            $sql .= " AND localite_user = :ville";
            $params[':ville'] = $gareVille;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int)$row['total'] : 0;
    }

    /**
     * Statistiques colis du mois en cours, scopées par compagnie systématiquement
     * (avant, le filtre compagnie manquait pour chef_d_escale/Utilisateur : fuite
     * possible entre compagnies partageant une même ville).
     */
    public function getColisMensuel($gareVille = null)
    {
        $role     = $_SESSION['droit'];
        $ville    = $_SESSION['ville'] ?? null;
        $num_gare = $_SESSION['numero_gare'] ?? null;

        $debutMois = date('Y-m-01');
        $finMois   = date('Y-m-t');

        $sql = "SELECT colis.status, COUNT(*) AS total
                FROM colis
                INNER JOIN agence ON colis.id_agence = agence.idAgence
                WHERE colis.id_compagnie = :id_compagnie
                  AND DATE(colis.date_enregistrement) BETWEEN :debut AND :fin";

        $params = [
            ':id_compagnie' => $_SESSION['id_compagnie'],
            ':debut' => $debutMois,
            ':fin'   => $finMois
        ];

        if ($role === 'Utilisateur') {
            $sql .= " AND ((colis.status <> 'enregistre' AND agence.localite = :ville AND colis.num_gare = :num_gare)
                        OR (colis.status = 'enregistre' AND colis.provient_de = :ville_prov))";
            $params[':ville'] = $ville;
            $params[':num_gare'] = $num_gare;
            $params[':ville_prov'] = $ville;
        } elseif ($role === 'chef_d_escale') {
            $sql .= " AND ((colis.status <> 'enregistre' AND agence.localite = :ville)
                        OR (colis.status = 'enregistre' AND colis.provient_de = :ville_prov))";
            $params[':ville'] = $ville;
            $params[':ville_prov'] = $ville;
        } elseif ($role === 'Admin' && $gareVille) {
            $sql .= " AND ((colis.status <> 'enregistre' AND agence.localite = :ville)
                        OR (colis.status = 'enregistre' AND colis.provient_de = :ville_prov))";
            $params[':ville'] = $gareVille;
            $params[':ville_prov'] = $gareVille;
        }

        $sql .= " GROUP BY colis.status";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'prise_en_charge' => $results['enregistre'] ?? 0,
            'en_cours'        => $results['en_cours'] ?? 0,
            'recu'            => $results['recu'] ?? 0,
            'livre'           => $results['livre'] ?? 0,
            'attente'         => $results['attente'] ?? 0
        ];
    }

    public function getTopGares()
    {
        if ($_SESSION['droit'] !== 'Admin') {
            return [];
        }

        $id_compagnie = $_SESSION['id_compagnie'];
        $debutMois = date('Y-m-01');
        $finMois   = date('Y-m-t');

        $sql = "SELECT departId as gare, COUNT(*) as total_billets, SUM(nombrePassages) as total_passagers
                FROM billets
                WHERE id_compagnie = :compagnie
                  AND date_reservation BETWEEN :debut AND :fin
                GROUP BY departId
                ORDER BY total_billets DESC
                LIMIT 5";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ':compagnie' => $id_compagnie,
            ':debut'     => $debutMois,
            ':fin'       => $finMois
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Liste des gares de la compagnie de l'Admin connecté, pour le filtre du dashboard.
     */
    public function getGaresCompagnie()
    {
        $sql = "SELECT idAgence, localite, numeroGare FROM agence WHERE id_compagnie = :id_compagnie ORDER BY localite";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_compagnie' => $_SESSION['id_compagnie']]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * État de la caisse active du chef d'escale connecté (pour l'aperçu sur l'accueil).
     * Retourne null si aucune caisse n'est actuellement ouverte pour sa gare.
     */
    public function getCaisseGare()
    {
        $sql = "SELECT c.* FROM caisse c
                WHERE c.id_agence = :id_agence AND c.status_caisse = 1
                LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_agence' => $_SESSION['id_agence']]);
        $caisse = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$caisse) {
            return null;
        }

        $caisse->solde = ($caisse->montant_billets ?? 0) + ($caisse->montant_colis ?? 0)
            - ($caisse->montant_rembourse ?? 0) - ($caisse->montant_depense ?? 0);

        return $caisse;
    }

    /**
     * Dernières actions (billets et/ou colis) dans le périmètre de l'utilisateur connecté.
     * Remplace les "activités récentes" auparavant codées en dur dans la vue.
     */
    public function getActiviteRecente($gareVille = null, $limit = 6)
    {
        $role         = $_SESSION['droit'];
        $id_compagnie = $_SESSION['id_compagnie'];
        $ville        = $_SESSION['ville'] ?? null;
        $user_id      = $_SESSION['id_utilisateur'];
        $profile      = $_SESSION['profile'] ?? null;

        $activites = [];
        $pdo = $this->connect();

        $inclureBillets = $role !== 'Utilisateur' || $profile === 'billet';
        $inclureColis   = $role !== 'Utilisateur' || $profile === 'colis';

        if ($inclureBillets) {
            $sql = "SELECT numeroBillets, departId, destinationId, date_reservation
                    FROM billets
                    WHERE id_compagnie = :id_compagnie";
            $params = [':id_compagnie' => $id_compagnie];

            if ($role === 'chef_d_escale') {
                $sql .= " AND departId = :ville";
                $params[':ville'] = $ville;
            } elseif ($role === 'Utilisateur') {
                $sql .= " AND idUser = :idUser";
                $params[':idUser'] = $user_id;
            } elseif ($role === 'Admin' && $gareVille) {
                $sql .= " AND departId = :ville";
                $params[':ville'] = $gareVille;
            }

            $sql .= " ORDER BY date_reservation DESC, idBillets DESC LIMIT 6";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $activites[] = [
                    'type'   => 'billet',
                    'titre'  => 'Billet vendu',
                    'detail' => 'Billet #' . $row['numeroBillets'] . ' — ' . $row['departId'] . ' → ' . $row['destinationId'],
                    'date'   => $row['date_reservation']
                ];
            }
        }

        if ($inclureColis) {
            $sql = "SELECT colis.code_colis, colis.status, colis.date_enregistrement, agence.localite
                    FROM colis
                    INNER JOIN agence ON colis.id_agence = agence.idAgence
                    WHERE colis.id_compagnie = :id_compagnie";
            $params = [':id_compagnie' => $id_compagnie];

            if ($role === 'chef_d_escale') {
                $sql .= " AND agence.localite = :ville";
                $params[':ville'] = $ville;
            } elseif ($role === 'Utilisateur') {
                $sql .= " AND colis.id_utilisateur = :idUser";
                $params[':idUser'] = $user_id;
            } elseif ($role === 'Admin' && $gareVille) {
                $sql .= " AND agence.localite = :ville";
                $params[':ville'] = $gareVille;
            }

            $sql .= " ORDER BY colis.date_enregistrement DESC, colis.id_colis DESC LIMIT 6";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $activites[] = [
                    'type'   => 'colis',
                    'titre'  => 'Colis ' . str_replace('_', ' ', $row['status']),
                    'detail' => 'Colis #' . $row['code_colis'] . ' — ' . $row['localite'],
                    'date'   => $row['date_enregistrement']
                ];
            }
        }

        usort($activites, function ($a, $b) {
            return strcmp($b['date'] ?? '', $a['date'] ?? '');
        });

        return array_slice($activites, 0, $limit);
    }

    /**
     * Vue "plateforme" pour le super_admin : indicateurs toutes compagnies confondues.
     */
    public function getPlatformStats()
    {
        $pdo = $this->connect();
        $today = date('Y-m-d');

        $totalCompagnies = (int)$pdo->query("SELECT COUNT(*) FROM compagnie")->fetchColumn();
        $totalGares = (int)$pdo->query("SELECT COUNT(*) FROM agence")->fetchColumn();
        $totalUtilisateurs = (int)$pdo->query("SELECT COUNT(*) FROM utilisateur WHERE status = 1")->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM billets WHERE date_reservation = :today");
        $stmt->execute([':today' => $today]);
        $totalBilletsJour = (int)$stmt->fetchColumn();

        return [
            'totalCompagnies'   => $totalCompagnies,
            'totalGares'        => $totalGares,
            'totalUtilisateurs' => $totalUtilisateurs,
            'totalBilletsJour'  => $totalBilletsJour
        ];
    }

    /**
     * Répartition de l'activité du jour/mois par compagnie, pour le super_admin.
     */
    public function getCompagniesOverview()
    {
        $sql = "SELECT c.id_compagnie, c.nom_compagnie,
                    (SELECT COUNT(*) FROM agence a WHERE a.id_compagnie = c.id_compagnie) AS nb_gares,
                    (SELECT COUNT(*) FROM utilisateur u WHERE u.id_compagnie = c.id_compagnie AND u.status = 1) AS nb_utilisateurs,
                    (SELECT COUNT(*) FROM billets b WHERE b.id_compagnie = c.id_compagnie AND b.date_reservation = CURDATE()) AS billets_jour,
                    (SELECT COUNT(*) FROM colis co WHERE co.id_compagnie = c.id_compagnie
                        AND DATE(co.date_enregistrement) BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())) AS colis_mois
                FROM compagnie c
                ORDER BY c.nom_compagnie";

        $stmt = $this->connect()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
