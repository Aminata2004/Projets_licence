<?php
class Mouvements_colis extends  Model
{
    public function FetchSelectColisEncours()
    {
        $droit = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $ville = $_SESSION['ville'] ?? null;
        $numero_gare = $_SESSION['numero_gare'] ?? null;

        $sql = "SELECT 
                colis.*, 
                a.localite AS destination, 
                expediteurs.expediteur, expediteurs.numero_exp, expediteurs.whatsapp_exp, expediteurs.email_exp, 
                destinataires.destinataire, destinataires.numero_dest, destinataires.whatsapp_dest, destinataires.email_dest 
            FROM colis
            JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
            JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
            JOIN agence a ON colis.id_agence = a.idAgence
            WHERE colis.id_compagnie = :id_compagnie 
              AND colis.status = :status";

        $params = [
            ':id_compagnie' => $id_compagnie,
            ':status' => 'en_cours'
        ];

        if ($droit === 'chef_d_escale') {
            // On filtre selon la localité de l'agence
            $sql .= " AND a.localite = :ville";
            $params[':ville'] = $ville;
        } elseif ($droit === 'Utilisateur') {
            // Filtre par localité et par gare
            $sql .= " AND a.localite = :ville AND colis.num_gare = :numero_gare";
            $params[':ville'] = $ville;
            $params[':numero_gare'] = $numero_gare;
        }

        $sql .= " ORDER BY colis.id_colis DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function FetchSelectColisRecu()
    {
        $droit = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $ville = $_SESSION['ville'] ?? null;
        $numero_gare = $_SESSION['numero_gare'] ?? null;

        $sql = "SELECT
                colis.*,
                a.localite AS destination,
                a.numeroGare AS numero_gare_retrait,
                expediteurs.expediteur, expediteurs.numero_exp, expediteurs.whatsapp_exp, expediteurs.email_exp,
                destinataires.destinataire, destinataires.numero_dest, destinataires.whatsapp_dest, destinataires.email_dest
            FROM colis
            JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
            JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
            JOIN agence a ON colis.id_agence = a.idAgence
            WHERE colis.id_compagnie = :id_compagnie
              AND colis.status = :status";

        $params = [
            ':id_compagnie' => $id_compagnie,
            ':status' => 'recu'
        ];

        if ($droit === 'chef_d_escale') {
            // On filtre selon la localité de l'agence
            $sql .= " AND a.localite = :ville";
            $params[':ville'] = $ville;
        } elseif ($droit === 'Utilisateur') {
            // Filtre par localité et par gare
            $sql .= " AND a.localite = :ville AND colis.num_gare = :numero_gare";
            $params[':ville'] = $ville;
            $params[':numero_gare'] = $numero_gare;
        }

        $sql .= " ORDER BY colis.id_colis DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function FetchSelectColisLivre()
    {
        $droit = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $ville = $_SESSION['ville'] ?? null;
        $numero_gare = $_SESSION['numero_gare'] ?? null;

        $sql = "SELECT 
                colis.*, 
                a.localite AS destination, 
                expediteurs.expediteur, expediteurs.numero_exp, expediteurs.whatsapp_exp, expediteurs.email_exp, 
                destinataires.destinataire, destinataires.numero_dest, destinataires.whatsapp_dest, destinataires.email_dest 
            FROM colis
            JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
            JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
            JOIN agence a ON colis.id_agence = a.idAgence
            WHERE colis.id_compagnie = :id_compagnie 
              AND colis.status = :status";

        $params = [
            ':id_compagnie' => $id_compagnie,
            ':status' => 'livre'
        ];

        if ($droit === 'chef_d_escale') {
            // On filtre selon la localité de l'agence
            $sql .= " AND a.localite = :ville";
            $params[':ville'] = $ville;
        } elseif ($droit === 'Utilisateur') {
            // Filtre par localité et par gare
            $sql .= " AND a.localite = :ville AND colis.num_gare = :numero_gare";
            $params[':ville'] = $ville;
            $params[':numero_gare'] = $numero_gare;
        }

        $sql .= " ORDER BY colis.id_colis DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Retourne les colis à réceptionner
     */
    public function getColisPourReception(array $ids): array
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT colis.*, expediteurs.*, destinataires.*, agence.*
        FROM colis
        INNER JOIN expediteurs   ON expediteurs.id_expediteur    = colis.id_expediteur
        INNER JOIN destinataires ON destinataires.id_destinataire = colis.id_destinataire
        INNER JOIN agence ON agence.idAgence = colis.id_agence
        WHERE colis.id_colis IN ($placeholders)";


        $stmt = $this->connect()->prepare($sql);   // $this->db vient du __construct
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marque un colis « reçu »
     */
    public function marquerRecu(int $id): void
    {
        $stmt = $this->connect()->prepare("UPDATE colis SET status = 'recu' WHERE id_colis = ?");
        $stmt->execute([$id]);
    }
}
