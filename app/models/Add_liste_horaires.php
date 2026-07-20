 <?php
    class Add_liste_horaires extends Model
    {

        public function saveHoraire()
        {
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $errors = [];
            $id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($heuredepart)) {
                $errors[] = "L'heure de depart  est obligatoire.";
            }
            $db = $this->connect();
            // Vérifier si la combinaison localité + numéroGare existe
            $check = $db->prepare("SELECT id_heure FROM horaire WHERE  heuredepart = :heuredepart AND id_compagnie = :id_compagnie");
            $check->execute([
                ':heuredepart' => $heuredepart,
                ':id_compagnie' => $id_compagnie
            ]);
            $existe = $check->fetch();

            if ($existe) {
                $errors[] = "Cet heure de depart existe déjà.";
            }
            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {

                $insertion = $this->insertion_update_simples(
                    "INSERT INTO horaire 
                ( heuredepart,id_compagnie ) VALUES(:heuredepart,:id_compagnie)",
                    [
                        ":heuredepart" => $heuredepart,
                        ":id_compagnie" => $id_compagnie
                    ]
                );
                if ($insertion == true) {
                    $this->set_flash('Heure ajouter avec succes', 'info');
                    //  header("Location: " . $_SERVER['PHP_SELF']); // Redirection vers la même page
                    //  exit();
                } else {
                    $this->set_flash('heure non ajouter');
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    $this->set_flash($error, "danger");
                }
            }
        }
        public function editHoraire($data)
        {
            $req = "UPDATE horaire SET heuredepart = :heuredepart WHERE id_heure = :id_heure";
            $params = [
                ":heuredepart" => $data['heuredepart'],
                ":id_heure" => $data['id_heure'],
            ];
            // Un Admin ne peut modifier que les horaires de sa propre compagnie (IDOR sinon)
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $req .= " AND id_compagnie = :id_compagnie";
                $params[":id_compagnie"] = $_SESSION['id_compagnie'] ?? null;
            }

            $modification = $this->insertion_update_simples($req, $params);

            if ($modification == true) {
                $this->set_flash("Heure modifiée avec succès", "success");
            } else {
                $this->set_flash("Échec de la modification de l'heure", "danger");
            }
        }

        public function deleteHoraire($id_heure)
        {
            $req = "DELETE FROM horaire WHERE id_heure = :id_heure";
            $params = [":id_heure" => $id_heure];
            // Un Admin ne peut supprimer que les horaires de sa propre compagnie (IDOR sinon)
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $req .= " AND id_compagnie = :id_compagnie";
                $params[":id_compagnie"] = $_SESSION['id_compagnie'] ?? null;
            }
            $suppression = $this->insertion_update_simples($req, $params);

            if ($suppression == true) {
                $this->set_flash("Heure supprimée avec succès", "success");
            } else {
                $this->set_flash("Échec de la suppression de l'heure", "danger");
            }
        }

        public function savePermission()
        {
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $errors = [];
            //$id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($nom_permission)) {
                $errors[] = "Permission est obligatoire.";
            }
            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {

                $insertion = $this->insertion_update_simples(
                    "INSERT INTO permision 
                ( nom_permission ) VALUES(:nom_permission)",
                    [
                        ":nom_permission" => $nom_permission

                    ]
                );
                if ($insertion == true) {
                    $this->set_flash('Permission ajouter avec succes', 'info');
                    //  header("Location: " . $_SERVER['PHP_SELF']); // Redirection vers la même page
                    //  exit();
                } else {
                    $this->set_flash('heure non ajouter');
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    $this->set_flash($error, "danger");
                }
            }
        }
    }
