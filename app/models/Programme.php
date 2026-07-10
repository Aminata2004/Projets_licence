<?php
class Programme extends Model
{
    // public function getByCompagnie($id_compagnie)
    // {
    //     $stmt = $this->connect()->prepare("SELECT * FROM programmer WHERE id_compagnie = ?");
    //     $stmt->execute([$id_compagnie]);
    //     return $stmt->fetchAll(PDO::FETCH_OBJ);
    // }


    public function getByCompagnie($id_compagnie)
    {
        // $sql = "SELECT p.*,
        //                GROUP_CONCAT(CONCAT(e.escales, ' (', lt.prix_escale, ' FCFA)') ORDER BY e.id_escale SEPARATOR ' , ') AS escales_avec_frais
        //         FROM programmer p
        //         LEFT JOIN ligneTrajet lt ON lt.id_trajets = p.idProgrammer
        //         LEFT JOIN escale e ON e.id_escale = lt.id_escales
        //         WHERE p.id_compagnie = ?
        //         GROUP BY p.idProgrammer";
        $sql = "SELECT p.*,
               a1.localite AS departLocalite,
               a1.numeroGare AS numeroGare1,
               a2.numeroGare AS numeroGare2,
               a2.localite AS destinationLocalite,
               GROUP_CONCAT(CONCAT(e.escales, ' (', lt.prix_escale, ' FCFA)') 
                            ORDER BY e.id_escale SEPARATOR ', ') AS escales_avec_frais
        FROM programmer p
        LEFT JOIN ligneTrajet lt ON lt.id_trajets = p.idProgrammer AND lt.type_trajet = 'programmer'
        LEFT JOIN escale e ON e.id_escale = lt.id_escales
        LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
        LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
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

    // Villes réellement utilisées comme départ ou destination dans au moins un trajet
    // programmé : sert à peupler les select "Départ"/"Destination" du formulaire de
    // recherche public avec des villes qui donneront vraiment des résultats.
    public function getVillesDisponibles()
    {
        $sql = "SELECT DISTINCT a.localite
                FROM agence a
                WHERE a.idAgence IN (SELECT idDepart FROM programmer)
                   OR a.idAgence IN (SELECT idDestination FROM programmer)
                ORDER BY a.localite";
        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    // Recherche de trajets programmés, tous compagnies confondues, filtrée par
    // ville de départ / destination / compagnie (chaque filtre est optionnel).
    public function rechercher($depart, $destination, $id_compagnie = '')
    {
        $sql = "SELECT p.*,
                       a1.localite AS departLocalite,
                       a2.localite AS destinationLocalite,
                       co.id_compagnie AS compagnieId,
                       co.nom_compagnie,
                       co.logo
                FROM programmer p
                INNER JOIN agence a1 ON p.idDepart = a1.idAgence
                INNER JOIN agence a2 ON p.idDestination = a2.idAgence
                INNER JOIN compagnie co ON p.id_compagnie = co.id_compagnie
                WHERE 1=1";
        $params = [];

        if ($depart !== '') {
            $sql .= " AND a1.localite = :depart";
            $params[':depart'] = $depart;
        }
        if ($destination !== '') {
            $sql .= " AND a2.localite = :destination";
            $params[':destination'] = $destination;
        }
        if ($id_compagnie !== '') {
            $sql .= " AND p.id_compagnie = :id_compagnie";
            $params[':id_compagnie'] = $id_compagnie;
        }

        $sql .= " ORDER BY p.prix ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function findById($id)
    {
        $sql = "SELECT p.*, 
      a1.localite AS departLocalite,
               a1.numeroGare AS numeroGare1,
               a2.numeroGare AS numeroGare2,
               a2.localite AS destinationLocalite,
               a1.code As codeDepart,
                   GROUP_CONCAT(CONCAT(e.escales, ' (', lt.prix_escale, ' FCFA)') ORDER BY e.id_escale SEPARATOR ' - ') AS escales_avec_frais
            FROM programmer p
            LEFT JOIN ligneTrajet lt ON lt.id_trajets = p.idProgrammer AND lt.type_trajet = 'programmer'
            LEFT JOIN escale e ON e.id_escale = lt.id_escales
              LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
        LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
            WHERE p.idProgrammer = ?
            GROUP BY p.idProgrammer";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ); // retourne un objet stdClass
    }
}
