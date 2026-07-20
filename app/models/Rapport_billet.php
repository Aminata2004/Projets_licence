<?php
class Rapport_billet extends Model
{
    // Compter par type (presentiel, en_ligne, repporte)
    public function getTotalParType($compagnieId, $type)
    {
        if ($type === 'repporte') {
            $sql = "SELECT COUNT(*) FROM billets 
                    WHERE date_repporte IS NOT NULL 
                      AND id_compagnie = :compagnie";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([':compagnie' => $compagnieId]);
        } else {
            $sql = "SELECT COUNT(*) FROM billets 
                    WHERE status_reservation = :type 
                      AND id_compagnie = :compagnie";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([
                ':type' => $type,
                ':compagnie' => $compagnieId
            ]);
        }
        return $stmt->fetchColumn();
    }

    // Compter par mois
    public function getParMois($compagnieId)
    {
        $sql = "SELECT 
                    DATE_FORMAT(
                        IF(date_repporte IS NOT NULL, date_repporte, date_reservation), 
                        '%Y-%m'
                    ) AS mois,
                    CASE 
                        WHEN date_repporte IS NOT NULL THEN 'repporte'
                        ELSE status_reservation
                    END AS type_reservation,
                    COUNT(*) AS total
                FROM billets
                WHERE id_compagnie = :compagnie
                GROUP BY mois, type_reservation
                ORDER BY mois ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':compagnie' => $compagnieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Totaux annuels par type (presentiel, en_ligne, repporte)
    public function getTotalParTypeAnnuel($compagnieId, $type, $annee)
    {
        if ($type === 'repporte') {
            $sql = "SELECT COUNT(*) FROM billets 
                WHERE date_repporte IS NOT NULL 
                  AND id_compagnie = :compagnie
                  AND YEAR(date_repporte) = :annee";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([
                ':compagnie' => $compagnieId,
                ':annee' => $annee
            ]);
        } else {
            $sql = "SELECT COUNT(*) FROM billets 
                WHERE status_reservation = :type 
                  AND id_compagnie = :compagnie
                  AND YEAR(date_reservation) = :annee";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([
                ':type' => $type,
                ':compagnie' => $compagnieId,
                ':annee' => $annee
            ]);
        }

        return $stmt->fetchColumn();
    }

    // Statistiques annuelles par type
    public function getParAnnee($compagnieId, $annee)
    {
        $sql = "SELECT 
                YEAR(IF(date_repporte IS NOT NULL, date_repporte, date_reservation)) AS annee,
                CASE 
                    WHEN date_repporte IS NOT NULL THEN 'repporte'
                    ELSE status_reservation
                END AS type_reservation,
                COUNT(*) AS total
            FROM billets
            WHERE id_compagnie = :compagnie
              AND YEAR(IF(date_repporte IS NOT NULL, date_repporte, date_reservation)) = :annee
            GROUP BY annee, type_reservation
            ORDER BY annee ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ':compagnie' => $compagnieId,
            ':annee' => $annee
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getSommeBilletsParLocaliteEtGare($compagnieId, $localite = null, $mois = null)
    {
        $params = [':compagnie' => $compagnieId];

        $sql = "SELECT b.departId AS localite,
                   b.num_gare,
                   b.status_reservation,
                   SUM(CAST(REPLACE(REPLACE(c.montant_payer, ' ', ''), 'FCFA', '') AS DECIMAL(12,2))) AS total
            FROM billets b
            INNER JOIN client c ON b.id_client = c.idClient
            WHERE b.id_compagnie = :compagnie
              AND b.status_reservation IN ('presentiel', 'en_ligne')
              AND (b.status_billets IS NULL OR b.status_billets != 'annule')";

        if ($mois !== null) {
            $sql .= " AND DATE_FORMAT(b.date_reservation, '%Y-%m') = :mois";
            $params[':mois'] = $mois;
        }

        if ($localite !== null) {
            $sql .= " AND b.departId = :localite";
            $params[':localite'] = $localite;
        }

        $sql .= " GROUP BY b.departId, b.num_gare, b.status_reservation
              ORDER BY b.departId ASC, b.num_gare ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getSommeBilletsParLocaliteEtGareAnnuel($compagnieId, $localite = null, $annee = null)
    {
        $params = [':compagnie' => $compagnieId];

        $sql = "SELECT b.departId AS localite,
                   b.num_gare,
                   b.status_reservation,
                   SUM(CAST(REPLACE(REPLACE(c.montant_payer, ' ', ''), 'FCFA', '') AS DECIMAL(12,2))) AS total
            FROM billets b
            INNER JOIN client c ON b.id_client = c.idClient
            WHERE b.id_compagnie = :compagnie
              AND b.status_reservation IN ('presentiel', 'en_ligne')
              AND (b.status_billets IS NULL OR b.status_billets != 'annule')";

        if ($annee !== null) {
            $sql .= " AND YEAR(b.date_reservation) = :annee";
            $params[':annee'] = $annee;
        }

        if ($localite !== null) {
            $sql .= " AND b.departId = :localite";
            $params[':localite'] = $localite;
        }

        $sql .= " GROUP BY b.departId, b.num_gare, b.status_reservation
              ORDER BY b.departId ASC, b.num_gare ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // Toutes les caisses pour admin
    // public function getAllCaisses($id_compagnie)
    // {
    //     $sql = "SELECT a.localite, a.numeroGare, c.montant_initial, c.montant_billets, c.montant_colis, c.date_enregistrement, c.reference_caise, c.status_caisse
    //             FROM caisse c
    //             JOIN agence a ON a.idAgence = c.id_agence
    //             WHERE c.id_compagnie = :id_compagnie
    //             ORDER BY a.localite, a.numeroGare";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([':id_compagnie' => $id_compagnie]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // Caisses pour admin_regionale (filtré par localité)
    // public function getCaissesByLocalite($id_compagnie, $localite)
    // {
    //     $sql = "SELECT a.localite, a.numeroGare, c.montant_initial, c.montant_billets, c.montant_colis, c.date_enregistrement, c.reference_caise, c.status_caisse
    //             FROM caisse c
    //             JOIN agence a ON a.idAgence = c.id_agence
    //             WHERE c.id_compagnie = :id_compagnie
    //               AND a.localite = :localite
    //             ORDER BY a.numeroGare";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([
    //         ':id_compagnie' => $id_compagnie,
    //         ':localite' => $localite
    //     ]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
}
