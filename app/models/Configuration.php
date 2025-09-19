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

        private $idUser;

        public function saveUtilisateur()
        {
            $errors = [];

            extract($_POST);
            $status = 1;

            // Récupération de la compagnie de l'Admin connecté
            $id_compagnie_session = $_SESSION["id_compagnie"] ?? null;

            // Validation des champs
            if (empty($utilisateurs)) {
                $errors[] = "Le nom de l'utilisateur est obligatoire.";
            }

            if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }

            if (empty($motPasse)) {
                $errors[] = "Le mot de passe est obligatoire.";
            }

            if (empty($ConfirmermotPasse)) {
                $errors[] = "La confirmation du mot de passe est obligatoire.";
            }

            if (!empty($motPasse) && !empty($ConfirmermotPasse) && $motPasse !== $ConfirmermotPasse) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (empty($droit)) {
                $errors[] = "Le droit est obligatoire.";
            }

            if (!empty($emailUser) && $this->existe_deja('emailUser', $emailUser, 'utilisateur')) {
                $errors[] = "Cet email est déjà utilisé.";
            }

            if (count($errors) === 0) {
                $motPasseHash = password_hash($motPasse, PASSWORD_DEFAULT);
                $id_agence = $_POST['id_agence'] ?? null;
                $id_compagnie = null;

                // Si l'utilisateur qu'on crée est Admin, on prend l'id_compagnie du formulaire
                // Sinon, on force à celle de l'Admin connecté
                if ($droit === 'Admin') {
                    $id_compagnie = $_POST['id_compagnie'] ?? null;
                } else {
                    $id_compagnie = $id_compagnie_session;
                    $id_agence = $_POST['id_agence'] ?? null;
                }

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
                    $this->set_swal(
                        "👤 Utilisateur ajouté !",
                        "L'utilisateur a été ajouté avec succès.",
                        "success",
                        "#0d6efd", // couleur primary pour l'icône et le bouton
                        BASE_URL . "/admin/Configurations/add_utilisateurs" // redirection après confirmation
                    );
                } else {
                    $this->set_swal(
                        "Erreur",
                        "Échec de l'ajout de l'utilisateur.",
                        "error",
                        "#dc3545" // rouge
                    );
                }
            } else {
                $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
                $this->set_swal(
                    "Erreurs détectées",
                    $errorsHtml,
                    "warning",
                    "#ffc107" // jaune warning
                );
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
             
    }
