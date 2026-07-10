<?php

class Partenaire extends Model
{
    public function getByEmail($email)
    {
        $stmt = $this->connect()->prepare("SELECT * FROM partenaire_compte WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getById($id)
    {
        $stmt = $this->connect()->prepare("SELECT * FROM partenaire_compte WHERE id_partenaire = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function creer($data)
    {
        if ($this->getByEmail($data['email'])) {
            return false;
        }

        $sql = "INSERT INTO partenaire_compte (nom_compagnie, email, mot_de_passe, telephone, date_creation)
                VALUES (:nom_compagnie, :email, :mot_de_passe, :telephone, NOW())";

        $ok = $this->insertion_update_simples($sql, [
            ':nom_compagnie' => $data['nom_compagnie'],
            ':email' => $data['email'],
            ':mot_de_passe' => $this->bcript_hash_password($data['mot_de_passe']),
            ':telephone' => $data['telephone'] ?? null
        ]);

        return $ok ? $this->getByEmail($data['email']) : false;
    }

    public function verifierConnexion($email, $motDePasse)
    {
        $partenaire = $this->getByEmail($email);
        if ($partenaire && $this->bcript_verify_password($motDePasse, $partenaire->mot_de_passe)) {
            return $partenaire;
        }
        return false;
    }

    // Liste des comptes partenaires avec un aperçu de la conversation (pour l'admin).
    public function getTousAvecApercu()
    {
        $sql = "SELECT pc.*,
                       (SELECT message FROM partenaire_message pm WHERE pm.id_partenaire = pc.id_partenaire ORDER BY pm.date_envoi DESC LIMIT 1) AS dernier_message,
                       (SELECT date_envoi FROM partenaire_message pm WHERE pm.id_partenaire = pc.id_partenaire ORDER BY pm.date_envoi DESC LIMIT 1) AS date_dernier_message,
                       (SELECT auteur FROM partenaire_message pm WHERE pm.id_partenaire = pc.id_partenaire ORDER BY pm.date_envoi DESC LIMIT 1) AS dernier_auteur
                FROM partenaire_compte pc
                ORDER BY date_dernier_message DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Nombre de conversations dont le dernier message vient du partenaire (en attente d'une réponse admin).
    public function countEnAttenteReponse()
    {
        $sql = "SELECT COUNT(*) FROM partenaire_compte pc
                WHERE (
                    SELECT auteur FROM partenaire_message pm
                    WHERE pm.id_partenaire = pc.id_partenaire
                    ORDER BY pm.date_envoi DESC LIMIT 1
                ) = 'partenaire'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
