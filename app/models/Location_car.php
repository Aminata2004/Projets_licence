<?php
class Location_car extends Model
{
    const STATUTS = ['en_attente', 'valide', 'rejete'];

    // Cars disponibles pour une gare de depart et une periode donnees.
    // - Si la location est pour AUJOURD'HUI et que $limiterAGare est vrai (chef d'escale) :
    //   uniquement les cars normalement affectes a un trajet au depart de cette gare
    //   (liaison_car_trajet -> programmer.idDepart), le car doit etre physiquement present.
    // - Sinon (Admin, ou date future meme pour un chef d'escale) : tous les cars de la
    //   compagnie, sans cette restriction.
    // Dans tous les cas, un car deja reserve (en_attente ou valide) sur une periode qui
    // chevauche celle demandee est exclu.
    public function carsDisponibles($id_agence_depart, $date_depart, $date_retour, $limiterAGare)
    {
        $id_compagnie = $_SESSION['id_compagnie'];

        $sql = "SELECT DISTINCT car.id_car, car.numero_car, car.matriculle
                FROM car
                WHERE car.id_compagnie = :id_compagnie";
        $params = [':id_compagnie' => $id_compagnie];

        if ($limiterAGare) {
            $sql .= " AND car.id_car IN (
                SELECT lct.id_car FROM liaison_car_trajet lct
                INNER JOIN programmer p ON p.idProgrammer = lct.id_trajets
                WHERE p.idDepart = :id_agence_depart
            )";
            $params[':id_agence_depart'] = $id_agence_depart;
        }

        $sql .= " AND car.id_car NOT IN (
            SELECT lc.id_car FROM location_car lc
            WHERE lc.statut IN ('en_attente', 'valide')
              AND lc.date_depart <= :date_retour AND lc.date_retour_prevu >= :date_depart
        )
        ORDER BY car.numero_car";
        $params[':date_depart'] = $date_depart;
        $params[':date_retour'] = $date_retour;

        return $this->FetchSelectCustom($sql, $params);
    }

    public function FetchSelectCustom($query, $params = [])
    {
        $stmt = $this->connect()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Un Admin ne peut louer que les cars de sa propre compagnie (IDOR sinon)
    private function carAppartientCompagnie($id_car)
    {
        if (($_SESSION['droit'] ?? null) === 'super_admin') {
            return true;
        }
        $car = $this->FetchSelectWhere(
            "id_car",
            "car",
            "id_car = :id_car AND id_compagnie = :id_compagnie",
            [":id_car" => $id_car, ":id_compagnie" => $_SESSION['id_compagnie'] ?? null]
        );
        return (bool) $car;
    }

    public function saveLocation()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        $droit        = $_SESSION['droit'] ?? null;

        extract($_POST);

        // Le chef d'escale ne peut louer qu'au depart de sa propre gare, meme si le
        // formulaire est trafique (IDOR sinon, comme pour les depenses locales).
        if ($droit === 'chef_d_escale') {
            $id_agence_depart = $_SESSION['id_agence'];
        } elseif (empty($id_agence_depart)) {
            $this->set_flash("Veuillez choisir la gare de départ.", "danger");
            return false;
        }

        if (empty($destination) || trim($destination) === '') {
            $this->set_flash("Veuillez indiquer la destination.", "danger");
            return false;
        }
        if (empty($id_car)) {
            $this->set_flash("Veuillez choisir un car.", "danger");
            return false;
        }
        if (empty($nom_client) || empty($prenom_client) || empty($telephone_client)) {
            $this->set_flash("Les coordonnées du client (nom, prénom, téléphone) sont obligatoires.", "danger");
            return false;
        }
        if (empty($date_depart) || empty($date_retour_prevu)) {
            $this->set_flash("Veuillez indiquer les dates de départ et de retour prévu.", "danger");
            return false;
        }
        if ($date_retour_prevu < $date_depart) {
            $this->set_flash("La date de retour prévu ne peut pas être avant la date de départ.", "danger");
            return false;
        }
        if (!is_numeric($frais_location) || (float)$frais_location <= 0) {
            $this->set_flash("Le frais de location doit être un nombre positif.", "danger");
            return false;
        }

        if (!$this->carAppartientCompagnie($id_car)) {
            $this->set_flash("Ce car n'appartient pas à votre compagnie.", "danger");
            return false;
        }

        // Re-verification serveur de la disponibilite du car choisi : ne jamais faire
        // confiance a la liste proposee cote client (formulaire trafique, race condition
        // entre deux locations soumises en meme temps...).
        $aujourdhui = date('Y-m-d');
        $limiterAGare = ($droit === 'chef_d_escale') && $date_depart === $aujourdhui;
        $disponibles = $this->carsDisponibles($id_agence_depart, $date_depart, $date_retour_prevu, $limiterAGare);
        $idsDisponibles = array_map(fn($c) => (int)$c->id_car, $disponibles);
        if (!in_array((int)$id_car, $idsDisponibles, true)) {
            $this->set_flash("Ce car n'est plus disponible sur la période demandée. Veuillez en choisir un autre.", "danger");
            return false;
        }

        $statut = in_array($droit, ['Admin', 'super_admin'], true) ? 'valide' : 'en_attente';

        $insertion = $this->insertion_update_simples(
            "INSERT INTO location_car
                (id_compagnie, id_agence_depart, destination, id_car, nom_client, prenom_client,
                 telephone_client, date_depart, date_retour_prevu, frais_location, statut, id_utilisateur)
             VALUES
                (:id_compagnie, :id_agence_depart, :destination, :id_car, :nom_client, :prenom_client,
                 :telephone_client, :date_depart, :date_retour_prevu, :frais_location, :statut, :id_utilisateur)",
            [
                ':id_compagnie'      => $id_compagnie,
                ':id_agence_depart'  => $id_agence_depart,
                ':destination'       => trim($destination),
                ':id_car'            => $id_car,
                ':nom_client'        => trim($nom_client),
                ':prenom_client'     => trim($prenom_client),
                ':telephone_client'  => trim($telephone_client),
                ':date_depart'       => $date_depart,
                ':date_retour_prevu' => $date_retour_prevu,
                ':frais_location'    => $frais_location,
                ':statut'            => $statut,
                ':id_utilisateur'    => $_SESSION['id_utilisateur']
            ]
        );

        if (!$insertion) {
            $this->set_flash("Erreur lors de l'enregistrement de la location.", "danger");
            return false;
        }

        if ($statut === 'valide') {
            $crediteOk = $this->crediterCaisse($id_agence_depart, (float)$frais_location);
            if (!$crediteOk) {
                $this->set_flash(
                    "Location enregistrée, mais aucune caisse n'est ouverte pour cette gare : le montant n'a pas pu être crédité. Ouvrez une caisse puis validez à nouveau si besoin.",
                    "warning"
                );
                return true;
            }
            $this->set_flash("Location enregistrée avec succès et créditée à la caisse.", "success");
        } else {
            $this->set_flash("Location enregistrée avec succès. Elle est en attente de validation par l'administrateur.", "success");
        }
        return true;
    }

    // Credite le montant a la caisse ouverte de la gare de depart. Retourne false si
    // aucune caisse n'est ouverte (l'appelant decide alors quoi faire).
    private function crediterCaisse($id_agence_depart, $montant)
    {
        $caisse = $this->FetchSelectWhere(
            "id_caisse",
            "caisse",
            "id_agence = :id_agence AND status_caisse = 1",
            [":id_agence" => $id_agence_depart]
        );

        if (!$caisse) {
            return false;
        }

        $this->insertion_update_simples(
            "UPDATE caisse SET montant_location = montant_location + :montant WHERE id_caisse = :id_caisse",
            [':montant' => $montant, ':id_caisse' => $caisse->id_caisse]
        );

        return true;
    }

    // Liste des locations visibles selon le role : le chef d'escale ne voit que celles de
    // sa propre gare de depart, l'Admin voit tout.
    public function getLocations()
    {
        $id_compagnie = $_SESSION['id_compagnie'];
        $droit        = $_SESSION['droit'] ?? null;

        $condition = 'l.id_compagnie = :id_compagnie';
        $params    = ['id_compagnie' => $id_compagnie];

        if ($droit === 'chef_d_escale') {
            $condition .= ' AND l.id_agence_depart = :id_agence';
            $params['id_agence'] = $_SESSION['id_agence'];
        }

        return $this->FetchSelectWheres(
            'l.*, a.localite, a.numeroGare, c.numero_car, c.matriculle, u.utilisateurs AS agent',
            'location_car l
                LEFT JOIN agence a ON l.id_agence_depart = a.idAgence
                LEFT JOIN car c ON l.id_car = c.id_car
                LEFT JOIN utilisateur u ON l.id_utilisateur = u.idUser',
            $condition . ' ORDER BY l.date_depart DESC, l.id_location DESC',
            $params
        );
    }

    public function validerLocation($id)
    {
        $id = (int)$id;
        $id_compagnie = $_SESSION['id_compagnie'];

        $location = $this->FetchSelectWhere(
            "*",
            "location_car",
            "id_location = :id AND id_compagnie = :id_compagnie",
            [":id" => $id, ":id_compagnie" => $id_compagnie]
        );

        if (!$location) {
            $this->set_flash("Location introuvable.", "danger");
            return false;
        }
        if ($location->statut !== 'en_attente') {
            $this->set_flash("Cette location a déjà été traitée (validée ou rejetée).", "warning");
            return false;
        }

        $update = $this->insertion_update_simples(
            "UPDATE location_car SET statut = 'valide' WHERE id_location = :id",
            [":id" => $id]
        );
        if (!$update) {
            $this->set_flash("Erreur lors de la validation de la location.", "danger");
            return false;
        }

        $crediteOk = $this->crediterCaisse($location->id_agence_depart, (float)$location->frais_location);
        if (!$crediteOk) {
            $this->set_flash(
                "Location validée, mais aucune caisse n'est ouverte pour cette gare : le montant n'a pas pu être crédité.",
                "warning"
            );
            return true;
        }

        $this->set_flash("Location validée avec succès et créditée à la caisse.", "success");
        return true;
    }

    public function rejeterLocation($id)
    {
        $id = (int)$id;
        $id_compagnie = $_SESSION['id_compagnie'];

        $location = $this->FetchSelectWhere(
            "*",
            "location_car",
            "id_location = :id AND id_compagnie = :id_compagnie",
            [":id" => $id, ":id_compagnie" => $id_compagnie]
        );

        if (!$location) {
            $this->set_flash("Location introuvable.", "danger");
            return false;
        }
        if ($location->statut !== 'en_attente') {
            $this->set_flash("Cette location a déjà été traitée.", "warning");
            return false;
        }

        $update = $this->insertion_update_simples(
            "UPDATE location_car SET statut = 'rejete' WHERE id_location = :id",
            [":id" => $id]
        );

        if ($update) {
            $this->set_flash("Location rejetée avec succès.", "success");
            return true;
        }

        $this->set_flash("Erreur lors du rejet de la location.", "danger");
        return false;
    }
}
