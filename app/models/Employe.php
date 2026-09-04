<?php
class Employe extends Model
{
    // Liste des employés visibles pour l'utilisateur connecté, calquée sur
    // Depense::getDepenses() : un chef d'escale (ou tout rôle rattaché à une gare
    // précise via id_agence) ne voit que le personnel de SA gare (lui compris,
    // puisque sa propre fiche employe partage le même id_agence) ; un Admin/PDG
    // (id_agence NULL) voit toute la compagnie. Le contrôleur garantit déjà que
    // cette méthode n'est jamais atteinte sans la permission Salaire_apercu.
    public function getEmployesVisibles()
    {
        $droit = $_SESSION['droit'] ?? null;

        if ($droit === 'super_admin') {
            return $this->SelectAllData($this->selectAvecNoms(), $this->joinsAvecNoms());
        }

        $condition = 'employe.id_compagnie = :id_compagnie';
        $params = ['id_compagnie' => $_SESSION['id_compagnie']];

        if (!empty($_SESSION['id_agence'])) {
            // Le personnel sans gare precise (chauffeur -- pas de colonne id_agence,
            // ou employe hors-systeme rattache a "toute la compagnie") doit rester
            // visible par tous ceux qui ont la permission, pas seulement Admin/PDG :
            // employe.id_agence = :id_agence ne matche jamais NULL en SQL, d'ou le OR.
            $condition .= ' AND (employe.id_agence = :id_agence OR employe.id_agence IS NULL)';
            $params['id_agence'] = $_SESSION['id_agence'];
        }

        return $this->FetchSelectWheres(
            $this->selectAvecNoms(),
            $this->joinsAvecNoms(),
            $condition . ' ORDER BY nom_affiche',
            $params
        );
    }

    // Une seule fiche employe, avec re-vérification IDOR (compagnie, et gare si
    // l'appelant est scopé) indépendamment de getEmployesVisibles() -- même
    // principe que Employes::resolveEmploye().
    public function getEmployeVisibleById($id)
    {
        $droit = $_SESSION['droit'] ?? null;
        $condition = 'employe.id_employe = :id AND employe.id_compagnie = :id_compagnie';
        $params = [':id' => $id, ':id_compagnie' => $_SESSION['id_compagnie'] ?? null];

        if ($droit !== 'super_admin' && !empty($_SESSION['id_agence'])) {
            $condition .= ' AND (employe.id_agence = :id_agence OR employe.id_agence IS NULL)';
            $params[':id_agence'] = $_SESSION['id_agence'];
        }

        $result = $this->FetchSelectWhere1($this->selectAvecNoms(), $this->joinsAvecNoms(), $condition, $params);
        return !empty($result) ? $result[0] : null;
    }

    private function selectAvecNoms()
    {
        return "employe.*,
            COALESCE(utilisateur.utilisateurs, chauffeur.nom_prenom, employe.nom_prenom) AS nom_affiche,
            agence.localite, agence.numeroGare";
    }

    private function joinsAvecNoms()
    {
        return "employe
            LEFT JOIN utilisateur ON employe.id_utilisateur = utilisateur.idUser
            LEFT JOIN chauffeur ON employe.id_chauffeur = chauffeur.id_chauffeur
            LEFT JOIN agence ON employe.id_agence = agence.idAgence";
    }

    // Création d'un employé hors-système (gardien, balayeur...) : réservée à
    // Admin/PDG/super_admin, contrôlé par le contrôleur avant l'appel.
    public function saveEmployeHorsSysteme()
    {
        $nom_prenom = trim($_POST['nom_prenom'] ?? '');
        $poste = trim($_POST['poste'] ?? '');
        $salaire_base = $_POST['salaire_base'] ?? '';
        $id_agence = !empty($_POST['id_agence']) ? $_POST['id_agence'] : null;

        $errors = [];
        if ($nom_prenom === '') {
            $errors[] = "Le nom est obligatoire.";
        }
        if ($poste === '') {
            $errors[] = "Le poste est obligatoire.";
        }
        if ($salaire_base === '' || !is_numeric($salaire_base) || $salaire_base < 0) {
            $errors[] = "Le salaire de base doit être un nombre positif.";
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->set_flash($error, "danger");
            }
            return;
        }

        $insertion = $this->insertion_update_simples(
            "INSERT INTO employe (nom_prenom, poste, id_agence, id_compagnie, salaire_base, statut, date_creation)
             VALUES (:nom_prenom, :poste, :id_agence, :id_compagnie, :salaire_base, 'actif', CURDATE())",
            [
                ":nom_prenom" => $nom_prenom,
                ":poste" => $poste,
                ":id_agence" => $id_agence,
                ":id_compagnie" => $_SESSION['id_compagnie'],
                ":salaire_base" => $salaire_base
            ]
        );

        if ($insertion) {
            $this->set_flash("Employé ajouté avec succès.", "info");
        } else {
            $this->set_flash("Erreur lors de l'ajout de l'employé.", "danger");
        }
    }

    // Modification du poste/salaire/gare/statut : réservée à Admin/PDG/super_admin
    // (contrôlé par le contrôleur). Le nom n'est éditable ici que pour le personnel
    // hors-système (sinon il vient de utilisateur/chauffeur, cf. selectAvecNoms()).
    public function updateSalaire($id, $data)
    {
        $sql = "UPDATE employe SET poste = :poste, salaire_base = :salaire_base,
                id_agence = :id_agence, statut = :statut";
        $params = [
            ':poste' => $data['poste'],
            ':salaire_base' => $data['salaire_base'],
            ':id_agence' => $data['id_agence'],
            ':statut' => $data['statut'],
            ':id' => $id
        ];

        if (array_key_exists('nom_prenom', $data)) {
            $sql .= ", nom_prenom = :nom_prenom";
            $params[':nom_prenom'] = $data['nom_prenom'];
        }

        $sql .= " WHERE id_employe = :id";
        if (($_SESSION['droit'] ?? null) !== 'super_admin') {
            $sql .= " AND id_compagnie = :id_compagnie";
            $params[':id_compagnie'] = $_SESSION['id_compagnie'] ?? null;
        }

        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute($params);
    }

    // Hook additif appelé par Configuration::saveUtilisateur() après la création
    // d'un nouveau compte (hors super_admin) : lui crée directement sa fiche
    // employe (salaire à 0, à renseigner ensuite par l'Admin depuis "Salaires").
    public function creerEmployePourUtilisateur($idUtilisateur, $droit, $id_agence, $id_compagnie)
    {
        if ($droit === 'super_admin') {
            return;
        }
        $this->insertion_update_simples(
            "INSERT INTO employe (id_utilisateur, poste, id_agence, id_compagnie, salaire_base, statut, date_creation)
             VALUES (:id_utilisateur, :poste, :id_agence, :id_compagnie, 0, 'actif', CURDATE())",
            [
                ":id_utilisateur" => $idUtilisateur,
                ":poste" => $droit,
                ":id_agence" => $id_agence,
                ":id_compagnie" => $id_compagnie
            ]
        );
    }

    // Hook additif équivalent, appelé par Chauffeurs_car::saveChauffeur() après la
    // création d'un nouveau chauffeur (car ou camion).
    public function creerEmployePourChauffeur($idChauffeur, $id_compagnie)
    {
        $this->insertion_update_simples(
            "INSERT INTO employe (id_chauffeur, poste, id_agence, id_compagnie, salaire_base, statut, date_creation)
             VALUES (:id_chauffeur, 'Chauffeur', NULL, :id_compagnie, 0, 'actif', CURDATE())",
            [
                ":id_chauffeur" => $idChauffeur,
                ":id_compagnie" => $id_compagnie
            ]
        );
    }
}
