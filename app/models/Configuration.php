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

            // Un chef d'escale ou un simple Utilisateur doit être rattaché à une gare :
            // leurs opérations (billets, colis, caisse) sont scopées dessus, un compte sans
            // gare ne peut rien faire de cohérent. Seuls un Admin et un PDG (rattachés à une
            // compagnie entière, pas une gare précise) échappent à cette règle.
            if (!empty($droit) && !in_array($droit, ['Admin', 'PDG'], true) && empty($_POST['id_agence'])) {
                $errors[] = "La gare est obligatoire pour ce type de compte.";
            }

            if (!empty($emailUser) && $this->existe_deja('emailUser', $emailUser, 'utilisateur')) {
                $errors[] = "Cet email est déjà utilisé.";
            }

            $telephone = trim($_POST['telephone'] ?? '');
            if ($telephone !== '' && !preg_match('/^[0-9+\s.\-]{6,20}$/', $telephone)) {
                $errors[] = "Le numéro de téléphone n'est pas valide.";
            }

            if (count($errors) === 0) {
                // Mot de passe par défaut identique pour tous les nouveaux comptes,
                // à communiquer à l'utilisateur (pas d'envoi d'email) : il le change
                // lui-même à sa première connexion.
                $motPasseHash = password_hash(self::MOT_DE_PASSE_PAR_DEFAUT, PASSWORD_DEFAULT);

                $id_agence = $_POST['id_agence'] ?? null;
                $id_compagnie = in_array($droit, ['Admin', 'PDG'], true) ? ($_POST['id_compagnie'] ?? null) : $id_compagnie_session;
                $profile = ($droit === 'Utilisateur') ? ($_POST['profile'] ?? null) : null;

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

                try {
                    // insertion_update_simples_insert_id() ouvre sa PROPRE connexion PDO et
                    // renvoie lastInsertId() de CETTE connexion. Avant ce correctif, le code
                    // ouvrait un $pdo séparé juste pour beginTransaction()/lastInsertId(),
                    // mais l'INSERT réel passait par insertion_update_simples() sur une AUTRE
                    // connexion (Model::connect() n'est pas partagée) : $pdo->lastInsertId()
                    // valait donc toujours 0, et assignPermissionsParDefautPourRole() ci-dessous
                    // assignait les permissions par défaut à un utilisateur fantôme (id 0) au
                    // lieu du compte réellement créé — chaque nouvel utilisateur se retrouvait
                    // sans aucune permission malgré le message de succès.
                    $result = $this->insertion_update_simples_insert_id(
                        "INSERT INTO utilisateur (utilisateurs, emailUser, telephone, motPasse, status, id_agence, id_compagnie, droit, profile, photo)
                        VALUES (:utilisateurs, :emailUser, :telephone, :motPasse, :status, :id_agence, :id_compagnie, :droit, :profile, :photo)",
                        [
                            ":utilisateurs"  => $utilisateurs,
                            ":emailUser"     => $emailUser,
                            ":telephone"     => $telephone !== '' ? $telephone : null,
                            ":motPasse"      => $motPasseHash,
                            ":status"        => $status,
                            ":id_agence"     => $id_agence,
                            ":id_compagnie"  => $id_compagnie,
                            ":droit"         => $droit,
                            ":profile"       => $profile,
                            ":photo"         => $photoPath
                        ]
                    );

                    $idNouvelUtilisateur = (int) $result['lastInsertId'];

                    if ($idNouvelUtilisateur > 0) {
                        // Chaque rôle reçoit directement le jeu de permissions correspondant à ce
                        // qu'il peut faire (super_admin/Admin : tout ; chef_d_escale : tout sauf la
                        // programmation fixe et l'affectation des cars ; Utilisateur billet/colis :
                        // uniquement son service), sans passer par l'écran d'assignation manuelle.
                        $permissionModel = new Permission();
                        $permissionModel->assignPermissionsParDefautPourRole($idNouvelUtilisateur, $droit, $profile);

                        $this->set_swal(
                            "👤 Utilisateur ajouté !",
                            "L'utilisateur a été ajouté avec succès. Mot de passe par défaut : " . self::MOT_DE_PASSE_PAR_DEFAUT . " (à communiquer à l'utilisateur, qui pourra le modifier après sa première connexion).",
                            "success",
                            "#0d6efd",
                            BASE_URL . "/admin/Configurations/add_utilisateurs"
                        );
                    } else {
                        $this->set_swal("Erreur", "Échec de l'ajout de l'utilisateur.", "error", "#dc3545");
                    }
                } catch (Throwable $e) {
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


        // Le menu latéral (sidebar) appelle userHasPermission() ~26 fois à lui seul, sur
        // CHAQUE page admin, et une page peut créer plusieurs instances de Configuration
        // (une pour elle-même, une pour le sidebar) : sans cache, ça fait 26+ allers-retours
        // SQL (jointure sur 3 tables) juste pour savoir quoi afficher dans le menu, à chaque
        // clic. On charge donc la liste complète des permissions de l'utilisateur en UNE
        // seule requête, mise en cache en session (partagée entre toutes les instances de la
        // même requête HTTP, contrairement à un cache d'instance). Effet de bord accepté : un
        // changement de permission par un admin ne prend effet, pour l'utilisateur concerné,
        // qu'à sa prochaine connexion (le cache n'est invalidé qu'à la (re)connexion).
        public function userHasPermission($userPermissionName)
        {
            // Mode support technique : le super_admin impersonné voit tout comme un admin normal
            if (($_SESSION['super_admin_droit'] ?? null) === 'super_admin') {
                return true;
            }

            // Le super_admin a toutes les permissions par conception, y compris en direct
            // (pas seulement en mode impersonation).
            if (($_SESSION['droit'] ?? null) === 'super_admin') {
                return true;
            }

            if (!isset($_SESSION['permissions_cache']) || ($_SESSION['permissions_cache_user'] ?? null) !== $this->idUser) {
                $sql = "SELECT p.nom_permission
                    FROM permision p
                    JOIN user_permission up ON p.id_permision = up.permission_id
                    WHERE up.user_id = ?";
                $result = $this->select_data_table_join_where($sql, [$this->idUser]);
                $_SESSION['permissions_cache'] = array_map(fn($row) => $row->nom_permission, $result);
                $_SESSION['permissions_cache_user'] = $this->idUser;
            }

            return in_array($userPermissionName, $_SESSION['permissions_cache'], true);
        }

        // PDG : superviseur lecture seule d'une compagnie. Reçoit le catalogue complet de
        // permissions (pour voir tous les écrans, cf. Permission::assignPermissionsParDefautPourRole())
        // mais ne doit jamais voir un bouton créer/modifier/valider/annuler. Les vues s'appuient
        // sur ce helper pour cacher ces boutons ; l'interdiction réelle est appliquée par
        // App::isEcritureBloqueePourPDG() (verrou central, indépendant de l'affichage).
        public function estLectureSeule(): bool
        {
            return ($_SESSION['droit'] ?? null) === 'PDG';
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

        // Mise à jour de ses propres infos par l'utilisateur lui-même (page "Mes informations").
        public function updateInfoUtilisateur($idUser, $utilisateurs, $emailUser, $telephone = null, $photo = null)
        {
            $stmt = $this->connect()->prepare("UPDATE utilisateur SET utilisateurs = ?, emailUser = ?, telephone = ?, photo = ? WHERE idUser = ?");
            return $stmt->execute([$utilisateurs, $emailUser, $telephone, $photo, $idUser]);
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
