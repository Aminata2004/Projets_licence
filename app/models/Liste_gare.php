<?php
class Liste_gare extends Model
{
    // Le formulaire envoie localite[]/numeroGare[]/code[]/tel[] (plusieurs lignes ajoutées
    // dynamiquement, "add to row"). Tout ou rien : toutes les lignes sont d'abord validées ;
    // si UNE seule est en erreur, RIEN n'est inséré (l'utilisateur doit tout corriger avant
    // de pouvoir réenregistrer), et si tout est valide, l'insertion se fait dans une seule
    // transaction (soit toutes les gares sont ajoutées, soit aucune en cas d'échec SQL).
    // Retourne les lignes soumises avec, pour chacune, les champs en erreur (pour les
    // souligner en rouge côté vue) et un message ; tableau vide si tout a été enregistré.
    public function saveGares()
    {
        $id_compagnie = $_SESSION['id_compagnie'];

        $localites = $_POST['localite'] ?? [];
        $numeroGares = $_POST['numeroGare'] ?? [];
        $codes = $_POST['code'] ?? [];
        $tels = $_POST['tel'] ?? [];

        if (!is_array($localites)) {
            $localites = [$localites];
            $numeroGares = [$numeroGares];
            $codes = [$codes];
            $tels = [$tels];
        }

        $db = $this->connect();
        $check = $db->prepare("SELECT idAgence FROM agence WHERE localite = :localite AND numeroGare = :numeroGare AND id_compagnie = :id_compagnie");

        $lignes = [];
        // Détecte aussi les doublons ENTRE les lignes du même envoi : l'unicité en base ne
        // les voit pas puisqu'aucune n'est encore insérée au moment du check.
        $combosVues = [];
        $telsVus = [];
        $codesVus = [];
        $auMoinsUneLigneSaisie = false;

        foreach ($localites as $index => $localite) {
            $localite = trim($localite);
            $numeroGare = trim($numeroGares[$index] ?? '');
            $code = trim($codes[$index] ?? '');
            $tel = trim($tels[$index] ?? '');

            if ($localite === '' && $numeroGare === '' && $code === '' && $tel === '') {
                continue; // ligne "add to row" jamais remplie : pas une vraie tentative
            }
            $auMoinsUneLigneSaisie = true;

            $champsEnErreur = [];
            $messages = [];

            foreach (['localite' => $localite, 'numeroGare' => $numeroGare, 'code' => $code, 'tel' => $tel] as $champ => $valeur) {
                if ($valeur === '') {
                    $champsEnErreur[] = $champ;
                }
            }
            if (!empty($champsEnErreur)) {
                $messages[] = "Tous les champs sont obligatoires.";
            }

            // Les vérifications suivantes exigent que les 4 champs soient remplis.
            if (empty($champsEnErreur)) {
                $combo = $localite . '|' . $numeroGare;

                if (preg_match('/^-/', $code)) {
                    $champsEnErreur[] = 'code';
                    $messages[] = "Le code marchand ne peut pas commencer par un signe négatif.";
                }
                if (in_array($combo, $combosVues, true)) {
                    $champsEnErreur[] = 'localite';
                    $champsEnErreur[] = 'numeroGare';
                    $messages[] = "« $localite / $numeroGare » est en double dans les lignes saisies.";
                }
                if (in_array($tel, $telsVus, true)) {
                    $champsEnErreur[] = 'tel';
                    $messages[] = "Le numéro « $tel » est en double dans les lignes saisies.";
                }
                if (in_array($code, $codesVus, true)) {
                    $champsEnErreur[] = 'code';
                    $messages[] = "Le code marchand « $code » est en double dans les lignes saisies.";
                }

                if (empty($champsEnErreur)) {
                    $check->execute([
                        ':localite' => $localite,
                        ':numeroGare' => $numeroGare,
                        ':id_compagnie' => $id_compagnie
                    ]);
                    if ($check->fetch()) {
                        $champsEnErreur[] = 'localite';
                        $champsEnErreur[] = 'numeroGare';
                        $messages[] = "« $localite / $numeroGare » existe déjà dans cette localité.";
                    }
                    if ($this->existe('agence', 'tel', $tel)) {
                        $champsEnErreur[] = 'tel';
                        $messages[] = "Le numéro « $tel » est déjà utilisé.";
                    }
                    if ($this->existe('agence', 'code', $code)) {
                        $champsEnErreur[] = 'code';
                        $messages[] = "Le code marchand « $code » est déjà utilisé.";
                    }
                }

                if (empty($champsEnErreur)) {
                    $combosVues[] = $combo;
                    $telsVus[] = $tel;
                    $codesVus[] = $code;
                }
            }

            $lignes[] = [
                'localite' => $localite,
                'numeroGare' => $numeroGare,
                'code' => $code,
                'tel' => $tel,
                'champs_en_erreur' => array_values(array_unique($champsEnErreur)),
                'erreur' => implode(' ', array_unique($messages)),
            ];
        }

        if (!$auMoinsUneLigneSaisie) {
            $this->set_flash("Aucune gare à ajouter.", "danger");
            return [];
        }

        $yADesErreurs = false;
        foreach ($lignes as $ligne) {
            if (!empty($ligne['champs_en_erreur'])) {
                $yADesErreurs = true;
                break;
            }
        }

        if ($yADesErreurs) {
            $this->set_flash("Corrigez les champs en rouge avant d'enregistrer.", "danger");
            return $lignes;
        }

        // Tout est valide : insertion atomique (tout ou rien).
        try {
            $db->beginTransaction();
            $insert = $db->prepare(
                "INSERT INTO agence(code, localite, numeroGare, tel, id_compagnie)
                 VALUES(:code, :localite, :numeroGare, :tel, :id_compagnie)"
            );
            foreach ($lignes as $ligne) {
                $insert->execute([
                    ':code' => $ligne['code'],
                    ':localite' => $ligne['localite'],
                    ':numeroGare' => $ligne['numeroGare'],
                    ':tel' => $ligne['tel'],
                    ':id_compagnie' => $id_compagnie
                ]);
            }
            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            $this->set_flash("Échec de l'enregistrement, rien n'a été ajouté. Réessayez.", "danger");
            return $lignes;
        }

        $nb = count($lignes);
        $this->set_flash($nb > 1 ? "$nb gares ajoutées avec succès." : "Gare ajoutée avec succès.", 'info');
        return [];
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

        // Un Admin ne peut modifier que les gares de sa propre compagnie (IDOR sinon)
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $req .= " AND id_compagnie = :id_compagnie";
            $params[':id_compagnie'] = $_SESSION['id_compagnie'] ?? null;
        }

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

    // 🔒 Un chef d'escale ne peut ouvrir une caisse que pour sa propre agence,
    // même si le formulaire est trafiqué pour soumettre un autre id_agence.
    if (($_SESSION['droit'] ?? null) === 'chef_d_escale') {
        $agence = $this->FetchSelectWhere(
            "idAgence",
            "agence",
            "idAgence = :id_agence AND id_compagnie = :id_compagnie AND localite = :ville",
            [
                ":id_agence"    => $id_agence,
                ":id_compagnie" => $id_compagnie,
                ":ville"        => $_SESSION['ville']
            ]
        );

        if (!$agence) {
            $this->set_flash("Vous ne pouvez ouvrir une caisse que pour votre propre agence.", "danger");
            return false;
        }
    }

    // Le montant initial ne peut pas être négatif
    if (!is_numeric($montant_initial) || (float)$montant_initial < 0) {
        $this->set_flash("Le montant initial doit être un nombre positif.", "danger");
        return false;
    }

    // Référence générée côté serveur (le champ du formulaire n'est qu'un aperçu) pour
    // garantir l'unicité même si deux caisses sont ouvertes le même jour.
    $reference_caise = 'RF' . date('md') . '-' . $id_agence . '-' . random_int(100, 999);

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
        $this->set_flash("Caisse ouverte avec succès.", "success");
        return true;
    } else {
        $this->set_flash("Erreur lors de l'ajout de la caisse.", "danger");
        return false;
    }
}



    public function suspendGare($idAgence)
    {
        $db = $this->connect();
        // Un Admin ne peut suspendre que les gares de sa propre compagnie (IDOR sinon)
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $stmt = $db->prepare("SELECT status FROM agence WHERE idAgence = ? AND id_compagnie = ?");
            $stmt->execute([$idAgence, $_SESSION['id_compagnie'] ?? null]);
        } else {
            $stmt = $db->prepare("SELECT status FROM agence WHERE idAgence = ?");
            $stmt->execute([$idAgence]);
        }
        $agence = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($agence) {
            $current_status = isset($agence['status']) ? $agence['status'] : 1;
            $new_status = ($current_status == 1) ? 0 : 1;
            $update = $db->prepare("UPDATE agence SET status = ? WHERE idAgence = ?");
            $update->execute([$new_status, $idAgence]);

            if ($new_status == 1) {
                $this->set_flash("Gare activée avec succès.", "success");
            } else {
                $this->set_flash("Gare suspendue avec succès.", "warning");
            }
        }
    }

    public function deleteGare($idAgence)
    {
        $db = $this->connect();
        // Un Admin ne peut supprimer que les gares de sa propre compagnie (IDOR sinon)
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $stmt = $db->prepare("SELECT numeroGare FROM agence WHERE idAgence = ? AND id_compagnie = ?");
            $stmt->execute([$idAgence, $_SESSION['id_compagnie'] ?? null]);
        } else {
            $stmt = $db->prepare("SELECT numeroGare FROM agence WHERE idAgence = ?");
            $stmt->execute([$idAgence]);
        }
        $agence = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($agence) {
            $numeroGare = $agence['numeroGare'];

            $countBillets = $db->prepare("SELECT COUNT(*) FROM billets WHERE num_gare = ?");
            $countBillets->execute([$numeroGare]);
            $hasBillets = $countBillets->fetchColumn();

            $countColis = $db->prepare("SELECT COUNT(*) FROM colis WHERE num_gare = ? OR id_agence = ?");
            $countColis->execute([$numeroGare, $idAgence]);
            $hasColis = $countColis->fetchColumn();

            $countCaisse = $db->prepare("SELECT COUNT(*) FROM caisse WHERE id_agence = ?");
            $countCaisse->execute([$idAgence]);
            $hasCaisse = $countCaisse->fetchColumn();

            if ($hasBillets == 0 && $hasColis == 0 && $hasCaisse == 0) {
                $del = $db->prepare("DELETE FROM agence WHERE idAgence = ?");
                $del->execute([$idAgence]);
                $this->set_flash("Gare supprimée avec succès.", "success");
            } else {
                $this->set_flash("Impossible de supprimer cette gare car elle a déjà des actions enregistrées.", "danger");
            }
        } else {
            $this->set_flash("Gare introuvable.", "danger");
        }
    }
}
