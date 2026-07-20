 <?php
    class Cars_chauffeur extends Model
    {

        public function saveCare()
        {
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $errors = [];
            $programmer_car = "off";
            $nbr_place_reserve = 0;
            $id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($numero_car)) {
                $errors[] = "Le numéro du car est obligatoire.";
            }

            if (empty($matriculle)) {
                $errors[] = "Le matricule est obligatoire.";
            }

            if (empty($nbr_place)) {
                $errors[] = "Le nombre de places est obligatoire.";
            } elseif (!is_numeric($nbr_place)) {
                $errors[] = "Le nombre de places doit être un nombre.";
            }

            // Vérification d'unicité du numéro du car
            if ($this->existe_deja('numero_car', $numero_car, 'car')) {
                $errors[] = "Ce numéro de car existe déjà.";
            }

            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {
                $insertion = $this->insertion_update_simples(
                    "INSERT INTO car (numero_car, matriculle, nbr_place, nbr_place_reserve, programmer_car, id_compagnie) 
        VALUES (:numero_car, :matriculle, :nbr_place, :nbr_place_reserve,:programmer_car,:id_compagnie)",
                    [
                        ":numero_car" => $numero_car,
                        ":matriculle" => $matriculle,
                        ":nbr_place"  => $nbr_place,
                        ":programmer_car" => $programmer_car,
                        ":id_compagnie" => $id_compagnie,
                        ":nbr_place_reserve" => $nbr_place_reserve

                    ]
                );

                if ($insertion) {
                    $this->set_flash("Car ajouté avec succès", "info");
                } else {
                    $this->set_flash("Erreur : le car n'a pas pu être ajouté");
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    $this->set_flash($error, "danger");
                }
            }
        }

        public function updateCar($id, $data) {
        // Un Admin ne peut modifier que les cars de sa propre compagnie (IDOR sinon)
        $sql = "UPDATE car SET numero_car = :numero, matriculle = :matriculle, nbr_place = :places WHERE id_car = :id";
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $sql .= " AND id_compagnie = :id_compagnie";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':numero', $data['numero_car']);
        $stmt->bindParam(':matriculle', $data['matricule']);
        $stmt->bindParam(':places', $data['nbr_place']);
        $stmt->bindParam(':id', $id);
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $stmt->bindValue(':id_compagnie', $_SESSION['id_compagnie'] ?? null);
        }
        return $stmt->execute();
    }

        public function deleteCar($id) {
        // Un Admin ne peut supprimer que les cars de sa propre compagnie (IDOR sinon)
        $sql = "DELETE FROM car WHERE id_car = :id";
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $sql .= " AND id_compagnie = :id_compagnie";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $stmt->bindValue(':id_compagnie', $_SESSION['id_compagnie'] ?? null);
        }
        return $stmt->execute();
    }

    }
