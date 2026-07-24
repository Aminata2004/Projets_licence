<?php

class Envoie_colis extends Model
{
   public function traiterEnvoi($colis_ids, $id_car)
   {
      $date_enregistre = date('YmdHis');
      $id_compagnie = $_SESSION['id_compagnie'];

      // Ne traiter que les colis appartenant réellement à la compagnie de l'utilisateur :
      // sans ce filtre, un id_colis posté d'une AUTRE compagnie était accepté tel quel et son
      // statut passait à 'en_cours' (IDOR en écriture sur les données d'une autre compagnie).
      $colis_ids = $this->filtrerColisDeLaCompagnie($colis_ids, $id_compagnie);
      if (empty($colis_ids)) {
         return;
      }

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
      $sql = "UPDATE colis SET status = 'en_cours' WHERE id_colis IN ($placeholders) AND id_compagnie = ?";
      $this->connect()->prepare($sql)->execute([...$colis_ids, $id_compagnie]);
   }

   // Restreint une liste d'id_colis postés à ceux qui appartiennent réellement à la
   // compagnie de l'utilisateur connecté (protection IDOR commune à traiterEnvoi/traiterEnvoi1).
   private function filtrerColisDeLaCompagnie(array $colis_ids, $id_compagnie): array
   {
      if (empty($colis_ids)) {
         return [];
      }
      $placeholders = implode(',', array_fill(0, count($colis_ids), '?'));
      $params = $colis_ids;
      $params[] = $id_compagnie;
      $stmt = $this->connect()->prepare(
         "SELECT id_colis FROM colis WHERE id_colis IN ($placeholders) AND id_compagnie = ?"
      );
      $stmt->execute($params);
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
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

   // public function getCarById($id)
   // {
   //    $result = $this->FetchSelectWhere1(
   //       "*",
   //       "programmation_voyage  inner join horaire on horaire.id_heure= programmation_voyage.id_horaire",
   //       "id_car_programmer = :id_car_programmer",
   //       [":id_car_programmer" => $id]
   //    );

   //    return !empty($result) ? $result[0] : null;
      
   // }
   public function getCarById($id)
{
    // programmation_voyage.id_horaire stocke directement l'heure (ex: "04:00:00"),
    // pas l'id de la table horaire : la jointure doit se faire sur heuredepart, pas id_heure.
    $result = $this->FetchSelectWhere1(
        "*",
        "programmation_voyage
         INNER JOIN horaire
            ON horaire.heuredepart = programmation_voyage.id_horaire
           AND horaire.id_compagnie = programmation_voyage.id_compagnie",
        "programmation_voyage.id_car_programmer = :id_car_programmer
         AND programmation_voyage.id_compagnie = :id_compagnie
         AND programmation_voyage.date_enregistre = :today",
        [
            ":id_car_programmer" => $id,
            ":id_compagnie" => $_SESSION['id_compagnie'] ?? null,
            ":today" => date('Y-m-d')
        ]
    );

    return !empty($result) ? $result[0] : null;
}


   public function traiterEnvoi1($colis_ids, $id_car)
   {
      date_default_timezone_set('Africa/Bamako');
      $id_compagnie = $_SESSION['id_compagnie'];

      // Ne traiter que les colis appartenant réellement à la compagnie de l'utilisateur (IDOR sinon).
      $colis_ids = $this->filtrerColisDeLaCompagnie($colis_ids, $id_compagnie);
      if (empty($colis_ids)) {
         return;
      }

      $date_enregistre = $this->getOuCreerLigneEnvoiDuJour($id_car, $id_compagnie);

      // Insertion dans la table envoi
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

      // Mise à jour du statut des colis
      $placeholders = implode(',', array_fill(0, count($colis_ids), '?'));
      $sql = "UPDATE colis SET status = 'en_cours' WHERE id_colis IN ($placeholders) AND id_compagnie = ?";
      $this->connect()->prepare($sql)->execute([...$colis_ids, $id_compagnie]);
   }

   // Retourne la date du lot d'envoi du jour pour ce car (le crée s'il n'existe pas encore).
   private function getOuCreerLigneEnvoiDuJour($id_car, $id_compagnie)
   {
      $date_aujourdhui = date('Y-m-d');

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
         return $ligne_existante['dates'];
      }

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

      return $date_enregistre;
   }

   // Liste des cars programmés aujourd'hui pouvant recevoir des colis :
   // un chef d'escale ne voit que les cars au départ de sa propre ville, l'Admin voit tout.
   public function getCarsDisponiblesAujourdhui()
   {
      $condition = "programmation_voyage.date_enregistre = :today
       AND programmation_voyage.id_compagnie = :id_compagnie
       AND programmation_voyage.statut = 'active'";
      $params = [
         ":today" => date('Y-m-d'),
         ":id_compagnie" => $_SESSION['id_compagnie']
      ];

      // id_agence (pas seulement localite_user) : deux gares peuvent partager la même ville,
      // cf. ajout_id_agence_programmation_voyage.sql.
      if (($_SESSION['droit'] ?? null) === 'chef_d_escale') {
         $condition .= " AND programmation_voyage.localite_user = :ville AND programmation_voyage.id_agence = :id_agence";
         $params[":ville"] = $_SESSION['ville'];
         $params[":id_agence"] = $_SESSION['id_agence'] ?? null;
      }

      return $this->FetchSelectWhere1(
         "DISTINCT
           programmation_voyage.id_car_programmer,
           programmation_voyage.id_horaire,
           programmation_voyage.id_trajet,
           programmation_voyage.localite_user,
           programmation_voyage.date_enregistre,
           programmation_voyage.id_compagnie AS compagnie_prog,
           horaire.heuredepart,
           horaire.id_compagnie AS compagnie_horaire",
         "programmation_voyage
       INNER JOIN horaire
          ON horaire.heuredepart = programmation_voyage.id_horaire
          AND horaire.id_compagnie = programmation_voyage.id_compagnie",
         $condition,
         $params
      );
   }

   // Déplace un colis déjà envoyé vers un autre car (ex: erreur d'affectation, changement de dernière minute).
   public function changerCarColis($id_colis, $ancien_id_car, $ancienne_date, $nouveau_id_car)
   {
      $id_compagnie = $_SESSION['id_compagnie'];
      date_default_timezone_set('Africa/Bamako');
      $nouvelle_date = $this->getOuCreerLigneEnvoiDuJour($nouveau_id_car, $id_compagnie);

      $stmt = $this->insertion_update_simples(
         "UPDATE envoi
          SET id_car = :nouveau_id_car, date_enregistre = :nouvelle_date
          WHERE id_coli = :id_colis
            AND id_car = :ancien_id_car
            AND date_enregistre = :ancienne_date
            AND id_compagnie = :id_compagnie",
         [
            ':nouveau_id_car' => $nouveau_id_car,
            ':nouvelle_date' => $nouvelle_date,
            ':id_colis' => $id_colis,
            ':ancien_id_car' => $ancien_id_car,
            ':ancienne_date' => $ancienne_date,
            ':id_compagnie' => $id_compagnie
         ]
      );

      return $stmt && $stmt->rowCount() > 0;
   }

   // Annule un envoi complet (tous les colis d'un car pour une date donnée) :
   // les colis redeviennent disponibles ('enregistre') et le lot d'envoi est supprimé.
   public function annulerEnvoi($id_car, $date_envoi)
   {
      $id_compagnie = $_SESSION['id_compagnie'];

      $stmt = $this->connect()->prepare(
         "SELECT id_coli FROM envoi WHERE id_car = :id_car AND date_enregistre = :date_envoi AND id_compagnie = :id_compagnie"
      );
      $stmt->execute([':id_car' => $id_car, ':date_envoi' => $date_envoi, ':id_compagnie' => $id_compagnie]);
      $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if (empty($ids)) {
         return false;
      }

      $placeholders = implode(',', array_fill(0, count($ids), '?'));
      $this->connect()
         ->prepare("UPDATE colis SET status = 'enregistre' WHERE id_colis IN ($placeholders)")
         ->execute($ids);

      $this->insertion_update_simples(
         "DELETE FROM envoi WHERE id_car = :id_car AND date_enregistre = :date_envoi AND id_compagnie = :id_compagnie",
         [':id_car' => $id_car, ':date_envoi' => $date_envoi, ':id_compagnie' => $id_compagnie]
      );

      $this->insertion_update_simples(
         "DELETE FROM ligne_envoi WHERE numero_car = :numero_car AND dates = :date_envoi AND id_compagnie = :id_compagnie",
         [':numero_car' => $id_car, ':date_envoi' => $date_envoi, ':id_compagnie' => $id_compagnie]
      );

      return true;
   }
}
