 <?php
    class Add_liste_escale extends Model
    {

        // Le formulaire envoie escales[] (plusieurs lignes ajoutées dynamiquement, "add to
        // row") : on traite chaque ligne indépendamment pour qu'une erreur sur l'une (ex. déjà
        // existante) n'empêche pas l'enregistrement des autres.
        public function savescale()
        {
            $id_compagnie = $_SESSION["id_compagnie"];
            $liste = $_POST['escales'] ?? [];
            if (!is_array($liste)) {
                $liste = [$liste];
            }

            $db = $this->connect();
            $check = $db->prepare("SELECT id_escale FROM escale WHERE escales = :escales AND id_compagnie = :id_compagnie");

            $nbAjoutes = 0;
            $erreurs = [];

            foreach ($liste as $escales) {
                $escales = trim($escales);
                if ($escales === '') {
                    continue;
                }

                $check->execute([':escales' => $escales, ':id_compagnie' => $id_compagnie]);
                if ($check->fetch()) {
                    $erreurs[] = "« $escales » existe déjà.";
                    continue;
                }

                $insertion = $this->insertion_update_simples(
                    "INSERT INTO escale (escales, id_compagnie) VALUES (:escales, :id_compagnie)",
                    [":escales" => $escales, ":id_compagnie" => $id_compagnie]
                );
                if ($insertion) {
                    $nbAjoutes++;
                } else {
                    $erreurs[] = "Échec de l'ajout de « $escales ».";
                }
            }

            if ($nbAjoutes > 0) {
                $this->set_flash($nbAjoutes > 1 ? "$nbAjoutes escales ajoutées avec succès." : "Escale ajoutée avec succès.", 'info');
            }
            foreach ($erreurs as $erreur) {
                $this->set_flash($erreur, "danger");
            }
            if ($nbAjoutes === 0 && count($erreurs) === 0) {
                $this->set_flash("Aucune escale à ajouter.", "danger");
            }
        }

        public function updateEscale($id, $nom)
        {
            $sql = "UPDATE escale SET escales = :nom WHERE id_escale = :id";
            $params = [':nom' => $nom, ':id' => $id];
            // Un Admin ne peut modifier que les escales de sa propre compagnie (IDOR sinon)
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $sql .= " AND id_compagnie = :id_compagnie";
                $params[':id_compagnie'] = $_SESSION['id_compagnie'] ?? null;
            }
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute($params);
        }

        public function deleteEscale($id)
        {
            $sql = "DELETE FROM escale WHERE id_escale = :id";
            $params = [':id' => $id];
            // Un Admin ne peut supprimer que les escales de sa propre compagnie (IDOR sinon)
            if (($_SESSION['droit'] ?? null) !== 'super_admin') {
                $sql .= " AND id_compagnie = :id_compagnie";
                $params[':id_compagnie'] = $_SESSION['id_compagnie'] ?? null;
            }
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute($params);
        }
    }
