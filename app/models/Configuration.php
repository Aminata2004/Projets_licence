 <?php

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

        const MOT_DE_PASSE_PAR_DEFAUT = '123456';

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
                // Mot de passe par défaut identique pour tous les nouveaux comptes,
                // à communiquer à l'utilisateur (pas d'envoi d'email) : il le change
                // lui-même à sa première connexion.
                $motPasseHash = password_hash(self::MOT_DE_PASSE_PAR_DEFAUT, PASSWORD_DEFAULT);

                $id_agence = $_POST['id_agence'] ?? null;
                $id_compagnie = ($droit === 'Admin') ? ($_POST['id_compagnie'] ?? null) : $id_compagnie_session;
                $profile = ($droit === 'Utilisateur') ? ($_POST['profile'] ?? null) : null;

                try {
                    // Utilise une seule connexion PDO
                    $pdo = $this->connect();
                    $pdo->beginTransaction();

                    $insertion = $this->insertion_update_simples(
                        "INSERT INTO utilisateur (utilisateurs, emailUser, motPasse, status, id_agence, id_compagnie, droit, profile)
                        VALUES (:utilisateurs, :emailUser, :motPasse, :status, :id_agence, :id_compagnie, :droit, :profile)",
                        [
                            ":utilisateurs"  => $utilisateurs,
                            ":emailUser"     => $emailUser,
                            ":motPasse"      => $motPasseHash,
                            ":status"        => $status,
                            ":id_agence"     => $id_agence,
                            ":id_compagnie"  => $id_compagnie,
                            ":droit"         => $droit,
                            ":profile"       => $profile
                        ]
                    );

                    if ($insertion) {
                        $pdo->commit();

                        $this->set_swal(
                            "👤 Utilisateur ajouté !",
                            "L'utilisateur a été ajouté avec succès. Mot de passe par défaut : " . self::MOT_DE_PASSE_PAR_DEFAUT . " (à communiquer à l'utilisateur, qui pourra le modifier après sa première connexion).",
                            "success",
                            "#0d6efd",
                            BASE_URL . "/admin/Configurations/add_utilisateurs"
                        );
                    } else {
                        $pdo->rollBack();
                        $this->set_swal("Erreur", "Échec de l'ajout de l'utilisateur.", "error", "#dc3545");
                    }
                } catch (Throwable $e) {
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
            // Mode support technique : le super_admin impersonné voit tout comme un admin normal
            if (($_SESSION['super_admin_droit'] ?? null) === 'super_admin') {
                return true;
            }

            // Super_admin direct : seulement l'accès à Configuration et à l'onglet Utilisateur
            if (($_SESSION['droit'] ?? null) === 'super_admin'
                && in_array($userPermissionName, ['Configuration_apercu', 'utilisateur_apercu'], true)) {
                return true;
            }

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

        // Supprime définitivement un compte utilisateur et ses données propres (permissions,
        // historique de connexion, jetons de réinitialisation). Les billets/colis/dépenses déjà
        // enregistrés par ce compte sont conservés (historique comptable) mais détachés : leur
        // référence à l'utilisateur passe à NULL plutôt que d'être supprimés avec lui.
        public function deleteUtilisateur($idUser)
        {
            $utilisateur = $this->getUserById($idUser);
            if (!$utilisateur) {
                return false;
            }

            $pdo = $this->connect();
            $pdo->beginTransaction();

            try {
                $pdo->prepare("UPDATE billets SET idUser = NULL WHERE idUser = :id")
                    ->execute([':id' => $idUser]);
                $pdo->prepare("UPDATE colis SET id_utilisateur = NULL WHERE id_utilisateur = :id")
                    ->execute([':id' => $idUser]);
                $pdo->prepare("UPDATE depense SET id_utilisateur = NULL WHERE id_utilisateur = :id")
                    ->execute([':id' => $idUser]);
                $pdo->prepare("DELETE FROM user_permission WHERE user_id = :id")
                    ->execute([':id' => $idUser]);
                $pdo->prepare("DELETE FROM login_attempts WHERE identifiant = :email")
                    ->execute([':email' => $utilisateur['emailUser']]);
                $pdo->prepare("DELETE FROM password_resets WHERE email = :email")
                    ->execute([':email' => $utilisateur['emailUser']]);
                $pdo->prepare("DELETE FROM utilisateur WHERE idUser = :id")
                    ->execute([':id' => $idUser]);

                $pdo->commit();
                return true;
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                return false;
            }
        }
    }
