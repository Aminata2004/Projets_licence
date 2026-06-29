 <?php
    class Compagnie extends Model
    {

        /**
         * @param array|null $file  le tableau $_FILES['logo'] ou null
         */
        public function saveCompagnie(?array $file = null): void
        {
            $errors = [];
            extract($_POST);

            if (empty($nom_compagnie))  $errors[] = "Le nom de la compagnie est obligatoire.";
            if (empty($libele))         $errors[] = "Le libellé est obligatoire.";
            if (empty($slogant))        $errors[] = "Le slogan est obligatoire.";

            // Traitement du fichier logo
            $logoNameInDb = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $mime = mime_content_type($file['tmp_name']);
                $allowed = ['image/png', 'image/jpeg', 'image/webp'];
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = "Logo : format non autorisé (png, jpg, webp uniquement).";
                }
                if ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Logo : trop grand (max 2Mo).";
                }

                if (!$errors) {
                    $ext = ($mime === 'image/png') ? '.png' : (($mime === 'image/webp') ? '.webp' : '.jpg');
                    $uploadDir = ROOT . '/public/images/logos';

                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

                    $logoNameInDb = uniqid('logo_', true) . $ext;
                    $destPath = $uploadDir . '/' . $logoNameInDb;
                    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                        $errors[] = "Échec de l'enregistrement du logo.";
                    }
                }
            } else {
                $errors[] = "Logo manquant ou corrompu.";
            }

            // Insertion
            if (!$errors) {
                $sql = "INSERT INTO compagnie (nom_compagnie, libele, slogant, logo)
                VALUES (:nom_compagnie, :libele, :slogant, :logo)";

                $ok = $this->insertion_update_simples($sql, [
                    ':nom_compagnie' => $nom_compagnie,
                    ':libele'        => $libele,
                    ':slogant'       => $slogant,
                    ':logo'          => $logoNameInDb
                ]);

                if ($ok) {
                    $this->set_flash("Compagnie ajoutée avec succès.", "success");
                    header("Location: " . BASE_URL . "/admin/Compagnies");
                    exit;
                }
            } else {
                $this->set_flash(implode('<br>', array_map('htmlspecialchars', $errors)), "danger");
                header("Location: " . BASE_URL . "/admin/Compagnies");
                exit;
            }
        }


        // function pour la modification
        public function editCompagnie($data)
        {
            $req = "UPDATE compagnie 
           SET nom_compagnie =:nom_compagnie, 
               libele=:libele,
               slogant=:slogant,
               logo=:logo
                WHERE id_compagnie=:id_compagnie";

            $params = [
                ":nom_compagnie" => $data['nom_compagnie'],
                ":libele" => $data['libele'],
                ':slogant' => $data['slogant'],
                ':logo' => $data['logo'],
                ':id_compagnie' => $data['id_compagnie'],
            ];

            $modification = $this->insertion_update_simples($req, $params);

            if ($modification == true) {
                $this->set_flash("Modification faite avec succès", "success");
            }
        }

        public function editPlace($data)
        {
            $req = "UPDATE place_minumale 
            SET place_minumale = :place_minumale 
            WHERE id_place_minumale = :id_place_minumale";

            $params = [
                ":place_minumale" => $data['place_minumale'],
                ":id_place_minumale" => $data['id_place_minumale']
            ];

            $modification = $this->insertion_update_simples($req, $params);

            if ($modification) {
                $this->set_flash("Modification faite avec succès", "info");
            }
        }

        public function getById($id)
        {
            $stmt = $this->connect()->prepare("SELECT * FROM compagnie WHERE id_compagnie = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
    }
