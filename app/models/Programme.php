<?php
class Programme extends Model
{
    // public function getByCompagnie($id_compagnie)
    // {
    //     $stmt = $this->connect()->prepare("SELECT * FROM programmer WHERE id_compagnie = ?");
    //     $stmt->execute([$id_compagnie]);
    //     return $stmt->fetchAll(PDO::FETCH_OBJ);
    // }
    

public function getByCompagnie($id_compagnie) {
    $sql = "SELECT p.*,
                   GROUP_CONCAT(CONCAT(e.escales, ' (', lt.prix_escale, ' FCFA)') ORDER BY e.id_escale SEPARATOR ' , ') AS escales_avec_frais
            FROM programmer p
            LEFT JOIN ligneTrajet lt ON lt.id_trajets = p.idProgrammer
            LEFT JOIN escale e ON e.id_escale = lt.id_escales
            WHERE p.id_compagnie = ?
            GROUP BY p.idProgrammer";
    
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id_compagnie]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}


    // public function findById($id)
    // {
    //     $sql = "SELECT * FROM programmer WHERE idProgrammer = ?";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_OBJ);
    // }

    public function findById($id)
{
    $sql = "SELECT p.*, 
                   GROUP_CONCAT(CONCAT(e.escales, ' (', lt.prix_escale, ' FCFA)') ORDER BY e.id_escale SEPARATOR ' - ') AS escales_avec_frais
            FROM programmer p
            LEFT JOIN ligneTrajet lt ON lt.id_trajets = p.idProgrammer
            LEFT JOIN escale e ON e.id_escale = lt.id_escales
            WHERE p.idProgrammer = ?
            GROUP BY p.idProgrammer";

    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_OBJ); // retourne un objet stdClass
}


    
}
