<?php
class Liste_entente extends Model
{
    public function liste_entente()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        // $liste = $this->FetchSelectWheres(
        //     '*',
        //     'billets INNER JOIN client ON billets.id_client = client.idClient',
        //     'billets.id_compagnie = :id_compagnie AND billets.status_reservation = :status AND billets.validation_billets = :validation',
        //     [
        //         'id_compagnie' => $id_compagnie,
        //         'status_reservation'       => 'en_ligne',
        //         'validation_billets'   => 'en_attente'
        //     ]
        // );
        // return $liste;
        $liste = $this->FetchSelectWheres(
            '*',
            'billets INNER JOIN client ON billets.id_client = client.idClient',
            'billets.id_compagnie = :id_compagnie AND billets.status_reservation = :status AND billets.validation_billets = :validation',
            [
                'id_compagnie' => $id_compagnie,
                'status'       => 'en_ligne',
                'validation'   => 'en_attente'
            ]
        );
        return $liste;
    }

    public function getTicketById($id)
    {
        $sql = "SELECT * FROM billets INNER JOIN client ON billets.id_client = client.idClient WHERE idBillets = :id LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    
}
