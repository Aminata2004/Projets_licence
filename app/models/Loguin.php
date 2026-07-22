<?php
class Loguin extends Model
{
    private const MAX_TENTATIVES = 10;
    private const BLOCAGE_MINUTES = 5;

    // Comptes super_admin recréés automatiquement à chaque fois que la table
    // utilisateur est vide (nouvelle base, reset accidentel...), pour ne jamais
    // se retrouver sans accès admin.
    private const SUPER_ADMINS_PAR_DEFAUT = [
        ['nom' => 'Aminata Diallo', 'email' => 'amitacompt9@gmail.com', 'motPasse' => 'superadmin1'],
        ['nom' => 'Rokhaya Djiré', 'email' => 'rokhayadjire5@gmail.com', 'motPasse' => 'superadmin2'],
        ['nom' => 'Barry', 'email' => 'maitredjkbarry@icloud.com', 'motPasse' => 'superadmin3'],
    ];

    public function seedSuperAdminsSiTableVide(): void
    {
        $db = $this->connect();
        $nbUtilisateurs = (int) $db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
        if ($nbUtilisateurs > 0) {
            return;
        }

        $stmt = $db->prepare(
            "INSERT INTO utilisateur (utilisateurs, droit, motPasse, status, emailUser)
             VALUES (:nom, 'super_admin', :motPasse, 1, :email)"
        );
        foreach (self::SUPER_ADMINS_PAR_DEFAUT as $admin) {
            $stmt->execute([
                ':nom' => $admin['nom'],
                ':motPasse' => password_hash($admin['motPasse'], PASSWORD_DEFAULT),
                ':email' => $admin['email'],
            ]);
        }
    }

    public function connecter()
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", 'danger');
            return;
        }

        // Récupération des champs email et mot de passe du formulaire
        $emailUser = filter_var($_POST['emailUser'] ?? null, FILTER_SANITIZE_EMAIL);
        $motPasse = htmlspecialchars($_POST['motPasse'] ?? null);

        // Vérification des champs vides
        if (empty($emailUser) || empty($motPasse)) {
            if (empty($emailUser)) {
                $this->set_flash("Veuillez remplir le champ Email", 'danger');
            }
            if (empty($motPasse)) {
                $this->set_flash("Veuillez remplir le champ Mot de passe", 'danger');
            }
            return;
        }

        // Anti brute-force : refuse la tentative si ce compte est temporairement bloqué
        $minutesRestantes = $this->minutesDeBlocageRestantes($emailUser);
        if ($minutesRestantes > 0) {
            $this->set_flash("Trop de tentatives échouées. Réessayez dans $minutesRestantes minute(s).", 'danger');
            return;
        }

        // Requête : récupération de l'utilisateur et de l'agence si nécessaire
        $utilisateur = $this->FetchSelectWhere(
            'utilisateur.*, agence.localite, agence.numeroGare', // explicitement ce dont tu as besoin
            'utilisateur
     LEFT JOIN agence ON utilisateur.id_agence = agence.idAgence',
            'emailUser = :emailUser',
            ['emailUser' => $emailUser]
        );


        if (!$utilisateur) {
            $this->enregistrerTentativeEchouee($emailUser);
            $this->set_flash("Aucun utilisateur trouvé avec cet email", 'danger');
            return;
        }

        if (password_verify($motPasse, $utilisateur->motPasse)) {
            // Connexion réussie : on efface le compteur de tentatives
            $this->reinitialiserTentatives($emailUser);

            $_SESSION['id_utilisateur'] = $utilisateur->idUser;
            $_SESSION['emailUser'] = $utilisateur->emailUser;
            $_SESSION['nom'] = $utilisateur->utilisateurs;
            $_SESSION['droit'] = $utilisateur->droit;
            $_SESSION['status'] = $utilisateur->status;
            $_SESSION['profile'] = $utilisateur->profile ?? null;

            // Vérifie si l'utilisateur a une agence
            $_SESSION['ville'] = $utilisateur->localite ?? null;
            $_SESSION['id_agence'] = $utilisateur->id_agence ?? null;
            $_SESSION['numero_gare'] = $utilisateur->numeroGare ?? null;

            // Prend l'id_compagnie directement depuis la table utilisateur
            $_SESSION['id_compagnie'] = $utilisateur->id_compagnie ?? null;

            // Affichage pour debug
            // echo "id_compagnie = " . $_SESSION['id_compagnie'];
            // exit;

            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        } else {
            $this->enregistrerTentativeEchouee($emailUser);
            $this->set_flash("Mot de passe incorrect pour l'utilisateur", 'danger');
            return;
        }
    }

    // Retourne le nombre de minutes restantes avant déblocage, 0 si le compte n'est pas bloqué
    private function minutesDeBlocageRestantes(string $identifiant): int
    {
        $stmt = $this->connect()->prepare(
            "SELECT bloque_jusqu FROM login_attempts WHERE identifiant = :id"
        );
        $stmt->execute([':id' => $identifiant]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ligne || !$ligne['bloque_jusqu']) {
            return 0;
        }

        $secondesRestantes = strtotime($ligne['bloque_jusqu']) - time();
        return $secondesRestantes > 0 ? (int)ceil($secondesRestantes / 60) : 0;
    }

    private function enregistrerTentativeEchouee(string $identifiant): void
    {
        $stmt = $this->connect()->prepare(
            "INSERT INTO login_attempts (identifiant, tentatives, derniere_tentative)
             VALUES (:id, 1, NOW())
             ON DUPLICATE KEY UPDATE
                tentatives = tentatives + 1,
                derniere_tentative = NOW(),
                -- MySQL évalue les SET de gauche à droite : `tentatives` ici vaut déjà la
                -- nouvelle valeur (tentatives + 1 ci-dessus), donc pas de +1 supplémentaire.
                bloque_jusqu = IF(tentatives >= :max, DATE_ADD(NOW(), INTERVAL :minutes MINUTE), bloque_jusqu)"
        );
        $stmt->execute([
            ':id' => $identifiant,
            ':max' => self::MAX_TENTATIVES,
            ':minutes' => self::BLOCAGE_MINUTES,
        ]);
    }

    private function reinitialiserTentatives(string $identifiant): void
    {
        $stmt = $this->connect()->prepare("DELETE FROM login_attempts WHERE identifiant = :id");
        $stmt->execute([':id' => $identifiant]);
    }
}
