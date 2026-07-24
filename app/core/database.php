<?php
/**
* Created by PhpStorm.
* User: SNT
* Date: 21/11/2022
* Time: 12:24
*/
 //session_start();

class Database {
    /** @var PDO|null */
    private $database = null;

    public function connect() {
        try {
            // charset=utf8mb4 explicite : sans lui, PDO négocie le charset par défaut du
            // serveur MySQL (souvent latin1), ce qui corrompt les caractères accentués et
            // fait échouer silencieusement json_encode() (retourne false) sur ces données.
            $bdd = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8mb4', DBUSERNAME, DBPASSWORD);
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
