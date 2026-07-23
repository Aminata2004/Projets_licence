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

    public function insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre, $id_depart = null, $id_agence_depart = null)
    {
        // Admin (plusieurs gares) : départ choisi dans le formulaire.
        // chef_d_escale (une seule gare) : toujours sa propre localité de session.
        $localite_user = $id_depart ?: $_SESSION['ville'];
        $id_compagnie = $_SESSION["id_compagnie"];

        if ($id_destination == $localite_user) {
            return false;
        }

        // Gare précise de départ (idAgence) : indispensable pour ne pas mélanger deux gares
        // d'une même ville sur le même créneau (ex. "Segou" Gare I et Gare II toutes deux
        // programmées vers Bamako à la même heure). Voir ajout_id_agence_programmation_voyage.sql.
        if ($id_agence_depart) {
            // Admin : revalider que la gare postée appartient bien à sa compagnie et
            // correspond à la localité choisie, jamais faire confiance à l'ID posté tel quel.
            $agenceDepart = $this->fetchOne(
                "SELECT idAgence, localite FROM agence WHERE idAgence = :id AND id_compagnie = :ic LIMIT 1",
                [':id' => $id_agence_depart, ':ic' => $id_compagnie]
            );
            if (!$agenceDepart || $agenceDepart['localite'] !== $localite_user) {
                return false;
            }
            $id_agence = $agenceDepart['idAgence'];
        } else {
            $id_agence = $_SESSION['id_agence'] ?? null;
        }

        if (!$id_agence) {
            return false;
        }

        // L'heure choisie doit correspondre à un trajet réellement programmé (table "programmer")
        // pour CETTE gare précise et cette destination : sinon on pourrait enregistrer un voyage à
        // une heure qui n'existe pas pour ce trajet, ou mélanger deux gares de la même ville.
        $trajetValide = $this->fetchOne(
            "SELECT p.idProgrammer
             FROM programmer p
             LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
             WHERE p.idDepart = :id_agence AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
             LIMIT 1",
            [':id_agence' => $id_agence, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
        );

        if (!$trajetValide) {
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
            id_car_programmer, id_horaire, id_trajet, localite_user, id_agence, date_enregistre, id_compagnie
       ) VALUES (
            :id_car_programmer, :id_horaire, :id_trajet, :localite_user, :id_agence, :date_enregistre, :id_compagnie
       )";

        $params = [
            ':id_car_programmer' => $id_care,
            ':id_horaire' => $id_horaire,
            ':id_trajet' => $id_destination,
            ':localite_user' => $localite_user,
            ':id_agence' => $id_agence,
            ':date_enregistre' => $date_enregistre,
            ':id_compagnie' => $id_compagnie
        ];

        $result = $this->insertion_update_simples($insert, $params);

        // 2. Recalculer (jamais remettre à 0 aveuglément) le nombre de places déjà réservées
        //    sur ce créneau exact : une reprogrammation ne doit jamais faire "disparaître" des
        //    billets déjà vendus (aujourd'hui ou demain), sinon les places redeviennent
        //    disponibles alors que des tickets existent déjà dessus (risque de survente).
        $placesDejaVendues = $this->countPlacesVendues($id_horaire, $id_destination, $localite_user, $date_enregistre, $id_compagnie, $id_agence);

        $update = "UPDATE car SET nbr_place_reserve = :n WHERE id_car = :id_car";
        $this->insertion_update_simples($update, [':n' => $placesDejaVendues, ':id_car' => $id_care]);

        return $result;
    }

    // Toutes les valeurs de destinationId qui correspondent à un créneau donné : la destination
    // finale du trajet, plus le nom de chaque escale (les billets vendus vers une escale sont
    // enregistrés avec le nom de l'escale comme destinationId, pas la destination finale).
    // Public : réutilisé par Transfert_gare pour retrouver les billets d'un créneau
    // (destination finale + escales) lors d'un transfert de passagers entre gares.
    public function getDestinationsPourCreneau($id_horaire, $id_destination, $localite_user, $id_compagnie, $id_agence = null)
    {
        $destinations = [$id_destination];

        // Gare précise si disponible (id_agence) : évite de mélanger deux gares d'une même
        // ville. Repli sur la ville seule pour les lignes historiques pas encore rattachées
        // à une gare précise (voir ajout_id_agence_programmation_voyage.sql).
        if ($id_agence) {
            $prog = $this->fetchOne(
                "SELECT p.idProgrammer
                 FROM programmer p
                 LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
                 WHERE p.idDepart = :id_agence AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
                 LIMIT 1",
                [':id_agence' => $id_agence, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
            );
        } else {
            $prog = $this->fetchOne(
                "SELECT p.idProgrammer
                 FROM programmer p
                 LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
                 LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
                 WHERE a1.localite = :dep AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
                 LIMIT 1",
                [':dep' => $localite_user, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
            );
        }

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

        return $destinations;
    }

    // Compte les places déjà vendues (billets) pour un créneau exact (départ/destination/heure/date/compagnie).
    // Inclut les billets vendus vers une escale du trajet, car ceux-ci sont enregistrés avec le nom
    // de l'escale comme destinationId plutôt que la destination finale.
    public function countPlacesVendues($id_horaire, $id_destination, $localite_user, $jourVoyage, $id_compagnie, $id_agence = null)
    {
        $destinations = $this->getDestinationsPourCreneau($id_horaire, $id_destination, $localite_user, $id_compagnie, $id_agence);

        $placeholders = implode(',', array_fill(0, count($destinations), '?'));
        $sql = "SELECT COALESCE(SUM(nombrePassages), 0) AS total
                FROM billets
                WHERE jourVoyage = ? AND Heur_departs = ? AND departId = ? AND id_compagnie = ?
                  AND destinationId IN ($placeholders)";

        $params = array_merge([$jourVoyage, $id_horaire, $localite_user, $id_compagnie], $destinations);

        // Gare précise si disponible : sans ceci, deux gares d'une même ville sur le même
        // créneau compteraient les billets l'une de l'autre (num_gare distingue les gares
        // qui partagent une localité, cf. ajout_id_agence_programmation_voyage.sql).
        if ($id_agence) {
            $agence = $this->fetchOne("SELECT numeroGare FROM agence WHERE idAgence = :id", [':id' => $id_agence]);
            if ($agence && $agence['numeroGare'] !== null) {
                $sql .= " AND num_gare = ?";
                $params[] = $agence['numeroGare'];
            }
        }

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
        $select = "programmer.idProgrammer, programmer.heureDepart, a1.localite AS departLocalite, a2.localite AS destinationLocalite";
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

    // Cars de la compagnie utilisables comme remplacement sur un créneau libéré : pas en
    // transit (un car déjà parti ne peut pas être affecté à un second trajet en même temps).
    public function getCarsDisponiblesPourRemplacement($id_compagnie, $id_car_a_exclure)
    {
        return $this->fetchAll(
            "SELECT id_car, numero_car FROM car
             WHERE id_compagnie = :id_compagnie
               AND id_car != :id_car_a_exclure
               AND (status_car IS NULL OR status_car NOT LIKE 'En\\_transit\\_%')
             ORDER BY numero_car",
            [':id_compagnie' => $id_compagnie, ':id_car_a_exclure' => $id_car_a_exclure]
        );
    }

    // Modifie l'heure/destination d'une programmation existante.
    //
    // S'il existe déjà des billets vendus sur l'ancien créneau (ancienne heure/destination),
    // la modification ne peut pas se faire silencieusement : ces clients ont acheté un billet
    // pour un départ précis, et changer le créneau du car sans rien faire les abandonnerait.
    // Deux issues possibles, au choix de l'utilisateur (résolu par $action) :
    //   - 'suivre'     : les billets déjà vendus suivent le car vers la nouvelle heure (uniquement
    //                    si la destination ne change pas : on ne réachemine jamais silencieusement
    //                    des clients vers une autre ville).
    //   - 'nouveau_car': l'ancien créneau (heure/destination d'origine) est repris par un autre
    //                    car choisi par l'utilisateur, pendant que celui-ci part sur le nouveau créneau.
    // Sans $action, si des réservations existent, on retourne ce qu'il faut pour proposer le choix
    // à l'utilisateur au lieu d'enregistrer quoi que ce soit.
    public function updateProgrammation($id_programmation, $id_horaire, $id_destination, $action = null, $id_car_remplacement = null)
    {
        $prog = $this->fetchOne(
            "SELECT id_car_programmer, id_horaire, id_trajet, localite_user, id_agence, date_enregistre, id_compagnie
             FROM programmation_voyage WHERE id_programmation = :id",
            [':id' => $id_programmation]
        );
        if (!$prog) {
            return ['error' => 'introuvable'];
        }

        $id_compagnie   = $prog['id_compagnie'];
        $localite_user  = $prog['localite_user'];
        $id_agence      = $prog['id_agence'];

        // La nouvelle heure doit correspondre à un trajet réellement programmé (même règle que pour
        // une nouvelle programmation) : jamais une heure qui n'existe pas pour ce départ/cette destination.
        // Gare précise si disponible (id_agence) : repli sur la ville seule pour les lignes
        // historiques pas encore rattachées à une gare précise.
        if ($id_agence) {
            $trajetValide = $this->fetchOne(
                "SELECT p.idProgrammer
                 FROM programmer p
                 LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
                 WHERE p.idDepart = :id_agence AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
                 LIMIT 1",
                [':id_agence' => $id_agence, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
            );
        } else {
            $trajetValide = $this->fetchOne(
                "SELECT p.idProgrammer
                 FROM programmer p
                 LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
                 LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
                 WHERE a1.localite = :dep AND a2.localite = :dest AND p.heureDepart = :heure AND p.id_compagnie = :id_compagnie
                 LIMIT 1",
                [':dep' => $localite_user, ':dest' => $id_destination, ':heure' => $id_horaire, ':id_compagnie' => $id_compagnie]
            );
        }
        if (!$trajetValide) {
            return ['error' => 'horaire_invalide'];
        }

        $memeCreneau = ($id_horaire === $prog['id_horaire'] && $id_destination === $prog['id_trajet']);

        if (!$memeCreneau) {
            $dejaReserve = $this->countPlacesVendues(
                $prog['id_horaire'],
                $prog['id_trajet'],
                $localite_user,
                $prog['date_enregistre'],
                $id_compagnie,
                $id_agence
            );

            if ($dejaReserve > 0) {
                $destinationChange = $id_destination !== $prog['id_trajet'];

                if ($action === null || ($destinationChange && $action !== 'nouveau_car')) {
                    return ['needs_choice' => true, 'count' => $dejaReserve, 'destination_change' => $destinationChange];
                }

                if ($action === 'nouveau_car') {
                    if (!$id_car_remplacement) {
                        return ['error' => 'car_remplacement_requis'];
                    }
                    // Réutilise la logique d'insertion existante (vérifie que le car est libre,
                    // recalcule les places déjà vendues) pour faire reprendre l'ancien créneau
                    // par le car de remplacement choisi.
                    $ok = $this->insertProgrammation(
                        $id_car_remplacement,
                        $prog['id_horaire'],
                        $prog['id_trajet'],
                        null,
                        $prog['date_enregistre'],
                        $localite_user,
                        $id_agence
                    );
                    if (!$ok) {
                        return ['error' => 'car_remplacement_invalide'];
                    }
                } elseif ($action === 'suivre') {
                    // Les billets déjà vendus (destination finale + escales de l'ancien trajet)
                    // suivent le car vers la nouvelle heure.
                    $destinationsAncien = $this->getDestinationsPourCreneau(
                        $prog['id_horaire'],
                        $prog['id_trajet'],
                        $localite_user,
                        $id_compagnie,
                        $id_agence
                    );
                    $placeholders = implode(',', array_fill(0, count($destinationsAncien), '?'));
                    $stmt = $this->connect()->prepare(
                        "UPDATE billets SET Heur_departs = ?
                         WHERE departId = ? AND Heur_departs = ? AND jourVoyage = ? AND id_compagnie = ?
                           AND destinationId IN ($placeholders)"
                    );
                    $stmt->execute(array_merge(
                        [$id_horaire, $localite_user, $prog['id_horaire'], $prog['date_enregistre'], $id_compagnie],
                        $destinationsAncien
                    ));
                }
            }
        }

        $update = "UPDATE programmation_voyage
            SET id_horaire = :id_horaire, id_trajet = :id_trajet
            WHERE id_programmation = :id_programmation";

        $result = $this->insertion_update_simples($update, [
            ':id_horaire' => $id_horaire,
            ':id_trajet' => $id_destination,
            ':id_programmation' => $id_programmation
        ]);

        // Le car d'origine sert maintenant le NOUVEAU créneau : son compteur de places réservées
        // doit refléter ce nouveau créneau, pas l'ancien (sinon il resterait avec un décompte qui
        // ne correspond plus à rien, faussant la capacité disponible affichée).
        $placesActuelles = $this->countPlacesVendues($id_horaire, $id_destination, $localite_user, $prog['date_enregistre'], $id_compagnie, $id_agence);
        $this->insertion_update_simples(
            "UPDATE car SET nbr_place_reserve = :n WHERE id_car = :id_car",
            [':n' => $placesActuelles, ':id_car' => $prog['id_car_programmer']]
        );

        return $result;
    }
}
