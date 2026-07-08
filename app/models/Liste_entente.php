<?php
class Liste_entente extends Model
{
    public function liste_entente()
    {
        $where = 'billets.id_compagnie = :id_compagnie AND billets.status_reservation = :status AND billets.validation_billets = :validation';
        $params = [
            'id_compagnie' => $_SESSION['id_compagnie'],
            'status'       => 'en_ligne',
            'validation'   => 'en_attente'
        ];

        // La réservation appartient à la gare de départ : seule cette gare doit la voir dans sa liste d'attente.
        if (in_array($_SESSION['droit'] ?? '', ['chef_d_escale', 'Utilisateur'])) {
            $where .= ' AND billets.departId = :ville AND billets.num_gare = :numero_gare';
            $params['ville'] = $_SESSION['ville'];
            $params['numero_gare'] = $_SESSION['numero_gare'];
        }

        $liste = $this->FetchSelectWheres(
            '*',
            'billets INNER JOIN client ON billets.id_client = client.idClient',
            $where,
            $params
        );
        return $liste;
    }

    public function getTicketById($id)
    {
        $sql = "SELECT * FROM billets INNER JOIN client ON billets.id_client = client.idClient WHERE idBillets = :id AND billets.id_compagnie = :id_compagnie LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_compagnie', $_SESSION['id_compagnie'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    
}
