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

            // Un chauffeur conduit soit un car, soit un camion (jamais les deux) : la
            // checkbox "est_camion" du formulaire determine lequel des deux champs fait foi.
            $estCamion = isset($_POST['est_camion']) && $_POST['est_camion'] === '1';
            $id_car = $estCamion ? null : ($_POST['id_car'] ?? null);
            $id_camion = $estCamion ? ($_POST['id_camion'] ?? null) : null;
            $type_vehicule = $estCamion ? 'camion' : 'car';

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

            if ($estCamion) {
                if (empty($id_camion)) {
                    $errors[] = "Le camion qu'il conduit est obligatoire.";
                }
            } else {
                if (empty($id_car)) {
                    $errors[] = "Le car qu il conduit est obligatoire.";
                }
            }

            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {
                $photoPath = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'public/uploads/profiles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['photo']['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                        // Le umask du process PHP peut produire un fichier non lisible par
                        // le serveur web (403) selon l'hébergeur ; on force donc 0644.
                        chmod($targetFile, 0644);
                        $photoPath = $fileName;
                    }
                }

                // insertion_update_simples_insert_id() (pas la variante sans "_insert_id") :
                // necessaire pour recuperer l'id du chauffeur cree sur LA MEME connexion PDO
                // (cf. le meme correctif deja applique dans Configuration::saveUtilisateur(),
                // sinon lastInsertId() sur une connexion separee vaudrait toujours 0), afin de
                // creer sa fiche employe (module Salaire) juste apres.
                $result = $this->insertion_update_simples_insert_id(
                    "INSERT INTO chauffeur (nom_prenom, numero, id_car, id_camion, type_vehicule, id_compagnie, photo)
        VALUES (:nom_prenom, :numero, :id_car, :id_camion, :type_vehicule, :id_compagnie, :photo)",
                    [
                        ":nom_prenom" => $nom_prenom,
                        ":numero" => $numero,
                        ":id_car"  => $id_car,
                        ":id_camion" => $id_camion,
                        ":type_vehicule" => $type_vehicule,
                        ":id_compagnie" => $id_compagnie,
                        ":photo" => $photoPath
                    ]
                );
                $idNouveauChauffeur = (int) ($result['lastInsertId'] ?? 0);

                if ($idNouveauChauffeur > 0) {
                    (new Employe())->creerEmployePourChauffeur($idNouveauChauffeur, $id_compagnie);
                    $this->set_flash("Chauffeur ajouté avec succès", "info");
                } else {
                    $this->set_flash("Erreur : le chauffeur n'a pas pu être ajouté");
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
            $sql = "UPDATE chauffeur SET nom_prenom = :nom, numero = :numero, id_car = :id_car, id_camion = :id_camion, type_vehicule = :type_vehicule WHERE id_chauffeur = :id";
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $sql .= " AND id_compagnie = :id_compagnie";
            }
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':nom', $data['nom_prenom']);
            $stmt->bindParam(':numero', $data['numero']);
            $stmt->bindParam(':id_car', $data['id_car']);
            $stmt->bindParam(':id_camion', $data['id_camion']);
            $stmt->bindParam(':type_vehicule', $data['type_vehicule']);
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
