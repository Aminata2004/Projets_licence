 <?php
    class Colis_prise_en_charge extends Model
    {
        public function saveColis()
        {
            extract($_POST);
            $errors = [];

            $id_compagnie = $_SESSION["id_compagnie"] ?? null;
            $id_utilisateur = $_SESSION["id_user"] ?? null;
            $date = date('Ymd');
            $status = "enregistre"; // statut par défaut
            $id_utilisateur =  $_SESSION['id_utilisateur'];

            // Champs obligatoires (expéditeur)
            if (empty($expediteur)) $errors[] = "Le nom de l'expéditeur est obligatoire.";
            if (empty($numero_exp)) $errors[] = "Le numéro de l'expéditeur est obligatoire.";
            if (empty($email_exp)) $errors[] = "L'email de l'expéditeur est obligatoire.";

            // Champs obligatoires (destinataire)
            if (empty($destinataire)) $errors[] = "Le nom du destinataire est obligatoire.";
            if (empty($numero_dest)) $errors[] = "Le numéro du destinataire est obligatoire.";
            if (empty($email_dest)) $errors[] = "L'email du destinataire est obligatoire.";

            // Champs obligatoires (colis)
            if (empty($nom_colis)) $errors[] = "Le nom du colis est obligatoire.";
            if (empty($nature)) $errors[] = "La nature du colis est obligatoire.";

            if (empty($destination)) $errors[] = "La destination est obligatoire.";
            if (empty($valeur)) $errors[] = "La valeur du colis est obligatoire.";
            if (empty($fraix_transaction)) $errors[] = "Les frais de transaction sont obligatoires.";
            if (empty($code_colis)) $errors[] = "Le code colis est obligatoire.";

            if (count($errors) === 0) {

                try {
                    // Connexion
                    $pdo = $this->connect();
                    $pdo->beginTransaction();

                    // Insertion expéditeur
                    $stmt1 = $pdo->prepare("
                INSERT INTO expediteurs (expediteur, numero_exp, email_exp) 
                VALUES (:expediteur, :num_exp, :email_exp)
            ");
                    $stmt1->execute([
                        ":expediteur" => $expediteur,
                        ":num_exp" => $numero_exp,
                        ":email_exp" => $email_exp
                    ]);
                    $id_expediteur = $pdo->lastInsertId();

                    // Insertion destinataire
                    $stmt2 = $pdo->prepare("
                INSERT INTO destinataires (destinataire, numero_dest, email_dest, id_exp) 
                VALUES (:destinataire, :numero_dest, :email_dest, :id_exp)
            ");
                    $stmt2->execute([
                        ":destinataire" => $destinataire,
                        ":numero_dest" => $numero_dest,
                        ":email_dest" => $email_dest,
                        ":id_exp" => $id_expediteur
                    ]);
                    $id_destinataire = $pdo->lastInsertId();
                    $Provenace = $_SESSION['ville'];

                    // Insertion colis
                    $stmt3 = $pdo->prepare("
                INSERT INTO colis 
                (nom_colis, nature, provient_de, id_agence, valeur, fraix_transaction, id_expediteur, id_destinataire, id_utilisateur, date_enregistrement, code_colis, status, id_compagnie)
                VALUES 
                (:nom_colis, :nature, :provient_de, :id_agence, :valeur, :fraix_transaction, :id_expediteur, :id_destinataire, :id_utilisateur, :date_enregistrement, :code_colis, :status, :id_compagnie)
            ");
                    $stmt3->execute([
                        ":nom_colis" => $nom_colis,
                        ":nature" => $nature,
                        ":provient_de" => $Provenace,
                        ":id_agence" => $destination,
                        ":valeur" => $valeur,
                        ":fraix_transaction" => $fraix_transaction,
                        ":id_expediteur" => $id_expediteur,
                        ":id_destinataire" => $id_destinataire,
                        ":id_utilisateur" => $id_utilisateur,
                        ":date_enregistrement" => $date,
                        ":code_colis" => $code_colis,
                        ":status" => $status,
                        ":id_compagnie" => $id_compagnie
                    ]);
                    $pdo->commit();

                    // ✅ Notification de succès
                    $this->set_swal(
                        "🎁 Colis ajouté avec succès !",
                        "Le colis a été enregistré avec succès dans le système.",
                        "success",
                        "#0d6efd" // Couleur bleue (primaire)
                    );
                } catch (Exception $e) {
                    $pdo->rollBack();

                    $this->set_swal(
                        "Erreur",
                        "Erreur lors de l'enregistrement du colis : " . htmlspecialchars($e->getMessage()),
                        "error",
                        "#dc3545"
                    );
                }
            } else {
                $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
                $this->set_swal(
                    "Erreurs détectées",
                    $errorsHtml,
                    "warning",
                    "#ffc107"
                );
            }
        }
    }
