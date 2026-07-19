<?php


class Programmation_voyage extends Model
{

    public function getHoraires()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        return $this->FetchSelectWhereS(
            "*",
            "horaire",
            "id_compagnie = :id_compagnie",
            [":id_compagnie" => $id_compagnie]
        );
    }

    // public function getProgrammationCars()
    // {
    //     $select = "liaison_car_trajet.*, trajet.*, car.*";
    //     $fromAndWhere = "liaison_car_trajet 
    //     INNER JOIN trajet ON liaison_car_trajet.id_trajets = trajet.idTrajet
    //     INNER JOIN car ON liaison_car_trajet.id_car = car.id_car";

    //     $where = "";
    //     $params = [];

    //     if (isset($_SESSION['droit'])) {
    //         if ($_SESSION['droit'] === 'Admin') {
    //             $where = " WHERE car.status_car IS NULL";
    //         } elseif ($_SESSION['droit'] === 'chef_d_escale' && isset($_SESSION['ville'])) {
    //             $where = " WHERE car.status_car = :ville";
    //             $params[':ville'] = $_SESSION['ville'];
    //         }
    //     }


    //     // Ajout du ORDER BY à la fin
    //     $fromAndWhere .= " $where ORDER BY car.numero_car, trajet.depart";

    //     return $this->SelectAllDatas($select, $fromAndWhere, $params);
    // }

    public function getProgrammationCars()
    {
        // $select = "liaison_car_trajet.*, programmer.*, car.*";
        // $fromAndWhere = "liaison_car_trajet 
        // INNER JOIN programmer ON liaison_car_trajet.id_trajets = programmer.idProgrammer
        // INNER JOIN car ON liaison_car_trajet.id_car = car.id_car";
        $select = "liaison_car_trajet.*, 
           programmer.*, 
           car.*, 
           a1.localite AS departLocalite, 
           a2.localite AS destinationLocalite";

$fromAndWhere = "liaison_car_trajet
    INNER JOIN programmer ON liaison_car_trajet.id_trajets = programmer.idProgrammer
    INNER JOIN car ON liaison_car_trajet.id_car = car.id_car
    LEFT JOIN agence a1 ON programmer.idDepart = a1.idAgence
    LEFT JOIN agence a2 ON programmer.idDestination = a2.idAgence";


        $where = "";
        $params = [];

        if (isset($_SESSION['droit'], $_SESSION['id_compagnie'])) {
            if ($_SESSION['droit'] === 'Admin') {
                // Admin : voit tous les cars disponibles de sa compagnie, quelle que soit la ville
                // où ils se trouvent actuellement — seuls les cars EN TRANSIT sont exclus.
                // (status_car vaut soit NULL/vide, soit le nom d'une ville où le car est à l'arrêt,
                // soit "En_transit_XXX" pendant un trajet ; un car "à Bamako" doit rester proposable.)
                $where = " WHERE (car.status_car IS NULL OR car.status_car NOT LIKE 'En\\_transit\\_%') AND car.id_compagnie = :compagnie";
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            } elseif ($_SESSION['droit'] === 'chef_d_escale' && isset($_SESSION['ville'])) {
                // Chef d'escale : status_car = ville, id_compagnie = leur compagnie,
                // ET seuls les trajets dont le départ correspond à la ville actuelle du car
                // sont proposés (le car ne peut pas "partir" d'une ville où il n'est pas).
                $where = " WHERE car.status_car = :ville AND car.id_compagnie = :compagnie AND a1.localite = car.status_car";
                $params[':ville'] = $_SESSION['ville'];
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            }
        }

        $fromAndWhere .= " $where ORDER BY car.numero_car, programmer.idDepart";

        return $this->SelectAllDatas($select, $fromAndWhere, $params);
    }

    // Insertion programmation avec ta méthode d'insertion
    // public function insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre)
    // {
    //     $localite_user = $_SESSION['ville'];
    //     $id_compagnie = $_SESSION["id_compagnie"];
    //     $jourVoyage = $_POST['jourVoyage'];
    //     // Empêcher que la destination soit la même que la localité de l'utilisateur
    //     if ($id_destination == $localite_user) {
    //         return false; // ou tu peux retourner un message ou une exception
    //     }

    //     $insert = "INSERT INTO programmation_voyage (
    //                 id_car_programmer, id_horaire, id_trajet, localite_user, date_enregistre, id_compagnie
    //            ) VALUES (
    //                 :id_car_programmer, :id_horaire, :id_trajet, :localite_user, :date_enregistre, :id_compagnie
    //            )";

    //     $params = [
    //         ':id_car_programmer' => $id_care,
    //         ':id_horaire' => $id_horaire,
    //         ':id_trajet' => $id_destination,
    //         ':localite_user' => $localite_user,
    //         ':date_enregistre' => $jourVoyage,
    //         ':id_compagnie' => $id_compagnie
    //     ];

    //     return $this->insertion_update_simples($insert, $params);
    // }

    public function insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre, $id_depart = null)
    {
        // Admin (plusieurs gares) : départ choisi dans le formulaire.
        // chef_d_escale (une seule gare) : toujours sa propre localité de session.
        $localite_user = $id_depart ?: $_SESSION['ville'];
        $id_compagnie = $_SESSION["id_compagnie"];

        if ($id_destination == $localite_user) {
            return false;
        }

        // Le formulaire ne propose déjà que les cars disponibles (getProgrammationCars()),
        // mais rien ne revérifiait côté serveur qu'un id_care soumis directement l'est
        // toujours : un car déjà "En_transit_*" (parti sur un trajet en cours) pouvait être
        // reprogrammé une seconde fois, écrasant le compteur de places du trajet en cours.
        // Un car peut légitimement faire plusieurs tournées par jour, mais seulement après
        // que son arrivée ait été validée (validerArrivee() le rend de nouveau disponible).
        $car = $this->fetchOne(
            "SELECT status_car FROM car WHERE id_car = :id_car AND id_compagnie = :id_compagnie",
            [':id_car' => $id_care, ':id_compagnie' => $id_compagnie]
        );

        if (!$car) {
            return false;
        }

        $statusCar = $car['status_car'];
        if ($statusCar !== null && strpos($statusCar, 'En_transit_') === 0) {
            return false;
        }

        // chef_d_escale (pas d'id_depart fourni) : le car doit être physiquement dans sa gare.
        // Admin (id_depart fourni) : peut réaffecter un car présent ailleurs dans sa compagnie
        // (choix déjà assumé dans getProgrammationCars() pour ce rôle).
        if ($id_depart === null && $statusCar !== $localite_user) {
            return false;
        }

        // 1. Insertion dans programmation_voyage
        $insert = "INSERT INTO programmation_voyage (
            id_car_programmer, id_horaire, id_trajet, localite_user, date_enregistre, id_compagnie
       ) VALUES (
            :id_car_programmer, :id_horaire, :id_trajet, :localite_user, :date_enregistre, :id_compagnie
       )";

        $params = [
            ':id_car_programmer' => $id_care,
            ':id_horaire' => $id_horaire,
            ':id_trajet' => $id_destination,
            ':localite_user' => $localite_user,
            ':date_enregistre' => $date_enregistre,
            ':id_compagnie' => $id_compagnie
        ];

        $result = $this->insertion_update_simples($insert, $params);

        // 2. Recalculer (jamais remettre à 0 aveuglément) le nombre de places déjà réservées
        //    sur ce créneau exact : une reprogrammation ne doit jamais faire "disparaître" des
        //    billets déjà vendus (aujourd'hui ou demain), sinon les places redeviennent
        //    disponibles alors que des tickets existent déjà dessus (risque de survente).
        $placesDejaVendues = $this->countPlacesVendues($id_horaire, $id_destination, $localite_user, $date_enregistre, $id_compagnie);

        $update = "UPDATE car SET nbr_place_reserve = :n WHERE id_car = :id_car";
        $this->insertion_update_simples($update, [':n' => $placesDejaVendues, ':id_car' => $id_care]);

        return $result;
    }

    // Compte les places déjà vendues (billets) pour un créneau exact (départ/destination/heure/date/compagnie).
    // Inclut les billets vendus vers une escale du trajet, car ceux-ci sont enregistrés avec le nom
    // de l'escale comme destinationId plutôt que la destination finale.
    private function countPlacesVendues($id_horaire, $id_destination, $localite_user, $jourVoyage, $id_compagnie)
    {
        $destinations = [$id_destination];

        $prog = $this->fetchOne(
            "SELECT p.idProgrammer
             FROM programmer p
             LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
             LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
             WHERE a1.localite = :dep AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
             LIMIT 1",
            [':dep' => $localite_user, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
        );

        if ($prog) {
            $escales = $this->fetchAll(
                "SELECT e.escales FROM ligneTrajet lt
                 JOIN escale e ON e.id_escale = lt.id_escales
                 WHERE lt.id_trajets = :progId AND lt.type_trajet = 'programmer'",
                [':progId' => $prog['idProgrammer']]
            );
            foreach ($escales as $e) {
                $destinations[] = $e['escales'];
            }
        }

        $placeholders = implode(',', array_fill(0, count($destinations), '?'));
        $sql = "SELECT COALESCE(SUM(nombrePassages), 0) AS total
                FROM billets
                WHERE jourVoyage = ? AND Heur_departs = ? AND departId = ? AND id_compagnie = ?
                  AND destinationId IN ($placeholders)";

        $params = array_merge([$jourVoyage, $id_horaire, $localite_user, $id_compagnie], $destinations);

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }



    public function updateCareStatus($id_car, $id_destination)
    {
        $update = "UPDATE car SET status_car = :id_trajet WHERE id_car = :id_car";
        $params = [
            ':id_trajet' => 'En_transit_' . $id_destination,
            ':id_car' => $id_car
        ];
        return $this->insertion_update_simples($update, $params);
    }

    // Dernière date (strictement avant $avant_date) où une programmation a été enregistrée.
    // Permet de reproduire "la dernière programmation" même s'il n'y en a pas eu hier
    // (jour sans activité, tout début d'utilisation du système, etc.).
    public function getDerniereDateProgrammation($id_compagnie, $avant_date, $localite_user = null)
    {
        $where = "id_compagnie = :id_compagnie AND date_enregistre < :avant_date";
        $params = [
            ':id_compagnie' => $id_compagnie,
            ':avant_date' => $avant_date
        ];

        if ($localite_user) {
            $where .= " AND localite_user = :localite_user";
            $params[':localite_user'] = $localite_user;
        }

        $sql = "SELECT MAX(date_enregistre) AS derniere_date FROM programmation_voyage WHERE $where";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() ?: null;
    }

    // Dernière programmation enregistrée pour une date donnée (typiquement la veille),
    // utilisée pour pré-remplir le formulaire et éviter de tout ressaisir chaque jour.
    // Indexé par id_car_programmer pour un accès direct depuis la vue.
    public function getProgrammationParDate($id_compagnie, $date, $localite_user = null)
    {
        $where = "pv.id_compagnie = :id_compagnie AND pv.date_enregistre = :date";
        $params = [
            ':id_compagnie' => $id_compagnie,
            ':date' => $date
        ];

        if ($localite_user) {
            $where .= " AND pv.localite_user = :localite_user";
            $params[':localite_user'] = $localite_user;
        }

        $sql = "SELECT pv.id_car_programmer, pv.id_horaire, pv.id_trajet, pv.localite_user
                FROM programmation_voyage pv
                WHERE $where";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['id_car_programmer']] = [
                'id_horaire'    => $row['id_horaire'],
                'id_trajet'     => $row['id_trajet'],
                'localite_user' => $row['localite_user']
            ];
        }
        return $result;
    }

    public function getCarsInTransit()
    {
        $select = "car.*";
        $fromAndWhere = "car";
        $where = " WHERE status_car LIKE 'En_transit_%'";
        $params = [];

        if (isset($_SESSION['droit'], $_SESSION['id_compagnie'])) {
            if ($_SESSION['droit'] === 'Admin') {
                $where .= " AND id_compagnie = :compagnie";
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            } elseif ($_SESSION['droit'] === 'chef_d_escale' && isset($_SESSION['ville'])) {
                $where = " WHERE status_car = :ville AND id_compagnie = :compagnie";
                $params[':ville'] = 'En_transit_' . $_SESSION['ville'];
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            }
        }

        $fromAndWhere .= $where . " ORDER BY numero_car ASC";
        return $this->SelectAllDatas($select, $fromAndWhere, $params);
    }

    public function validerArrivee($id_car)
    {
        $car = $this->FetchSelectWheres("status_car", "car", "id_car = :id_car", [":id_car" => $id_car]);
        if (!empty($car)) {
            $status = $car[0]->status_car;
            if (strpos($status, 'En_transit_') === 0) {
                $ville = substr($status, 11);
                $update = "UPDATE car SET status_car = :ville WHERE id_car = :id_car";
                return $this->insertion_update_simples($update, [':ville' => $ville, ':id_car' => $id_car]);
            }
        }
        return false;
    }

    // Dernière programmation enregistrée pour ce car vers cette destination : sert à retrouver
    // l'heure de départ réelle du trajet en cours (pour la règle des 3h avant validation d'arrivée)
    // et l'id_programmation à proposer en reprogrammation si l'arrivée n'est pas jugée réelle.
    public function getProgrammationActivePourCar($id_car, $destination)
    {
        $sql = "SELECT id_programmation, date_enregistre, id_horaire
                FROM programmation_voyage
                WHERE id_car_programmer = :id_car AND id_trajet = :destination
                ORDER BY date_enregistre DESC, id_programmation DESC
                LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':id_car' => $id_car, ':destination' => $destination]);
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    public function getProgrammationById($id)
    {
        return $this->FetchSelectWhereS(
            "*",
            "programmation_voyage",
            "id_programmation = :id_programmation",
            [":id_programmation" => $id]
        );
    }

    // Destinations valides pour un car donné, au départ d'une localité donnée
    // (les trajets réellement assignés à ce car via "Affectation des cars").
    public function getDestinationsForCar($id_car, $localite_depart, $id_compagnie)
    {
        $select = "programmer.idProgrammer, a1.localite AS departLocalite, a2.localite AS destinationLocalite";
        $from = "liaison_car_trajet
            INNER JOIN programmer ON liaison_car_trajet.id_trajets = programmer.idProgrammer
            INNER JOIN agence a1 ON programmer.idDepart = a1.idAgence
            INNER JOIN agence a2 ON programmer.idDestination = a2.idAgence";
        $where = "liaison_car_trajet.id_car = :id_car
            AND a1.localite = :localite_depart
            AND liaison_car_trajet.id_compagnie = :id_compagnie";

        return $this->FetchSelectWheres($select, $from, $where, [
            ':id_car' => $id_car,
            ':localite_depart' => $localite_depart,
            ':id_compagnie' => $id_compagnie
        ]);
    }

    public function updateProgrammation($id_programmation, $id_horaire, $id_destination)
    {
        $update = "UPDATE programmation_voyage
            SET id_horaire = :id_horaire, id_trajet = :id_trajet
            WHERE id_programmation = :id_programmation";

        return $this->insertion_update_simples($update, [
            ':id_horaire' => $id_horaire,
            ':id_trajet' => $id_destination,
            ':id_programmation' => $id_programmation
        ]);
    }
}
