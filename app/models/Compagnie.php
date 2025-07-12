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
                $allowed = ['image/png', 'image/jpeg'];
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = "Logo : format non autorisé.";
                }
                if ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Logo : trop grand (max 2Mo).";
                }

                if (!$errors) {
                    $ext = $mime === 'image/png' ? '.png' : '.jpg';
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


                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            Swal.fire({
                                title: "🎉 Bravo !",
                                text: "Compagnie ajoutée avec succes.",
                                icon: "success",
                                iconColor: "#0d6efd", // ← couleur primary Bootstrap
                                confirmButtonColor: "#0d6efd", // ← bouton OK bleu
                                background: "#f0f8ff", // couleur de fond douce, proche du bleu
                                confirmButtonText: "OK",
                                showClass: {
                                    popup: "animate__animated animate__zoomIn"
                                },
                                hideClass: {
                                    popup: "animate__animated animate__fadeOut"
                                }
                            }).then(() => {
                                window.location.href = "' . BASE_URL . '/Compagnies";
                            });
                        });
                    </script>';
                }
            } else {

                echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    title: "Erreur",
                    html: "' . implode('<br>', array_map('htmlspecialchars', $errors)) . '",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
        </script>';
            }
        }



        // function pour la modification
        public function editCompagnie($data)
        {
            $req = "UPDATE compagnie 
           SET nom_compagnie =:nom_compagnie, 
               libele=:libele,
               slogant=:slogant
                WHERE id_compagnie=:id_compagnie";

            $params = [
                ":nom_compagnie" => $data['nom_compagnie'],
                ":libele" => $data['libele'],
                ':slogant' => $data['slogant'],
                ':id_compagnie' => $data['id_compagnie'],
            ];

            $modification = $this->insertion_update_simples($req, $params);

            if ($modification == true) {
                $this->set_flash("modification faite avec ssssss", "primary");
                // $this->redirect("compagnies");
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

            if ($modification === true) {
                $this->set_flash("Modification faite avec succès", "primary");
            }
        }
    }
