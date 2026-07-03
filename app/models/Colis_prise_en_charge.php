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
                    // Si aucun numéro WhatsApp n'est précisé, on suppose que c'est le même que le numéro classique.
                    $whatsapp_exp = trim($whatsapp_exp ?? '') !== '' ? trim($whatsapp_exp) : $numero_exp;

                    $stmt1 = $pdo->prepare("
                INSERT INTO expediteurs (expediteur, numero_exp, whatsapp_exp, email_exp)
                VALUES (:expediteur, :num_exp, :whatsapp_exp, :email_exp)
            ");
                    $stmt1->execute([
                        ":expediteur" => $expediteur,
                        ":num_exp" => $numero_exp,
                        ":whatsapp_exp" => $whatsapp_exp,
                        ":email_exp" => $email_exp
                    ]);
                    $id_expediteur = $pdo->lastInsertId();

                    // Insertion destinataire
                    // Si aucun numéro WhatsApp n'est précisé, on suppose que c'est le même que le numéro classique.
                    $whatsapp_dest = trim($whatsapp_dest ?? '') !== '' ? trim($whatsapp_dest) : $numero_dest;

                    $stmt2 = $pdo->prepare("
                INSERT INTO destinataires (destinataire, numero_dest, whatsapp_dest, email_dest, id_exp)
                VALUES (:destinataire, :numero_dest, :whatsapp_dest, :email_dest, :id_exp)
            ");
                    $stmt2->execute([
                        ":destinataire" => $destinataire,
                        ":numero_dest" => $numero_dest,
                        ":whatsapp_dest" => $whatsapp_dest,
                        ":email_dest" => $email_dest,
                        ":id_exp" => $id_expediteur
                    ]);
                    $id_destinataire = $pdo->lastInsertId();
                    $Provenace = $_SESSION['ville'];

                    // Insertion colis
                    $stmt3 = $pdo->prepare("
                INSERT INTO colis 
                (nom_colis, nature, provient_de, id_agence, valeur, fraix_transaction, id_expediteur, id_destinataire, id_utilisateur, date_enregistrement, code_colis, num_gare, status, id_compagnie)
                VALUES 
                (:nom_colis, :nature, :provient_de, :id_agence, :valeur, :fraix_transaction, :id_expediteur, :id_destinataire, :id_utilisateur, :date_enregistrement, :code_colis, :num_gare,  :status, :id_compagnie)
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
                        ":num_gare" => $_SESSION['numero_gare'] ?? null, // Assurez-vous que cette variable est définie
                        ":status" => $status,
                        ":id_compagnie" => $id_compagnie
                    ]);

                    // === Alimentation de la caisse ===
                    $stmt = $pdo->prepare("
                        SELECT c.id_caisse, c.montant_colis
                        FROM caisse c
                        INNER JOIN agence a ON c.id_agence = a.idAgence
                        WHERE c.id_compagnie = :id_compagnie
                          AND a.localite = :ville
                          AND a.numeroGare = :numeroGare
                          AND c.status_caisse = 1
                        LIMIT 1
                    ");
                    $stmt->execute([
                        ':id_compagnie' => $_SESSION['id_compagnie'],
                        ':ville'        => $_SESSION['ville'],
                        ':numeroGare'   => $_SESSION['numero_gare']
                    ]);
                    $caisse = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($caisse) {
                        $stmtUpdate = $pdo->prepare("
                            UPDATE caisse
                            SET montant_colis = montant_colis + :montant
                            WHERE id_caisse = :id_caisse
                        ");
                        $stmtUpdate->execute([
                            ':montant'   => $fraix_transaction,
                            ':id_caisse' => $caisse['id_caisse']
                        ]);
                    } else {
                        $pdo->rollBack();
                        $this->set_swal(
                            "Erreur caisse",
                            "Opération bloquée : Aucune caisse ouverte pour cette gare. Veuillez ouvrir une caisse d'abord.",
                            "error",
                            "#dc3545"
                        );
                        return false;
                    }
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

        public function updateColis()
        {
            $id_colis = (int)($_POST['id_colis'] ?? 0);
            $id_compagnie = $_SESSION['id_compagnie'] ?? null;

            $nom_colis         = trim($_POST['nom_colis'] ?? '');
            $nature            = trim($_POST['nature'] ?? '');
            $destination       = $_POST['destination'] ?? '';
            $valeur            = $_POST['valeur'] ?? '';
            $fraix_transaction = $_POST['fraix_transaction'] ?? '';

            $errors = [];
            if ($id_colis <= 0) $errors[] = "Colis introuvable.";
            if (empty($nom_colis)) $errors[] = "Le nom du colis est obligatoire.";
            if (empty($nature)) $errors[] = "La nature du colis est obligatoire.";
            if (empty($destination)) $errors[] = "La destination est obligatoire.";
            if ($valeur === '' || !is_numeric($valeur)) $errors[] = "La valeur du colis est obligatoire.";
            if ($fraix_transaction === '' || !is_numeric($fraix_transaction)) $errors[] = "Les frais de transaction sont obligatoires.";

            if (count($errors) > 0) {
                $this->set_swal("Erreurs détectées", implode("<br>", array_map('htmlspecialchars', $errors)), "warning", "#ffc107");
                return;
            }

            $sql = "UPDATE colis
                    SET nom_colis = :nom_colis, nature = :nature, id_agence = :destination,
                        valeur = :valeur, fraix_transaction = :fraix_transaction
                    WHERE id_colis = :id_colis AND id_compagnie = :id_compagnie";

            $stmt = $this->connect()->prepare($sql);
            $ok = $stmt->execute([
                ':nom_colis'         => $nom_colis,
                ':nature'            => $nature,
                ':destination'       => $destination,
                ':valeur'            => $valeur,
                ':fraix_transaction' => $fraix_transaction,
                ':id_colis'          => $id_colis,
                ':id_compagnie'      => $id_compagnie,
            ]);

            if ($ok && $stmt->rowCount() > 0) {
                $this->set_swal("Colis modifié !", "Les informations du colis ont été mises à jour.", "success", "#0d6efd");
            } else {
                $this->set_swal("Erreur", "Aucune modification effectuée (colis introuvable).", "error", "#dc3545");
            }
        }
    }
