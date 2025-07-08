<?php
/**
* Created by PhpStorm.
* User: SNT
* Date: 21/11/2022
* Time: 12:24
*/
 //session_start();

class Database {
    private ?PDO $database = null;

    public function connect() {
        try {
            $bdd = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSERNAME, DBPASSWORD);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Optionnel mais recommandé
            return $bdd;
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function bdd() {
        if ($this->database === null) {
            $this->database = $this->connect();
        }
        return $this->database;
    }
}
