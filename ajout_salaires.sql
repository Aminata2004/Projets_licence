-- Module Salaire + Bulletin de paie.
--
-- Tout le personnel salarie (comptes utilisateur hors super_admin, + personnel
-- sans compte systeme comme un gardien ou un balayeur) a une fiche `employe`
-- avec un salaire de base, et peut recevoir des bulletins de paie mensuels.
--
-- Table separee de `utilisateur`/`chauffeur` (pas de colonne salaire ajoutee
-- directement dessus) : le nom/contact reste la source de verite sur
-- utilisateur/chauffeur (lu par jointure), `employe` ne porte que les donnees
-- propres a la paie (poste, gare de rattachement pour le scoping, salaire,
-- statut). Le personnel hors-systeme (id_utilisateur et id_chauffeur NULL)
-- porte son propre nom_prenom.
--
-- Pas de contrainte FOREIGN KEY (ce projet n'en utilise nulle part) :
-- coherence delegee au code applicatif, comme partout ailleurs dans ce schema.
--
-- A verifier avant execution : DESCRIBE utilisateur; DESCRIBE chauffeur;
-- (le dump SQL local du projet est connu pour etre partiellement perime par
-- rapport a la base reelle).
--
-- A executer une seule fois, manuellement, en dev puis en prod.

CREATE TABLE IF NOT EXISTS employe (
    id_employe     INT(11) NOT NULL AUTO_INCREMENT,
    id_utilisateur INT(11) DEFAULT NULL,
    id_chauffeur   INT(11) DEFAULT NULL,
    nom_prenom     VARCHAR(200) DEFAULT NULL,
    poste          VARCHAR(100) NOT NULL,
    id_agence      INT(11) DEFAULT NULL,
    id_compagnie   INT(11) NOT NULL,
    salaire_base   INT(11) NOT NULL DEFAULT 0,
    statut         VARCHAR(10) NOT NULL DEFAULT 'actif',
    date_creation  DATE NOT NULL,
    PRIMARY KEY (id_employe),
    KEY id_compagnie (id_compagnie),
    KEY id_agence (id_agence),
    KEY id_utilisateur (id_utilisateur),
    KEY id_chauffeur (id_chauffeur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS bulletin_paie (
    id_bulletin     INT(11) NOT NULL AUTO_INCREMENT,
    id_employe      INT(11) NOT NULL,
    periode         VARCHAR(7) NOT NULL,
    salaire_verse   INT(11) NOT NULL,
    date_generation DATETIME NOT NULL,
    genere_par      INT(11) DEFAULT NULL,
    id_compagnie    INT(11) NOT NULL,
    PRIMARY KEY (id_bulletin),
    KEY id_employe (id_employe),
    KEY id_compagnie (id_compagnie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Backfill : une fiche employe pour chaque compte utilisateur existant (hors
-- super_admin, qui n'est pas du personnel salarie de la compagnie) et pour
-- chaque chauffeur existant. Salaire a 0 par defaut -- l'Admin le renseigne
-- ensuite depuis l'ecran Salaires, aucun montant n'est invente ici.
INSERT INTO employe (id_utilisateur, poste, id_agence, id_compagnie, salaire_base, statut, date_creation)
SELECT idUser, droit, id_agence, id_compagnie, 0, 'actif', CURDATE()
FROM utilisateur
WHERE droit != 'super_admin';

INSERT INTO employe (id_chauffeur, poste, id_agence, id_compagnie, salaire_base, statut, date_creation)
SELECT id_chauffeur, 'Chauffeur', NULL, id_compagnie, 0, 'actif', CURDATE()
FROM chauffeur;

-- Accorde retroactivement la permission Salaire_apercu aux comptes Admin/PDG deja
-- existants : Permission::assignPermissionsParDefautPourRole() ne l'attribue qu'a
-- la CREATION d'un compte, pas retroactivement -- sans ce backfill, les Admin/PDG
-- deja crees avant cette fonctionnalite n'auraient pas acces a "Salaires" malgre
-- le fait qu'elle soit censee leur etre accordee par defaut.
INSERT INTO permision (nom_permission)
SELECT 'Salaire_apercu' WHERE NOT EXISTS (
    SELECT 1 FROM permision WHERE nom_permission = 'Salaire_apercu'
);

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
JOIN permision p ON p.nom_permission = 'Salaire_apercu'
WHERE u.droit IN ('Admin', 'PDG')
AND NOT EXISTS (
    SELECT 1 FROM user_permission up
    WHERE up.user_id = u.idUser AND up.permission_id = p.id_permision
);
