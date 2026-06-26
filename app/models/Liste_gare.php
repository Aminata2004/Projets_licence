<?php
class Liste_gare extends Model
{
    public function saveGares()
    { // Initialiser un tableau d'erreurs
        $errors = [];
        $id_compagnie = $_SESSION['id_compagnie'];

        // Récupération sécurisée des données du formulaire
        extract($_POST);


        $db = $this->connect();

        // Vérifier si la combinaison localité + numéroGare existe
        $check = $db->prepare("SELECT idAgence FROM agence WHERE localite = :localite AND numeroGare = :numeroGare AND id_compagnie = :id_compagnie");
        $check->execute([
            ':localite' => $localite,
            ':numeroGare' => $numeroGare,
            ':id_compagnie' => $id_compagnie
        ]);
        $existe = $check->fetch();

        if ($existe) {
            $errors[] = "Cette gare existe déjà dans cette localité.";
        }
        // Vérifier si le téléphone existe déjà
        $telExiste = $this->existe('agence', 'tel', $tel);
        if ($telExiste) {
            $errors[] = "Ce numéro de téléphone est déjà utilisé.";
        }

        // Vérifier si le code marchand existe déjà
        $codeExiste = $this->existe('agence', 'code', $code);
        if ($codeExiste) {
            $errors[] = "Ce code marchand est déjà utilisé.";
        }

        // Vérifier si le code commence par un chiffre négatif
        if (preg_match('/^-/', $code)) {
            $errors[] = "Le code marchand ne peut pas commencer par un signe négatif.";
        }


        if (count($errors) === 0) {
            $insertion = $this->insertion_update_simples(
                "INSERT INTO agence(code, localite, numeroGare, tel, libele, id_compagnie) 
         VALUES(:code, :localite, :numeroGare, :tel, :libele, :id_compagnie)",
                [
                    ":code" => $code,
                    ":localite" => $localite,
                    ":numeroGare" => $numeroGare,
                    ":tel" => $tel,
                    ":libele" => $libele,
                    ":id_compagnie" => $id_compagnie
                ]
            );

            if ($insertion == true) {
                $this->set_flash('Gare ajoutée avec succès', 'info');
            } else {
                $this->set_flash('Gare non ajoutée');
            }
        } else {
            foreach ($errors as $error) {
                $this->set_flash($error, "danger");
            }
        }
    }

    public function editAgence($data)
    {
        $req = "UPDATE agence 
           SET numeroGare =:numeroGare, 
               localite=:localite,
               code=:code,
                tel=:tel
                WHERE idAgence=:idAgence";

        $params = [
            ":numeroGare" => $data['numeroGare'],
            ":localite" => $data['localite'],
            ':code' => $data['code'],
            ':tel' => $data['tel'],
            ':idAgence' => $data['idAgence'],
        ];

        $modification = $this->insertion_update_simples($req, $params);

        if ($modification == true) {
            $this->set_flash("Modification effectuée avec succès", "primary");
        } else {
            $this->set_flash("Echec de la modification", "danger");
            // $this->redirect("compagnies");
        }
    }
    // public function saveCaisse()
    // {
    //     $id_compagnie = $_SESSION['id_compagnie'];

    //     // Récupération sécurisée des données du formulaire
    //     extract($_POST);
    //     $montant_colis=0;
    //     $montant_billets=0;
    //     $insertion = $this->insertion_update_simples(
    //         "INSERT INTO caisse(id_compagnie, id_agence, montant_initial, montant_billets,montant_colis, date_enregistrement, reference_caise,status_caisse) 
    //      VALUES(:id_compagnie, :id_agence, :montant_initial, :montant_billets, :montant_colis,:date_enregistrement, :reference_caise,:status_caisse)",
    //         [
    //             ":id_compagnie" => $id_compagnie,
    //             ":id_agence" => $id_agence,
    //             ":montant_initial" => $montant_initial, 
    //             ":montant_billets" => $montant_billets,
    //             ":montant_colis" => $montant_colis,
    //             ":date_enregistrement" => $date_enregistrement,
    //             ":reference_caise" => $reference_caise,
    //             ":status_caisse" => 1
    //         ]
    //     );
    //      if ($insertion == true) {
    //             $this->set_flash('Gare ajoutée avec succès', 'info');
    //         } else {
    //             $this->set_flash('Gare non ajoutée');
    //         }
    // }

public function saveCaisse()
{
    $id_compagnie = $_SESSION['id_compagnie'];

    // Récupération sécurisée des données du formulaire
    extract($_POST);
    $montant_colis   = 0;
    $montant_billets = 0;

    // ⚡ Vérifier si une caisse active existe déjà pour cette agence
    $sql = "SELECT COUNT(*) as total 
            FROM caisse 
            WHERE id_agence = :id_agence 
              AND status_caisse = 1";

    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([":id_agence" => $id_agence]);
    $check = $stmt->fetch(PDO::FETCH_OBJ);

    if ($check && $check->total > 0) {
        // Une caisse active existe déjà → refus
        $this->set_flash("Impossible d’ouvrir une nouvelle caisse : une caisse active existe déjà pour cette localité.", "danger");
        return false;
    }

    // ✅ Sinon on insère
    $insertion = $this->insertion_update_simples(
        "INSERT INTO caisse(id_compagnie, id_agence, montant_initial, montant_billets, montant_colis, date_enregistrement, reference_caise, status_caisse) 
         VALUES(:id_compagnie, :id_agence, :montant_initial, :montant_billets, :montant_colis, :date_enregistrement, :reference_caise, :status_caisse)",
        [
            ":id_compagnie"       => $id_compagnie,
            ":id_agence"          => $id_agence,
            ":montant_initial"    => $montant_initial,
            ":montant_billets"    => $montant_billets,
            ":montant_colis"      => $montant_colis,
            ":date_enregistrement"=> $date_enregistrement,
            ":reference_caise"    => $reference_caise,
            ":status_caisse"      => 1
        ]
    );

    if ($insertion == true) {
        $this->set_swal(
            "Caisse ouverte !", 
            "La caisse a été ajoutée avec succès.", 
            "success", 
            "#16a34a"
        );
    } else {
        $this->set_swal(
            "Erreur", 
            "Erreur lors de l’ajout de la caisse.", 
            "error", 
            "#dc3545"
        );
    }
}


}
