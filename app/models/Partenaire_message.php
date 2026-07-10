<?php

class Partenaire_message extends Model
{
    public function envoyer($id_partenaire, $auteur, $message)
    {
        $sql = "INSERT INTO partenaire_message (id_partenaire, auteur, message, date_envoi)
                VALUES (:id_partenaire, :auteur, :message, NOW())";

        return $this->insertion_update_simples($sql, [
            ':id_partenaire' => $id_partenaire,
            ':auteur' => $auteur,
            ':message' => $message
        ]);
    }

    public function getByPartenaire($id_partenaire)
    {
        $stmt = $this->connect()->prepare(
            "SELECT * FROM partenaire_message WHERE id_partenaire = :id ORDER BY date_envoi ASC"
        );
        $stmt->execute([':id' => $id_partenaire]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
