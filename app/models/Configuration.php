 <?php
    class Configuration extends Model
    {
        // public function saveUtilisateur()
        // {
        //     // Initialiser un tableau d'erreurs
        //     $errors = [];

        //     // Récupération sécurisée des données du formulaire
        //     extract($_POST);
        //     $status = 1;
        //     $id_compagnie = $_SESSION["id_compagnie"];
        //     // Vérification des champs obligatoires
        //     if (empty($utilisateurs)) {
        //         $errors[] = "Le nom de l'utilisateur est obligatoire.";
        //     }

        //     if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
        //         $errors[] = "L'email n'est pas valide.";
        //     }

        //     if (empty($motPasse)) {
        //         $errors[] = "Le mot de passe est obligatoire.";
        //     }

        //     if (empty($ConfirmermotPasse)) {
        //         $errors[] = "La confirmation du mot de passe est obligatoire.";
        //     }

        //     if (!empty($motPasse) && !empty($ConfirmermotPasse) && $motPasse !== $ConfirmermotPasse) {
        //         $errors[] = "Les mots de passe ne correspondent pas.";
        //     }


        //     if (empty($droit)) {
        //         $errors[] = "Le droit est obligatoire.";
        //     }

        //     // Vérification de l'existence de l'email
        //     if (!empty($emailUser) && $this->existe_deja('emailUser', $emailUser, 'utilisateur')) {
        //         $errors[] = "Cet email est déjà utilisé.";
        //     }

        //     // Si aucune erreur, on procède à l'insertion
        //     if (count($errors) === 0) {
        //         // Hachage du mot de passe
        //         $motPasseHash = password_hash($motPasse, PASSWORD_DEFAULT);

        //         $droit = $_POST['droit'];
        //         // Initialisation des deux champs à null
        //         $id_agence = null;
        //         $id_compagnie = null;

        //         // Choisir les IDs selon le rôle
        //         if ($droit === 'Admin') {
        //             $id_compagnie = $_POST['id_compagnie'] ?? null;
        //         } else {
        //             $id_agence = $_POST['id_agence'] ?? null;
        //         }

        //         // Insertion avec les deux colonnes
        //         $insertion = $this->insertion_update_simples(
        //             "INSERT INTO utilisateur (utilisateurs, emailUser, motPasse, status, id_agence, id_compagnie, droit) 
        //              VALUES (:utilisateurs, :emailUser, :motPasse, :status, :id_agence, :id_compagnie, :droit)",
        //             [
        //                 ":utilisateurs"  => $utilisateurs,
        //                 ":emailUser"     => $emailUser,
        //                 ":motPasse"      => $motPasseHash,
        //                 ":status"        => $status,
        //                 ":id_agence"     => $id_agence,
        //                 ":id_compagnie"  => $id_compagnie,
        //                 ":droit"         => $droit
        //             ]
        //         );

        //         if ($insertion) {
        //             $this->set_flash('Utilisateur ajouté avec succès', 'info');
        //         } else {
        //             $this->set_flash("Échec de l'ajout de l'utilisateur.", 'danger');
        //         }
        //     } else {
        //         // Affichage des erreurs
        //         foreach ($errors as $error) {
        //             $this->set_flash($error, 'danger');
        //         }
        //     }
        // }

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
                        BASE_URL . "/Configurations" // redirection après confirmation
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
    }
