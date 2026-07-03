<?php
class EspaceClient extends Model
{
    public function login($numeroBillets, $numeroClient)
    {
        $sql = "SELECT c.*, b.idBillets, b.numeroBillets
                FROM client c
                INNER JOIN billets b ON b.id_client = c.idClient
                WHERE b.numeroBillets = :num AND c.numeroClient = :tel
                LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':num' => $numeroBillets, ':tel' => $numeroClient]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getMesCommandes($idClient)
    {
        $sql = "SELECT b.*, c.Client, c.numeroClient, c.emailClient, c.montant_payer, c.numeroPaiement
                FROM billets b
                INNER JOIN client c ON b.id_client = c.idClient
                WHERE b.id_client = :id
                ORDER BY b.date_reservation DESC, b.idBillets DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id' => $idClient]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCommandeDetail($idBillets, $idClient)
    {
        $sql = "SELECT b.*, c.Client, c.numeroClient, c.emailClient, c.montant_payer, c.numeroPaiement
                FROM billets b
                INNER JOIN client c ON b.id_client = c.idClient
                WHERE b.idBillets = :id AND b.id_client = :client
                LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id' => $idBillets, ':client' => $idClient]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function validerCommande($idBillets, $idClient)
    {
        $sql = "UPDATE billets SET validation_billets = 'valider'
                WHERE idBillets = :id AND id_client = :client AND validation_billets = 'en_attente'";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([':id' => $idBillets, ':client' => $idClient]);
    }

    public function getEpargne($idClient)
    {
        $sql = "SELECT * FROM epargne WHERE id_client = :id ORDER BY date_operation DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id' => $idClient]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSoldeEpargne($idClient)
    {
        $sql = "SELECT
                    SUM(CASE WHEN type_operation='depot' THEN montant ELSE 0 END) AS total_depot,
                    SUM(CASE WHEN type_operation='retrait' THEN montant ELSE 0 END) AS total_retrait
                FROM epargne WHERE id_client = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id' => $idClient]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getMesPaiements($idClient)
    {
        $sql = "SELECT c.idClient, c.Client, c.numeroClient, c.emailClient,
                       c.montant_payer, c.numeroPaiement, c.date_enregistrement,
                       b.numeroBillets, b.destinationId, b.departId,
                       b.jourVoyage, b.nombrePassages, b.validation_billets, b.status_reservation
                FROM client c
                INNER JOIN billets b ON b.id_client = c.idClient
                WHERE c.idClient = :id
                ORDER BY c.date_enregistrement DESC, b.idBillets DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id' => $idClient]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
