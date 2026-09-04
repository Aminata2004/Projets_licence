<?php
class BulletinPaie extends Model
{
    // Génère (ou renvoie l'existant) le bulletin d'un employé pour une période
    // donnée -- un seul bulletin par (id_employe, periode), le montant est figé
    // au moment de la génération (indépendant d'un changement ultérieur du
    // salaire de base). Réservé Admin/PDG/super_admin, contrôlé par l'appelant.
    public function genererBulletin($id_employe, $periode, $salaire_base, $genere_par)
    {
        $existant = $this->FetchSelectWhere1(
            "*",
            "bulletin_paie",
            "id_employe = :id_employe AND periode = :periode",
            [":id_employe" => $id_employe, ":periode" => $periode]
        );
        if (!empty($existant)) {
            return $existant[0]['id_bulletin'] ?? null;
        }

        $result = $this->insertion_update_simples_insert_id(
            "INSERT INTO bulletin_paie (id_employe, periode, salaire_verse, date_generation, genere_par, id_compagnie)
             VALUES (:id_employe, :periode, :salaire_verse, NOW(), :genere_par, :id_compagnie)",
            [
                ":id_employe" => $id_employe,
                ":periode" => $periode,
                ":salaire_verse" => $salaire_base,
                ":genere_par" => $genere_par,
                ":id_compagnie" => $_SESSION['id_compagnie']
            ]
        );

        return (int) ($result['lastInsertId'] ?? 0);
    }

    // Même scoping hiérarchique que Employe::getEmployesVisibles() (jointure sur
    // employe pour appliquer le même filtre id_agence).
    public function getBulletinsVisibles()
    {
        $droit = $_SESSION['droit'] ?? null;
        $select = "bulletin_paie.*,
            COALESCE(utilisateur.utilisateurs, chauffeur.nom_prenom, employe.nom_prenom) AS nom_affiche,
            employe.poste";
        $joins = "bulletin_paie
            INNER JOIN employe ON bulletin_paie.id_employe = employe.id_employe
            LEFT JOIN utilisateur ON employe.id_utilisateur = utilisateur.idUser
            LEFT JOIN chauffeur ON employe.id_chauffeur = chauffeur.id_chauffeur";

        if ($droit === 'super_admin') {
            return $this->FetchSelectWheres($select, $joins, '1 ORDER BY bulletin_paie.date_generation DESC', []);
        }

        $condition = 'bulletin_paie.id_compagnie = :id_compagnie';
        $params = ['id_compagnie' => $_SESSION['id_compagnie']];

        if (!empty($_SESSION['id_agence'])) {
            // Meme raison que dans Employe::getEmployesVisibles() : le personnel sans
            // gare precise (chauffeur) reste visible par tous ceux qui ont la permission.
            $condition .= ' AND (employe.id_agence = :id_agence OR employe.id_agence IS NULL)';
            $params['id_agence'] = $_SESSION['id_agence'];
        }

        return $this->FetchSelectWheres(
            $select,
            $joins,
            $condition . ' ORDER BY bulletin_paie.date_generation DESC',
            $params
        );
    }

    // Un seul bulletin, avec re-vérification IDOR (même principe que
    // Employe::getEmployeVisibleById()) -- utilisé avant le téléchargement du PDF.
    public function getBulletinVisibleById($id)
    {
        $droit = $_SESSION['droit'] ?? null;
        $select = "bulletin_paie.*,
            COALESCE(utilisateur.utilisateurs, chauffeur.nom_prenom, employe.nom_prenom) AS nom_affiche,
            employe.poste, employe.id_agence,
            agence.localite, agence.numeroGare,
            compagnie.nom_compagnie, compagnie.logo";
        $joins = "bulletin_paie
            INNER JOIN employe ON bulletin_paie.id_employe = employe.id_employe
            LEFT JOIN utilisateur ON employe.id_utilisateur = utilisateur.idUser
            LEFT JOIN chauffeur ON employe.id_chauffeur = chauffeur.id_chauffeur
            LEFT JOIN agence ON employe.id_agence = agence.idAgence
            LEFT JOIN compagnie ON bulletin_paie.id_compagnie = compagnie.id_compagnie";

        $condition = "bulletin_paie.id_bulletin = :id AND bulletin_paie.id_compagnie = :id_compagnie";
        $params = [":id" => $id, ":id_compagnie" => $_SESSION['id_compagnie'] ?? null];

        if ($droit !== 'super_admin' && !empty($_SESSION['id_agence'])) {
            $condition .= " AND (employe.id_agence = :id_agence OR employe.id_agence IS NULL)";
            $params[":id_agence"] = $_SESSION['id_agence'];
        }

        $result = $this->FetchSelectWhere1($select, $joins, $condition, $params);
        return !empty($result) ? $result[0] : null;
    }
}
