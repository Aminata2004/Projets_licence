<?php

class Envoie_colis extends Model
{
   public function traiterEnvoi($colis_ids, $id_car)
   {
      $date_enregistre = date('YmdHis');
      $id_compagnie = $_SESSION['id_compagnie'];
      // Insertion dans ligne_envoi
      $this->insertion_update_simples(
         "INSERT INTO ligne_envoi (numero_car, dates,id_compagnie) VALUES(:numero_car, :dates,:id_compagnie)",
         [
            ':numero_car' => $id_car,
            ':dates' => $date_enregistre,
            ':id_compagnie' => $id_compagnie
         ]
      );

      // Insertion dans la table envoi pour chaque colis
      foreach ($colis_ids as $id_colis) {
         $this->insertion_update_simples(
            "INSERT INTO envoi (id_coli, id_car, date_enregistre, id_compagnie) 
       VALUES (:id_coli, :id_car, :date_enregistre, :id_compagnie)",
            [
               ':id_coli' => $id_colis,
               ':id_car' => $id_car,
               ':date_enregistre' => $date_enregistre,
               ':id_compagnie' => $id_compagnie
            ]
         );
      }


      // Mise à jour des statuts des colis sélectionnés
      $placeholders = implode(',', array_fill(0, count($colis_ids), '?'));
      $sql = "UPDATE colis SET status = 'en_cours' WHERE id_colis IN ($placeholders)";
      $this->connect()->prepare($sql)->execute($colis_ids);
   }

   public function getColisEnregistres()
   {
      return $this->FetchSelectWheres(
         "*",
         "colis",
         "status = :status",
         [":status" => "enregistre"]
      );
   }

   public function getCarsProgrammes()
   {
      return $this->FetchSelectWheres(
         "*",
         "programmation_voyage",
         "1",
         []
      );
   }

   public function FetchSelectWheres($select, $fields, $whereValue, $value = [])
   {
      $bdd = $this->connect();
      $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue");
      $que->execute($value);
      $count = $que->fetchAll(PDO::FETCH_OBJ);
      $que->closeCursor();
      return $count;
   }

   public function getColisParCarEtDate($id_car, $date_envoi)
   {
      $query = "SELECT envoi.*, colis.* 
              FROM envoi 
              INNER JOIN colis ON envoi.id_coli = colis.id_colis
              WHERE envoi.id_car = :id_car AND envoi.date_enregistre = :date_envoi";

      $stmt = $this->connect()->prepare($query);
      $stmt->execute([
         ':id_car' => $id_car,
         ':date_envoi' => $date_envoi
      ]);

      return $stmt->fetchAll(PDO::FETCH_OBJ);
   }

   public function getCarById($id)
   {
      $result = $this->FetchSelectWhere1(
         "*",
         "programmation_voyage  inner join horaire on horaire.id_heure= programmation_voyage.id_horaire",
         "id_car_programmer = :id_car_programmer",
         [":id_car_programmer" => $id]
      );

      return !empty($result) ? $result[0] : null;
   }

   public function traiterEnvoi1($colis_ids, $id_car)
   {
      $id_compagnie = $_SESSION['id_compagnie'];
      $date_aujourdhui = date('Y-m-d'); // pour la comparaison

      // 1. Vérifier si une ligne existe déjà aujourd'hui pour ce car
      $stmt = $this->connect()->prepare("
        SELECT dates 
        FROM ligne_envoi 
        WHERE numero_car = :numero_car 
          AND DATE(dates) = :date_aujourdhui 
          AND id_compagnie = :id_compagnie 
        LIMIT 1
    ");
      $stmt->execute([
         ':numero_car' => $id_car,
         ':date_aujourdhui' => $date_aujourdhui,
         ':id_compagnie' => $id_compagnie
      ]);
      $ligne_existante = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($ligne_existante) {
         // 2. Si une ligne existe, on utilise la même date
         $date_enregistre = $ligne_existante['dates'];
      } else {
         // 3. Sinon on insère une nouvelle ligne et utilise cette nouvelle date
         $date_enregistre = date('YmdHis');
         $this->insertion_update_simples(
            "INSERT INTO ligne_envoi (numero_car, dates, id_compagnie) 
             VALUES(:numero_car, :dates, :id_compagnie)",
            [
               ':numero_car' => $id_car,
               ':dates' => $date_enregistre,
               ':id_compagnie' => $id_compagnie
            ]
         );
      }

      // 4. Insertion dans la table envoi
      foreach ($colis_ids as $id_colis) {
         $this->insertion_update_simples(
            "INSERT INTO envoi (id_coli, id_car, date_enregistre, id_compagnie) 
             VALUES (:id_coli, :id_car, :date_enregistre, :id_compagnie)",
            [
               ':id_coli' => $id_colis,
               ':id_car' => $id_car,
               ':date_enregistre' => $date_enregistre,
               ':id_compagnie' => $id_compagnie
            ]
         );
      }

      // 5. Mise à jour du statut des colis
      $placeholders = implode(',', array_fill(0, count($colis_ids), '?'));
      $sql = "UPDATE colis SET status = 'en_cours' WHERE id_colis IN ($placeholders)";
      $this->connect()->prepare($sql)->execute($colis_ids);
   }
}
