 <?php
    class Add_liste_escale extends Model
    {

        public function savescale()
        {
            $errors = [];
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($escales)) {
                $errors[] = "L'escales est obligatoire.";
            }
            $db = $this->connect();
            // Vérifier si la combinaison localité + numéroGare existe
            $check = $db->prepare("SELECT id_escale FROM escale WHERE  escales = :escales AND id_compagnie = :id_compagnie");
            $check->execute([
                ':escales' => $escales,
                ':id_compagnie' => $id_compagnie
            ]);
            $existe = $check->fetch();

            if ($existe) {
                $errors[] = "Cet escale existe deja.";
            }

            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {
                $insertion = $this->insertion_update_simples(
                    "INSERT INTO escale 
                ( escales,id_compagnie) VALUES(:escales,:id_compagnie)",
                    [
                        ":escales" => $escales,
                        ":id_compagnie" => $id_compagnie
                    ]
                );
                if ($insertion == true) {
                    $this->set_flash('Escale ajouter avec succes', 'info');
                    //  header("Location: " . $_SERVER['PHP_SELF']); // Redirection vers la même page
                    //  exit();
                } else {
                    $this->set_flash('Salle non ajouter');
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    $this->set_flash($error, "danger");
                }
            }
        }
    }
