 <?php
    class Chauffeurs_car extends Model
    {

        public function saveChauffeur()
        {
            // Récupération sécurisée des données du formulaire
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $errors = [];
            $id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($nom_prenom)) {
                $errors[] = "Le nom du chauffeur est obligatoire.";
            }

            if (empty($numero)) {
                $errors[] = "Le numero est obligatoire.";
            } else {
                $numero = trim($numero);
                if (strlen($numero) !== 8) {
                    $errors[] = "Le numéro de téléphone doit contenir exactement 8 caractères.";
                } elseif (!preg_match('/^[6789]\d{7}$/', $numero)) {
                    $errors[] = "Le numéro de téléphone doit commencer par 6, 7, 8 ou 9 et ne contenir que des chiffres.";
                }
            }

            if (empty($id_car)) {
                $errors[] = "Le car qu il conduit est obligatoire.";
            }

            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {
                $insertion = $this->insertion_update_simples(
                    "INSERT INTO chauffeur (nom_prenom, numero, id_car,id_compagnie) 
        VALUES (:nom_prenom, :numero, :id_car,:id_compagnie)",
                    [
                        ":nom_prenom" => $nom_prenom,
                        ":numero" => $numero,
                        ":id_car"  => $id_car,
                        ":id_compagnie" => $id_compagnie
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


        public function updateChauffeur($id, $data)
        {
            // Un Admin ne peut modifier que les chauffeurs de sa propre compagnie (IDOR sinon)
            $sql = "UPDATE chauffeur SET nom_prenom = :nom, numero = :numero, id_car = :id_car WHERE id_chauffeur = :id";
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $sql .= " AND id_compagnie = :id_compagnie";
            }
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':nom', $data['nom_prenom']);
            $stmt->bindParam(':numero', $data['numero']);
            $stmt->bindParam(':id_car', $data['id_car']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $stmt->bindValue(':id_compagnie', $_SESSION['id_compagnie'] ?? null);
            }
            return $stmt->execute();
        }

         public function deleteChauffeur($id) {
        // Un Admin ne peut supprimer que les chauffeurs de sa propre compagnie (IDOR sinon)
        $sql = "DELETE FROM chauffeur WHERE id_chauffeur = :id";
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
