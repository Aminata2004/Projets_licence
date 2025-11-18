 <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Configuration extends Model
    {
        /**
         * Sauvegarde un nouvel utilisateur dans la base de données.
         *
         * Valide les données du formulaire, hache le mot de passe, et insère
         * l'utilisateur dans la table `utilisateur`.
         *
         * Affiche des messages d'erreur ou de succès via SweetAlert.
         */

        private $idUser;


        public function saveUtilisateur()
        {
            $errors = [];
            extract($_POST);
            $status = 1;

            $id_compagnie_session = $_SESSION["id_compagnie"] ?? null;

            // Validation des champs
            if (empty($utilisateurs)) {
                $errors[] = "Le nom de l'utilisateur est obligatoire.";
            }

            if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }

            if (empty($droit)) {
                $errors[] = "Le droit est obligatoire.";
            }

            if (!empty($emailUser) && $this->existe_deja('emailUser', $emailUser, 'utilisateur')) {
                $errors[] = "Cet email est déjà utilisé.";
            }

            if (count($errors) === 0) {
                // Génération d’un mot de passe
                function genererMotDePasse($longueur = 10)
                {
                    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
                    return substr(str_shuffle(str_repeat($caracteres, $longueur)), 0, $longueur);
                }

                $motPasseGenere = genererMotDePasse();
                $motPasseHash = password_hash($motPasseGenere, PASSWORD_DEFAULT);

                $id_agence = $_POST['id_agence'] ?? null;
                $id_compagnie = ($droit === 'Admin') ? ($_POST['id_compagnie'] ?? null) : $id_compagnie_session;

                try {
                    // Utilise une seule connexion PDO
                    $pdo = $this->connect();
                    $pdo->beginTransaction();

                    $insertion = $this->insertion_update_simples(
                        "INSERT INTO utilisateur (utilisateurs, emailUser, motPasse, status, id_agence, id_compagnie, droit) 
                        VALUES (:utilisateurs, :emailUser, :motPasse, :status, :id_agence, :id_compagnie, :droit)",
                        [
                            ":utilisateurs"  => $utilisateurs,
                            ":emailUser"     => $emailUser,
                            ":motPasse"      => $motPasseHash,
                            ":status"        => $status,
                            ":id_agence"     => $id_agence,
                            ":id_compagnie"  => $id_compagnie,
                            ":droit"         => $droit
                        ]
                    );

                    if ($insertion) {
                        // --- Envoi Email ---
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'airbarry94@gmail.com';
                        $mail->Password   = 'jzdmiazwxwjqhikg'; // ⚠️ App Password Gmail recommandé
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        $mail->setFrom('airbarry94@gmail.com', 'Airbarry');
                        $mail->addAddress($emailUser, $utilisateurs);
                        $mail->CharSet = 'UTF-8';
                        $mail->isHTML(true);

                        $mail->Subject = "Création de votre compte utilisateur";
                        $mail->Body = <<<HTML
                        Bonjour <strong>{$utilisateurs}</strong>,<br><br>
                        Votre compte a été créé avec succès.<br>
                        Voici vos identifiants de connexion :<br><br>
                        <b>Email :</b> {$emailUser}<br>
                        <b>Mot de passe :</b> {$motPasseGenere}<br><br>
                         Merci de changer ce mot de passe dès votre première connexion.<br><br>
                        Cordialement,<br>
                        L’équipe .......
                        HTML;

                        $mail->send();

                        // ✅ On valide la transaction
                        $pdo->commit();

                        $this->set_swal(
                            "👤 Utilisateur ajouté !",
                            "L'utilisateur a été ajouté avec succès et son mot de passe lui a été envoyé par email.",
                            "success",
                            "#0d6efd",
                            BASE_URL . "/admin/Configurations/add_utilisateurs"
                        );
                    } else {
                        $pdo->rollBack();
                        $this->set_swal("Erreur", "Échec de l'ajout de l'utilisateur.", "error", "#dc3545");
                    }
                } catch (Exception $e) {
                    // 🚫 rollback sur la même connexion
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $this->set_swal(
                        "Erreur",
                        "L'opération a échoué : " . htmlspecialchars($e->getMessage()),
                        "error",
                        "#dc3545"
                    );
                }
            } else {
                $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
                $this->set_swal("Erreurs détectées", $errorsHtml, "warning", "#ffc107");
            }
        }


        public function findById($id)
        {
            $sql = "SELECT * FROM utilisateur WHERE idUser = ?";
            $result = $this->select_data_table_join_where($sql, [$id]);
            return !empty($result) ? $result[0] : null;
        }
        public function __construct($idUser = null)
        {
            if ($idUser) {
                $this->idUser = $idUser;
            }
        }


        public function userHasPermission($userPermissionName)
        {
            $sql = "SELECT p.nom_permission
                FROM permision p
                JOIN user_permission up ON p.id_permision = up.permission_id
                JOIN utilisateur u ON u.idUser = up.user_id
                WHERE u.idUser = ? AND p.nom_permission = ?";
            $result = $this->select_data_table_join_where($sql, [$this->idUser, $userPermissionName]);
            return count($result) > 0;
        }



        // Récupérer les infos d’un utilisateur
        public function getUserById($idUser)
        {
            $stmt = $this->connect()->prepare("SELECT * FROM utilisateur WHERE idUser = ?");
            $stmt->execute([$idUser]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Mise à jour du mot de passe
        public function updatePassword($idUser, $newPassword)
        {
            $stmt = $this->connect()->prepare("UPDATE utilisateur SET motPasse = ? WHERE idUser = ?");
            return $stmt->execute([$newPassword, $idUser]);
        }


        public function getByEmail($email)
        {
            $sql = "SELECT * FROM utilisateur WHERE emailUser = :emailUser";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':emailUser', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un tableau ou false
        }

        public function updatePassword1($email, $newPassword)
        {
            $sql = "UPDATE utilisateur SET motPasse = :motPasse WHERE emailUser = :emailUser";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':motPasse', $newPassword, PDO::PARAM_STR); // Ici $newPassword est déjà hashé
            $stmt->bindParam(':emailUser', $email, PDO::PARAM_STR);
            return $stmt->execute();
        }
    }
