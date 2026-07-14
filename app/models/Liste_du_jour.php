 <?php
  class Liste_du_jour extends Model
  {

    public function getDestinations($idDepart, $idCompagnie)
    {
      // programmer.idDepart/idDestination sont des idAgence : on les relie à la table agence
      // pour comparer sur la localité (billets.destinationId est stocké comme un nom de localité).
      $sql = "SELECT DISTINCT a2.localite AS idDestination
            FROM programmer p
            INNER JOIN agence a1 ON p.idDepart = a1.idAgence
            INNER JOIN agence a2 ON p.idDestination = a2.idAgence
            WHERE a1.localite = :idDepart
              AND p.id_compagnie = :idCompagnie
            ORDER BY a2.localite";
      return $this->fetchAll($sql, [
        ':idDepart' => $idDepart,
        ':idCompagnie' => $idCompagnie
      ]);
    }

    public function listeBillets($villeDepart)
    {
      $id_compagnie = $_SESSION['id_compagnie'];
      $liste = $this->FetchSelectWheres(
        '*',
        'billets inner join client on billets.id_client = client.idClient',
        'billets.id_compagnie = :id_compagnie AND billets.departId = :depart AND billets.validation_billets = :validation ORDER BY billets.idBillets DESC LIMIT 10',
        [
          'id_compagnie' => $id_compagnie,
          'depart'       => $villeDepart,
          'validation'   => 'valider'
        ]
      );
      return $liste;
    }

    public function getBilletById($idBillets)
    {
      $sql = "SELECT * FROM billets inner join client on billets.id_client = client.idClient 
        inner join utilisateur on utilisateur.idUser = billets.idUser
        WHERE idBillets = :id ";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([':id' => $idBillets]);
      return $stmt->fetch(PDO::FETCH_OBJ);
    }
    // public function getBilletById($idBillets)
    // {
    //   $sql = "SELECT 
    //             b.idBillets,
    //             b.numeroBillets,
    //             b.jourvoyage,
    //             b.heur_departs,
    //             b.numeroPlace,
    //             b.montant,
    //             c.nom AS nom_client,
    //             t.depart AS ville_depart,
    //             t.destination AS ville_destination,
    //             co.nom AS nom_compagnie,
    //             co.slogant,
    //             co.logo
    //         FROM billets b
    //         JOIN client c ON b.id_client = c.id

    //         WHERE b.idBillets = :id";

    //   $stmt = $this->connect()->prepare($sql);
    //   $stmt->execute([':id' => $idBillets]);
    //   return $stmt->fetch(PDO::FETCH_OBJ);
    // }


    public  function getHeures($destinationId, $villeDepart)
    {
      // programmer.idDepart/idDestination sont des idAgence : on les relie à la table agence
      // pour comparer sur la localité, comme dans getDestinations().
      $stmt = $this->connect()->prepare("SELECT DISTINCT p.heureDepart
                          FROM programmer p
                          INNER JOIN agence a1 ON p.idDepart = a1.idAgence
                          INNER JOIN agence a2 ON p.idDestination = a2.idAgence
                          WHERE a2.localite = :destinationId
                            AND a1.localite = :villeDepart
                            AND p.id_compagnie = :id_compagnie
                          ORDER BY p.heureDepart");
      $stmt->execute([
        ':destinationId' => $destinationId,
        ':villeDepart'   => $villeDepart,
        ':id_compagnie'  => $_SESSION['id_compagnie']
      ]);
      $results = $stmt->fetchAll(PDO::FETCH_COLUMN); // Un tableau avec toutes les heures

      return $results;
    }
    public function updateBillet($data)
    {
      $pdo = $this->connect();

      $stmtClient = $pdo->prepare("UPDATE client SET Client = :client WHERE idClient = :id_client");
      $stmtClient->execute([
        ':client'    => $data['Client'],
        ':id_client' => $data['id_client'],
      ]);

      $stmtBillet = $pdo->prepare("UPDATE billets SET date_expiration = :date_expiration WHERE idBillets = :idBillets");
      $stmtBillet->execute([
        ':date_expiration' => $data['date_expiration'],
        ':idBillets'       => $data['idBillets'],
      ]);

      $this->set_flash("Billet modifié avec succès", "success");
    }

    public function reporte_voyage($data)
    {
      $req = "UPDATE billets
            SET jourVoyage = :jourVoyage,
                Heur_departs = :Heur_departs,
                date_repporte = CURDATE()
            WHERE idBillets = :idBillets";

      $params = [
        ":jourVoyage" => $data['jourVoyage'],
        ":Heur_departs" => $data['Heur_departs'],
        ":idBillets" => $data['idBillets'],
      ];

      $modification = $this->insertion_update_simples($req, $params);

      if ($modification == true) {
        $this->set_flash("Modification faite avec succès", "primary");
      }
    }
  }
