<?php
class Home extends Model
{
    /**
     * Récupère le nombre de billets réservés aujourd'hui
     * en fonction du rôle de l'utilisateur et de sa compagnie
     */



    public function getBilletsJournalier($user)
    {
        $today = date('Y-m-d');

        // Base : toujours filtrer par compagnie
        $sql = "SELECT 
                SUM(CASE WHEN status_reservation = 'presentiel' THEN 1 ELSE 0 END) AS total_presentiel,
                SUM(CASE WHEN status_reservation = 'en_ligne' AND status_reservation = 'valider' THEN 1 ELSE 0 END) AS total_en_ligne,
                SUM(CASE WHEN status_reservation = 'en_ligne' AND validation_billets = 'en_attente' THEN 1 ELSE 0 END) AS total_en_attente
            FROM billets
            WHERE date_reservation = :today 
              AND id_compagnie = :compagnie";

        $params = [
            ':today' => $today,
            ':compagnie' => $_SESSION['id_compagnie']
        ];

        // Si c'est Admin régional → filtre ville
        if ($_SESSION['droit'] === 'chef_d_escale') {
            $sql .= " AND departId = :ville";
            $params[':ville'] = $_SESSION['ville'];
        }

        // Si c'est un utilisateur simple → filtre utilisateur
        if ($_SESSION['droit'] === 'Utilisateur') {
            $sql .= " AND idUser = :idUser";
            $params[':idUser'] = $_SESSION['id_utilisateur'];
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

    public function getVoyagesProgrammes()
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

        // Si Admin régional OU Utilisateur → filtre aussi par ville
        if ($_SESSION['droit'] === 'chef_d_escale' || $_SESSION['droit'] === 'Utilisateur') {
            $sql .= " AND localite_user = :ville";
            $params[':ville'] = $_SESSION['ville'];
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int)$row['total'] : 0;
    }

    // public function getColisJmensuel($user)
    // {
    //     $today = date('Y-m-d');

    //     $sql = "SELECT status, COUNT(*) as total 
    //         FROM colis
    //         WHERE DATE(date_enregistrement) = :today
    //           AND provient_de = :ville
    //           AND id_compagnie = :compagnie
    //         GROUP BY status";

    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([
    //         ':today'     => $today,
    //         ':ville'     => $_SESSION['ville'],        // Ville de l’utilisateur connecté
    //         ':compagnie' => $_SESSION['id_compagnie']  // Compagnie
    //     ]);

    //     $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    //     // Normaliser les résultats pour éviter les index manquants
    //     return [
    //         'prise_en_charge' => $results['enregistre'] ?? 0,
    //         'en_cours'        => $results['en_cours'] ?? 0,
    //         'recu'            => $results['recu'] ?? 0,
    //         'livre'           => $results['livre'] ?? 0,
    //         'attente'         => $results[''] ?? 0
    //     ];
    // }

    // public function getColisEnCours($session)
    // {
    //     $role         = $_SESSION['droit']; // 'utilisateur', 'admin_regionale', 'admin'
    //     $ville        = $_SESSION['ville'] ?? null;
    //     $num_gare     = $_SESSION['numero_gare'] ?? null;
    //     $user_id      = $session['id_utilisateur'];
    //     $id_compagnie = $_SESSION['id_compagnie'] ?? null;

    //     // Base de la requête
    //     $sql = "SELECT COUNT(*) AS en_cour
    //         FROM colis
    //         INNER JOIN agence ON colis.id_agence = agence.idAgence
    //         WHERE colis.status = 'en_cours'";

    //     $params = [];

    //     if ($role === 'Utilisateur') {
    //         // Utilisateur : filtre ville + gare + utilisateur
    //         $sql .= " AND agence.localite = :ville AND colis.num_gare = :num_gare ";
    //         $params = [
    //             ':ville' => $ville,
    //             ':num_gare' => $num_gare


    //         ];
    //     } elseif ($role === 'chef_d_escale') {
    //         // Admin régionale : filtre seulement sur ville
    //         $sql .= " AND agence.localite = :ville";
    //         $params = [
    //             ':ville' => $ville
    //         ];
    //     } elseif ($role === 'Admin') {
    //         // Admin global : filtre seulement sur la compagnie
    //         $sql .= " AND colis.id_compagnie = :id_compagnie";
    //         $params = [
    //             ':id_compagnie' => $id_compagnie
    //         ];
    //     }

    //     $stmt = $this->connect()->prepare($sql);

    //     foreach ($params as $key => $val) {
    //         $stmt->bindValue($key, trim($val)); // trim pour éviter les espaces invisibles
    //     }

    //     $stmt->execute();

    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // public function getColislivre($session)
    // {
    //     $role         = $_SESSION['droit']; // 'utilisateur', 'admin_regionale', 'admin'
    //     $ville        = $_SESSION['ville'] ?? null;
    //     $num_gare     = $_SESSION['numero_gare'] ?? null;
    //     $user_id      = $session['id_utilisateur'];
    //     $id_compagnie = $_SESSION['id_compagnie'] ?? null;

    //     // Base de la requête
    //     $sql = "SELECT COUNT(*) AS livre
    //         FROM colis
    //         INNER JOIN agence ON colis.id_agence = agence.idAgence
    //         WHERE colis.status = 'livre'";

    //     $params = [];

    //     if ($role === 'Utilisateur') {
    //         // Utilisateur : filtre ville + gare + utilisateur
    //         $sql .= " AND agence.localite = :ville AND colis.num_gare = :num_gare ";
    //         $params = [
    //             ':ville' => $ville,
    //             ':num_gare' => $num_gare


    //         ];
    //     } elseif ($role === 'chef_d_escale') {
    //         // Admin régionale : filtre seulement sur ville
    //         $sql .= " AND agence.localite = :ville";
    //         $params = [
    //             ':ville' => $ville
    //         ];
    //     } elseif ($role === 'Admin') {
    //         // Admin global : filtre seulement sur la compagnie
    //         $sql .= " AND colis.id_compagnie = :id_compagnie";
    //         $params = [
    //             ':id_compagnie' => $id_compagnie
    //         ];
    //     }

    //     $stmt = $this->connect()->prepare($sql);

    //     foreach ($params as $key => $val) {
    //         $stmt->bindValue($key, trim($val)); // trim pour éviter les espaces invisibles
    //     }

    //     $stmt->execute();

    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }


public function getColisMensuel($session)
{
    $role         = $_SESSION['droit']; // 'Utilisateur', 'chef_d_escale', 'Admin'
    $ville        = $_SESSION['ville'] ?? null;
    $num_gare     = $_SESSION['numero_gare'] ?? null;
    $user_id      = $session['id_utilisateur'];
    $id_compagnie = $_SESSION['id_compagnie'] ?? null;

    // Début et fin du mois courant
    $debutMois = date('Y-m-01'); // premier jour du mois
    $finMois   = date('Y-m-t');  // dernier jour du mois

    // Base de la requête — préfixe 'colis.' pour éviter l'ambiguïté avec agence
    $sql = "SELECT colis.status, COUNT(*) AS total
            FROM colis
            INNER JOIN agence ON colis.id_agence = agence.idAgence
            WHERE DATE(colis.date_enregistrement) BETWEEN :debut AND :fin";

    $params = [
        ':debut' => $debutMois,
        ':fin'   => $finMois
    ];

    // Ajouter les filtres selon le rôle
    if ($role === 'Utilisateur') {
        // Pour tous les statuts sauf "enregistre", filtre sur gare et utilisateur
        $sql .= " AND (colis.status <> 'enregistre' AND agence.localite = :ville AND colis.num_gare = :num_gare)";
        $params[':ville'] = $ville;
        $params[':num_gare'] = $num_gare;

        // Pour le statut "enregistre", filtrer sur provient_de = ville
        $sql .= " OR (colis.status = 'enregistre' AND colis.provient_de = :ville_prov)";
        $params[':ville_prov'] = $ville;

    } elseif ($role === 'chef_d_escale') {
        $sql .= " AND (colis.status <> 'enregistre' AND agence.localite = :ville)";
        $params[':ville'] = $ville;
        $sql .= " OR (colis.status = 'enregistre' AND colis.provient_de = :ville_prov)";
        $params[':ville_prov'] = $ville;

    } elseif ($role === 'Admin') {
        $sql .= " AND colis.id_compagnie = :id_compagnie";
        $params[':id_compagnie'] = $id_compagnie;
    }

    // Grouper par statut (préfixé pour éviter l'ambiguïté)
    $sql .= " GROUP BY colis.status";

    $stmt = $this->connect()->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Normaliser tous les statuts pour éviter les index manquants
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

}
