<?php

class Programme extends Model
    {
public function getByCompagnie($id_compagnie) {
    $stmt = $this->connect()->prepare("SELECT * FROM programmer WHERE id_compagnie = ?");
    $stmt->execute([$id_compagnie]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
public function findById($id)
{
    $sql = "SELECT * FROM programmer WHERE idProgrammer = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

    }
