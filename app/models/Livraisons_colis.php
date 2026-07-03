<?php
class Livraisons_colis extends Model
{
    public function findByCode(string $code): ?array
    {
        $sql = "SELECT colis.*,
                       expediteurs.expediteur        AS expediteur,
                       expediteurs.numero_exp         AS numero_exp,
                       expediteurs.whatsapp_exp       AS whatsapp_exp,
                       expediteurs.email_exp          AS email_exp,
                       destinataires.destinataire     AS destinataire,
                       destinataires.numero_dest      AS numero_dest,
                       destinataires.whatsapp_dest    AS whatsapp_dest,
                       destinataires.email_dest       AS email_dest,
                       agence.localite                AS localite,
                       agence.numeroGare              AS numero_gare
                FROM colis
                INNER JOIN expediteurs   ON expediteurs.id_expediteur    = colis.id_expediteur
                INNER JOIN destinataires ON destinataires.id_destinataire = colis.id_destinataire
                INNER JOIN agence        ON agence.idAgence             = colis.id_agence
                WHERE colis.code_colis = :code";

        return $this->query($sql, [':code' => $code], true);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT colis.*,
                       expediteurs.expediteur      AS expediteur,
                       expediteurs.numero_exp      AS numero_exp,
                       expediteurs.whatsapp_exp    AS whatsapp_exp,
                       expediteurs.email_exp       AS email_exp,
                       destinataires.destinataire  AS destinataire,
                       destinataires.numero_dest   AS numero_dest,
                       destinataires.whatsapp_dest AS whatsapp_dest,
                       destinataires.email_dest    AS email_dest,
                       agence.localite             AS localite,
                       agence.numeroGare           AS numero_gare
                FROM colis
                INNER JOIN expediteurs   ON expediteurs.id_expediteur     = colis.id_expediteur
                INNER JOIN destinataires ON destinataires.id_destinataire = colis.id_destinataire
                INNER JOIN agence        ON agence.idAgence              = colis.id_agence
                WHERE colis.id_colis = :id";

        return $this->query($sql, [':id' => $id], true);
    }

    public function livrer(int $id_colis): bool
    {
        $sql = "UPDATE colis SET status = 'livre', date_livraison = NOW() WHERE id_colis = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id_colis, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


      /** Retourne les informations d’une compagnie par son id */
    public function infoCompagnie(int $id): ?array
    {
        $sql = "SELECT id_compagnie,
                       nom_compagnie  AS nom,
                       slogant,
                       logo     AS logo,
                       
                       libele
                FROM   compagnie
                WHERE  id_compagnie = :id";

        return $this->query($sql, [':id' => $id], true);   // true ⇒ row unique ou null
    }

}
