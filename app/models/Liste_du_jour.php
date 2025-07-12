 <?php
  class Liste_du_jour extends Model
  {

    public function getDestinations($idDepart, $idCompagnie)
    {
      $sql = "SELECT DISTINCT p.idDestination
            FROM programmer p
            WHERE p.idDepart = :idDepart
              AND p.id_compagnie = :idCompagnie
            ORDER BY p.idDestination";
      return $this->fetchAll($sql, [
        ':idDepart' => $idDepart,
        ':idCompagnie' => $idCompagnie
      ]);
    }

    public function listeBillets()
    {
      $id_compagnie = $_SESSION['id_compagnie'];
      $liste = $this->FetchSelectWheres(
        '*',
        'billets inner join client on billets.id_client = client.idClient',
        'billets.id_compagnie = :id_compagnie ORDER BY billets.idBillets DESC LIMIT 10',
        ['id_compagnie' => $id_compagnie]
      );
      return $liste;
    }


    public function getBilletById($idBillets)
    {
      $sql = "SELECT * FROM billets inner join client on billets.id_client = client.idClient 
        inner join utilisateur on utilisateur.idUser = client.idUser
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
      $stmt = $this->connect()->prepare("SELECT heureDepart FROM programmer 
                          WHERE idDestination = ? 
                          AND idDepart = ?");
      $stmt->execute([$destinationId, $villeDepart]);
      $results = $stmt->fetchAll(PDO::FETCH_COLUMN); // Un tableau avec toutes les heures

      return $results;
    }
   public function reporte_voyage($data)
{
    $req = "UPDATE billets 
            SET jourVoyage = :jourVoyage,
                Heur_departs = :Heur_departs
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
