 <?php
    class Cars_chauffeur extends Model
    {

        // Le formulaire envoie numero_car[]/matriculle[]/nbr_place[] (plusieurs lignes ajoutées
        // dynamiquement, "add to row", les 3 champs alignés par index) : chaque ligne est
        // validée et insérée indépendamment, une erreur sur l'une n'empêche pas les autres.
        public function saveCare()
        {
            $id_compagnie = $_SESSION["id_compagnie"];
            $numeros = $_POST['numero_car'] ?? [];
            $matriculles = $_POST['matriculle'] ?? [];
            $nbrPlaces = $_POST['nbr_place'] ?? [];
            if (!is_array($numeros)) {
                $numeros = [$numeros];
                $matriculles = [$matriculles];
                $nbrPlaces = [$nbrPlaces];
            }

            $nbAjoutes = 0;
            $erreurs = [];

            foreach ($numeros as $i => $numero_car) {
                $numero_car = trim($numero_car);
                $matriculle = trim($matriculles[$i] ?? '');
                $nbr_place = trim($nbrPlaces[$i] ?? '');

                // Ligne vide (pas remplie par l'agent) : ignorée silencieusement, ce n'est pas
                // une erreur en soi si d'autres lignes sont valides.
                if ($numero_car === '' && $matriculle === '' && $nbr_place === '') {
                    continue;
                }

                if (empty($numero_car)) {
                    $erreurs[] = "Ligne " . ($i + 1) . " : le numéro du car est obligatoire.";
                    continue;
                }
                if (empty($matriculle)) {
                    $erreurs[] = "Ligne " . ($i + 1) . " : le matricule est obligatoire.";
                    continue;
                }
                if (empty($nbr_place)) {
                    $erreurs[] = "Ligne " . ($i + 1) . " : le nombre de places est obligatoire.";
                    continue;
                }
                if (!is_numeric($nbr_place)) {
                    $erreurs[] = "Ligne " . ($i + 1) . " : le nombre de places doit être un nombre.";
                    continue;
                }
                if ($this->existe_deja('numero_car', $numero_car, 'car')) {
                    $erreurs[] = "Le car « $numero_car » existe déjà.";
                    continue;
                }

                $insertion = $this->insertion_update_simples(
                    "INSERT INTO car (numero_car, matriculle, nbr_place, nbr_place_reserve, programmer_car, id_compagnie)
        VALUES (:numero_car, :matriculle, :nbr_place, :nbr_place_reserve, :programmer_car, :id_compagnie)",
                    [
                        ":numero_car" => $numero_car,
                        ":matriculle" => $matriculle,
                        ":nbr_place"  => $nbr_place,
                        ":programmer_car" => "off",
                        ":id_compagnie" => $id_compagnie,
                        ":nbr_place_reserve" => 0
                    ]
                );

                if ($insertion) {
                    $nbAjoutes++;
                } else {
                    $erreurs[] = "Le car « $numero_car » n'a pas pu être ajouté.";
                }
            }

            if ($nbAjoutes > 0) {
                $this->set_flash($nbAjoutes > 1 ? "$nbAjoutes cars ajoutés avec succès." : "Car ajouté avec succès.", 'info');
            }
            foreach ($erreurs as $erreur) {
                $this->set_flash($erreur, "danger");
            }
            if ($nbAjoutes === 0 && count($erreurs) === 0) {
                $this->set_flash("Aucun car à ajouter.", "danger");
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
