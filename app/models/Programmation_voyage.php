<?php


class Programmation_voyage extends Model
{

    public function getHoraires()
    {
        return $this->SelectAllData('*', "horaire");
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
    //         } elseif ($_SESSION['droit'] === 'Admin_regionale' && isset($_SESSION['ville'])) {
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
        $select = "liaison_car_trajet.*, trajet.*, car.*";
        $fromAndWhere = "liaison_car_trajet 
        INNER JOIN trajet ON liaison_car_trajet.id_trajets = trajet.idTrajet
        INNER JOIN car ON liaison_car_trajet.id_car = car.id_car";

        $where = "";
        $params = [];

        if (isset($_SESSION['droit'], $_SESSION['id_compagnie'])) {
            if ($_SESSION['droit'] === 'Admin') {
                // Admin : cars dont le status_car est NULL ET appartenant à leur compagnie
                $where = " WHERE car.status_car IS NULL AND car.id_compagnie = :compagnie";
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            } elseif ($_SESSION['droit'] === 'Admin_regionale' && isset($_SESSION['ville'])) {
                // Admin régionale : status_car = ville ET id_compagnie = leur compagnie
                $where = " WHERE car.status_car = :ville AND car.id_compagnie = :compagnie";
                $params[':ville'] = $_SESSION['ville'];
                $params[':compagnie'] = $_SESSION['id_compagnie'];
            }
        }

        $fromAndWhere .= " $where ORDER BY car.numero_car, trajet.depart";

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

    public function insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre)
    {
        $localite_user = $_SESSION['ville'];
        $id_compagnie = $_SESSION["id_compagnie"];
        $jourVoyage = $_POST['jourVoyage'];
        // Empêcher que la destination soit la même que la localité de l'utilisateur
        if ($id_destination == $localite_user) {
            return false;
        }

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
            ':date_enregistre' => $jourVoyage,
            ':id_compagnie' => $id_compagnie
        ];

        $result = $this->insertion_update_simples($insert, $params);

        // Remettre à zéro le nombre de places réservées du car programmé
        $update = "UPDATE car SET nbr_place_reserve = 0 WHERE numero_car = :numero_car";
        $this->insertion_update_simples($update, [':numero_car' => $id_care]);

        return $result;
    }


    // Update statut care
    public function updateCareStatus($numero_care, $id_destination)
    {
        $update = "UPDATE car SET status_car = :id_trajet WHERE numero_car = :numero_car";
        $params = [
            ':id_trajet' => $id_destination,
            ':numero_car' => $numero_care
        ];
        return $this->insertion_update_simples($update, $params);
    }
}
