 <?php
  class Liste_du_jour extends Model
  {

    // Heure de départ "en cours" pour cette gare : la prochaine heure programmée non
    // encore dépassée, ou la dernière de la journée si toutes sont déjà passées.
    // Remplace une ancienne grille d'horaires codée en dur dans la vue (05:00, 06:00,
    // 08:00...) qui ne correspondait qu'aux horaires d'une seule compagnie et rendait
    // invisibles les billets de toute compagnie ayant configuré d'autres heures.
    public function getHeureDepartCourante($villeDepart, $idCompagnie)
    {
      $heures = $this->fetchAll(
        "SELECT DISTINCT p.heureDepart
         FROM programmer p
         LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
         WHERE a1.localite = :depart AND p.id_compagnie = :id_compagnie
         ORDER BY p.heureDepart",
        [':depart' => $villeDepart, ':id_compagnie' => $idCompagnie]
      );

      if (empty($heures)) {
        return null;
      }

      $current = date('H:i:s');
      foreach ($heures as $row) {
        if ($row['heureDepart'] >= $current) {
          return $row['heureDepart'];
        }
      }

      return end($heures)['heureDepart'];
    }

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
      // Filtré par compagnie de session : empêche un utilisateur de consulter/modifier
      // un billet d'une autre compagnie en changeant simplement l'ID dans l'URL/le formulaire.
      $sql = "SELECT * FROM billets inner join client on billets.id_client = client.idClient
        inner join utilisateur on utilisateur.idUser = billets.idUser
        WHERE idBillets = :id AND billets.id_compagnie = :id_compagnie";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([
        ':id' => $idBillets,
        ':id_compagnie' => $_SESSION['id_compagnie'] ?? null
      ]);
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
      $id_compagnie = $_SESSION['id_compagnie'] ?? null;

      // id_client vient toujours du billet vérifié (appelant), jamais directement du POST,
      // pour empêcher d'écraser le nom d'un client arbitraire via un id_client falsifié.
      $stmtClient = $pdo->prepare("UPDATE client SET Client = :client WHERE idClient = :id_client");
      $stmtClient->execute([
        ':client'    => $data['Client'],
        ':id_client' => $data['id_client'],
      ]);

      $stmtBillet = $pdo->prepare("UPDATE billets SET date_expiration = :date_expiration WHERE idBillets = :idBillets AND id_compagnie = :id_compagnie");
      $stmtBillet->execute([
        ':date_expiration' => $data['date_expiration'],
        ':idBillets'       => $data['idBillets'],
        ':id_compagnie'    => $id_compagnie,
      ]);

      if ($stmtBillet->rowCount() > 0) {
        $this->set_flash("Billet modifié avec succès", "success");
      } else {
        $this->set_flash("Aucun billet correspondant n'a été modifié.", "warning");
      }
    }

    // Reporte un billet à une nouvelle date/heure en maintenant les compteurs de places
    // cohérents : libère la place sur l'ANCIEN créneau (car ou suivis selon le jour) puis
    // la réserve sur le NOUVEAU créneau, avec les mêmes contrôles de capacité que la vente
    // initiale (Add_billet::saveBillets). Avant cette correction, seule la date/heure du
    // billet était modifiée : la place restait bloquée à tort sur l'ancien créneau et
    // n'était jamais décomptée sur le nouveau (risque de survente).
    public function reporte_voyage($data)
    {
      $pdo = $this->connect();
      $id_compagnie = $_SESSION['id_compagnie'] ?? null;

      try {
        $pdo->beginTransaction();

        // Verrouille le billet le temps de la transaction pour éviter qu'une annulation ou
        // une vente concurrente sur le même créneau ne modifie les compteurs en parallèle.
        $stmt = $pdo->prepare(
          "SELECT * FROM billets WHERE idBillets = :id AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
        );
        $stmt->execute([':id' => $data['idBillets'], ':id_compagnie' => $id_compagnie]);
        $billet = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$billet) {
          $pdo->rollBack();
          $this->set_flash("Aucune modification effectuée : billet introuvable.", "warning");
          return;
        }

        date_default_timezone_set('Africa/Bamako');
        $aujourdhui  = date('Y-m-d');
        $demain      = date('Y-m-d', strtotime('+1 day'));
        $ancienJour  = date('Y-m-d', strtotime($billet['jourVoyage']));
        $nouveauJour = date('Y-m-d', strtotime($data['jourVoyage']));
        $nombrePassages = (int)$billet['nombrePassages'];

        $mainDest = $this->resolveDestinationPrincipale(
          $billet['departId'], $billet['Heur_departs'], $billet['destinationId'], $id_compagnie
        );

        // 1) Libère la place sur l'ANCIEN créneau, s'il était suivi (aujourd'hui ou demain).
        if ($ancienJour === $aujourdhui) {
          $rowProg = $this->fetchOne(
            "SELECT id_car_programmer FROM programmation_voyage
             WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
             AND localite_user = :l AND id_compagnie = :c LIMIT 1",
            [':h' => $billet['Heur_departs'], ':d' => $ancienJour, ':t' => $mainDest, ':l' => $billet['departId'], ':c' => $id_compagnie]
          );
          if ($rowProg) {
            $stmt = $pdo->prepare("SELECT nbr_place_reserve FROM car WHERE id_car = :id FOR UPDATE");
            $stmt->execute([':id' => $rowProg['id_car_programmer']]);
            $car = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($car) {
              $nouveauReserve = max(0, (int)$car['nbr_place_reserve'] - $nombrePassages);
              $pdo->prepare("UPDATE car SET nbr_place_reserve = :n WHERE id_car = :id")
                ->execute([':n' => $nouveauReserve, ':id' => $rowProg['id_car_programmer']]);
            }
          }
        } elseif ($ancienJour === $demain) {
          $stmt = $pdo->prepare(
            "SELECT idSuivis, place_reserve FROM suivis
             WHERE depart = :dep AND destination = :dest AND heur_depart = :h
             AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
          );
          $stmt->execute([':dep' => $billet['departId'], ':dest' => $mainDest, ':h' => $billet['Heur_departs'], ':jr' => $ancienJour, ':id_compagnie' => $id_compagnie]);
          $suivi = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($suivi) {
            $nouveauReserve = max(0, (int)$suivi['place_reserve'] - $nombrePassages);
            $pdo->prepare("UPDATE suivis SET place_reserve = :n WHERE idSuivis = :id")
              ->execute([':n' => $nouveauReserve, ':id' => $suivi['idSuivis']]);
          }
        }

        // 2) Réserve la place sur le NOUVEAU créneau, s'il est suivi (aujourd'hui ou demain).
        //    Recalcule aussi le numéro de place : celui de l'ancien créneau n'a plus de sens ici.
        $numPlace = $billet['numeroPlace'];

        if ($nouveauJour === $aujourdhui) {
          $rowProg = $this->fetchOne(
            "SELECT id_car_programmer FROM programmation_voyage
             WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
             AND localite_user = :l AND id_compagnie = :c LIMIT 1",
            [':h' => $data['Heur_departs'], ':d' => $nouveauJour, ':t' => $mainDest, ':l' => $billet['departId'], ':c' => $id_compagnie]
          );
          if (!$rowProg) {
            $pdo->rollBack();
            $this->set_flash("Aucun car programmé pour cette heure et ce trajet à la nouvelle date.", "danger");
            return;
          }
          $stmt = $pdo->prepare("SELECT nbr_place, nbr_place_reserve FROM car WHERE id_car = :id FOR UPDATE");
          $stmt->execute([':id' => $rowProg['id_car_programmer']]);
          $car = $stmt->fetch(PDO::FETCH_ASSOC);
          if (!$car) {
            $pdo->rollBack();
            $this->set_flash("Car introuvable pour la nouvelle date.", "danger");
            return;
          }
          $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
          if ($nombrePassages > $placesDispo) {
            $pdo->rollBack();
            $this->set_flash("Places insuffisantes sur le nouveau créneau : $placesDispo restantes.", "danger");
            return;
          }
          $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE id_car = :id")
            ->execute([':n' => $nombrePassages, ':id' => $rowProg['id_car_programmer']]);

          $start = (int)$car['nbr_place_reserve'] + 1;
          $end   = $start + $nombrePassages - 1;
          $numPlace = ($nombrePassages == 1) ? "$start" : "$start-$end";
        } elseif ($nouveauJour === $demain) {
          $stmt = $pdo->prepare("SELECT place_minumale FROM place_minumale LIMIT 1");
          $stmt->execute();
          $rowPlace = $stmt->fetch();
          $placeTotale = $rowPlace ? (int)$rowPlace['place_minumale'] : 0;

          $stmt = $pdo->prepare(
            "SELECT idSuivis, place_totals, place_reserve FROM suivis
             WHERE depart = :dep AND destination = :dest AND heur_depart = :h
             AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
          );
          $stmt->execute([':dep' => $billet['departId'], ':dest' => $mainDest, ':h' => $data['Heur_departs'], ':jr' => $nouveauJour, ':id_compagnie' => $id_compagnie]);
          $suivi = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($suivi) {
            $placesDispo = $suivi['place_totals'] - $suivi['place_reserve'];
            if ($nombrePassages > $placesDispo) {
              $pdo->rollBack();
              $this->set_flash("Places insuffisantes pour demain sur le nouveau créneau : $placesDispo restantes.", "danger");
              return;
            }
            $pdo->prepare("UPDATE suivis SET place_reserve = place_reserve + :n WHERE idSuivis = :id")
              ->execute([':n' => $nombrePassages, ':id' => $suivi['idSuivis']]);
          } else {
            if ($placeTotale <= 0) {
              $pdo->rollBack();
              $this->set_flash("Erreur : nombre de places minimales non défini.", "danger");
              return;
            }
            if ($nombrePassages > $placeTotale) {
              $pdo->rollBack();
              $this->set_flash("Places insuffisantes pour demain sur le nouveau créneau : $placeTotale restantes.", "danger");
              return;
            }
            $pdo->prepare(
              "INSERT INTO suivis (place_reserve, place_totals, depart, destination, heur_depart, date_reservation, id_compagnie)
               VALUES (:n, :total, :dep, :dest, :h, :jr, :id_compagnie)"
            )->execute([
              ':n' => $nombrePassages, ':total' => $placeTotale, ':dep' => $billet['departId'], ':dest' => $mainDest,
              ':h' => $data['Heur_departs'], ':jr' => $nouveauJour, ':id_compagnie' => $id_compagnie
            ]);
          }

          $stmt = $pdo->prepare("SELECT numeroPlace FROM billets
            WHERE jourVoyage = :j AND Heur_departs = :h AND departId = :dep AND destinationId = :dest
            AND id_compagnie = :id_compagnie AND idBillets != :idBillets");
          $stmt->execute([
            ':j' => $nouveauJour, ':h' => $data['Heur_departs'], ':dep' => $billet['departId'],
            ':dest' => $mainDest, ':id_compagnie' => $id_compagnie, ':idBillets' => $data['idBillets']
          ]);
          $placesPrises = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach (explode('-', $row['numeroPlace']) as $p) {
              $placesPrises[] = (int)$p;
            }
          }
          $start = 1;
          $numPlacesAttribues = [];
          while (count($numPlacesAttribues) < $nombrePassages) {
            if (!in_array($start, $placesPrises)) {
              $numPlacesAttribues[] = $start;
            }
            $start++;
          }
          $numPlace = implode('-', $numPlacesAttribues);
        }

        // 3) Met à jour le billet lui-même.
        $stmtUpd = $pdo->prepare(
          "UPDATE billets
           SET jourVoyage = :jourVoyage, Heur_departs = :Heur_departs, numeroPlace = :numeroPlace, date_repporte = CURDATE()
           WHERE idBillets = :idBillets AND id_compagnie = :id_compagnie"
        );
        $stmtUpd->execute([
          ':jourVoyage'   => $data['jourVoyage'],
          ':Heur_departs' => $data['Heur_departs'],
          ':numeroPlace'  => $numPlace,
          ':idBillets'    => $data['idBillets'],
          ':id_compagnie' => $id_compagnie,
        ]);

        if ($stmtUpd->rowCount() === 0) {
          $pdo->rollBack();
          $this->set_flash("Aucune modification effectuée : billet introuvable.", "warning");
          return;
        }

        $pdo->commit();
        $this->set_flash("Voyage reporté avec succès, places mises à jour.", "primary");
      } catch (Throwable $e) {
        $pdo->rollBack();
        $this->set_flash("Erreur lors du report : " . $e->getMessage(), "danger");
      }
    }

    // Retrouve la destination FINALE du trajet à partir du nom stocké sur le billet, qui peut
    // être soit la destination finale, soit le nom d'une escale intermédiaire (prix différent,
    // mais même car/même créneau de programmation).
    private function resolveDestinationPrincipale($depart, $heure, $destinationId, $id_compagnie)
    {
      $direct = $this->fetchOne(
        "SELECT a2.localite FROM programmer p
         LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
         LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
         WHERE a1.localite = :dep AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
         LIMIT 1",
        [':dep' => $depart, ':dest' => $destinationId, ':heure' => $heure, ':id_compagnie' => $id_compagnie]
      );
      if ($direct) {
        return $destinationId;
      }

      $viaEscale = $this->fetchOne(
        "SELECT a2.localite AS mainDest FROM ligneTrajet lt
         JOIN escale e ON e.id_escale = lt.id_escales
         JOIN programmer p ON p.idProgrammer = lt.id_trajets AND lt.type_trajet = 'programmer'
         LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
         LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
         WHERE e.escales = :dest AND a1.localite = :dep AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
         LIMIT 1",
        [':dest' => $destinationId, ':dep' => $depart, ':heure' => $heure, ':id_compagnie' => $id_compagnie]
      );

      return $viaEscale['mainDest'] ?? $destinationId;
    }

    // Annule un billet vendu au guichet : restaure la place vendue (car ou suivis selon le jour),
    // marque le billet comme annulé et déduit le montant de la caisse actuellement ouverte pour
    // cette gare (si aucune caisse n'est ouverte, l'annulation a quand même lieu mais la caisse
    // devra être ajustée manuellement).
    public function annulerBillet($idBillets, $motif = '')
    {
      if (!csrf_verify()) {
        $this->set_flash("Session expirée, veuillez réessayer.", "danger");
        return false;
      }

      $billet = $this->getBilletById($idBillets); // déjà filtré par compagnie de session
      if (!$billet) {
        $this->set_flash("Billet introuvable.", "danger");
        return false;
      }

      if (($billet->status_billets ?? null) === 'annule') {
        $this->set_flash("Ce billet est déjà annulé.", "warning");
        return false;
      }

      date_default_timezone_set('Africa/Bamako');
      $aujourdhui = date('Y-m-d');
      $jourVoyage = date('Y-m-d', strtotime($billet->jourVoyage));

      if ($jourVoyage < $aujourdhui) {
        $this->set_flash("Impossible d'annuler un billet dont le voyage est déjà passé.", "danger");
        return false;
      }

      $pdo = $this->connect();

      try {
        $pdo->beginTransaction();

        $mainDest = $this->resolveDestinationPrincipale(
          $billet->departId,
          $billet->Heur_departs,
          $billet->destinationId,
          $billet->id_compagnie
        );

        if ($jourVoyage == $aujourdhui) {
          // Aujourd'hui : la place vendue est comptabilisée sur car.nbr_place_reserve
          $rowProg = $this->fetchOne(
            "SELECT id_car_programmer FROM programmation_voyage
             WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
             AND localite_user = :l AND id_compagnie = :c LIMIT 1",
            [
              ':h' => $billet->Heur_departs,
              ':d' => $jourVoyage,
              ':t' => $mainDest,
              ':l' => $billet->departId,
              ':c' => $billet->id_compagnie
            ]
          );

          if ($rowProg) {
            $stmt = $pdo->prepare("SELECT nbr_place_reserve FROM car WHERE id_car = :id FOR UPDATE");
            $stmt->execute([':id' => $rowProg['id_car_programmer']]);
            $car = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($car) {
              $nouveauReserve = max(0, (int)$car['nbr_place_reserve'] - (int)$billet->nombrePassages);
              $upd = $pdo->prepare("UPDATE car SET nbr_place_reserve = :n WHERE id_car = :id");
              $upd->execute([':n' => $nouveauReserve, ':id' => $rowProg['id_car_programmer']]);
            }
          }
        } else {
          // Demain : la place vendue est comptabilisée dans la table suivis
          $stmt = $pdo->prepare(
            "SELECT idSuivis, place_reserve FROM suivis
             WHERE depart = :dep AND destination = :dest AND heur_depart = :h
             AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
          );
          $stmt->execute([
            ':dep' => $billet->departId,
            ':dest' => $mainDest,
            ':h' => $billet->Heur_departs,
            ':jr' => $jourVoyage,
            ':id_compagnie' => $billet->id_compagnie
          ]);
          $suivi = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($suivi) {
            $nouveauReserve = max(0, (int)$suivi['place_reserve'] - (int)$billet->nombrePassages);
            $upd = $pdo->prepare("UPDATE suivis SET place_reserve = :n WHERE idSuivis = :id");
            $upd->execute([':n' => $nouveauReserve, ':id' => $suivi['idSuivis']]);
          }
        }

        // Marquer le billet comme annulé
        $updBillet = $pdo->prepare(
          "UPDATE billets SET status_billets = 'annule', date_annulation = NOW(), motif_annulation = :motif, annule_par = :annule_par
           WHERE idBillets = :id AND id_compagnie = :id_compagnie"
        );
        $updBillet->execute([
          ':motif' => $motif !== '' ? $motif : null,
          ':annule_par' => $_SESSION['id_utilisateur'] ?? null,
          ':id' => $idBillets,
          ':id_compagnie' => $billet->id_compagnie
        ]);

        // Ajuster la caisse actuellement ouverte pour cette gare, si elle existe
        $stmtCaisse = $pdo->prepare("
          SELECT c.id_caisse
          FROM caisse c
          INNER JOIN agence a ON c.id_agence = a.idAgence
          WHERE c.id_compagnie = :id_compagnie
            AND a.localite = :ville
            AND a.numeroGare = :numeroGare
            AND c.status_caisse = 1
          LIMIT 1
        ");
        $stmtCaisse->execute([
          ':id_compagnie' => $billet->id_compagnie,
          ':ville' => $billet->departId,
          ':numeroGare' => $billet->num_gare
        ]);
        $caisse = $stmtCaisse->fetch(PDO::FETCH_ASSOC);

        if ($caisse) {
          $updCaisse = $pdo->prepare("UPDATE caisse SET montant_billets = GREATEST(0, montant_billets - :montant) WHERE id_caisse = :id_caisse");
          $updCaisse->execute([':montant' => $billet->montant_payer, ':id_caisse' => $caisse['id_caisse']]);
        }

        $pdo->commit();

        if ($caisse) {
          $this->set_flash("Billet annulé avec succès. Caisse ajustée de -" . number_format((float)$billet->montant_payer, 0, ',', ' ') . " FCFA.", "success");
        } else {
          $this->set_flash("Billet annulé avec succès. Aucune caisse ouverte pour cette gare : ajustez le montant manuellement.", "warning");
        }
        return true;
      } catch (Throwable $e) {
        $pdo->rollBack();
        $this->set_flash("Erreur lors de l'annulation : " . $e->getMessage(), "danger");
        return false;
      }
    }
  }
