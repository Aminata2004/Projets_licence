-- Nouvelle fonctionnalite : gestion des camions de colis (fret), en plus des cars
-- (bus passagers) deja geres par la table `car`.
--
-- Table separee (et non un type sur `car`) : la logique billetterie/programmation
-- (nbr_place, nbr_place_reserve, programmer_car, status_car, programmation_voyage,
-- liaison_car_trajet...) est fortement couplee a `car` ; ajouter un type dessus
-- risquerait de casser cette logique existante.
-- Voir app/models/Cars_chauffeur.php, app/controllers/admin/Cars_chauffeurs.php.
--
-- Un camion n'a pas de notion de "programmation du jour" (contrairement a un car,
-- qui doit etre sur programmation_voyage pour etre selectionnable a l'envoi de
-- colis) : il est simplement selectionnable des que actif = 'on'.
--
-- Pas de contrainte FOREIGN KEY (ce projet n'en utilise nulle part, cf. `car`,
-- `chauffeur` existants) : coherence delegee au code applicatif, comme partout
-- ailleurs dans ce schema.
--
-- A verifier avant execution : DESCRIBE chauffeur; DESCRIBE envoi; DESCRIBE ligne_envoi;
-- (le dump SQL local du projet est connu pour etre partiellement perime par rapport
-- a la base reelle -- ex: chauffeur.photo y existe sans etre dans le dump).
--
-- A executer une seule fois, manuellement, en dev puis en prod.

CREATE TABLE IF NOT EXISTS camion (
    id_camion     INT(11) NOT NULL AUTO_INCREMENT,
    numero_camion INT(11) NOT NULL,
    matriculle    VARCHAR(100) NOT NULL,
    actif         VARCHAR(10) NOT NULL DEFAULT 'on',
    id_compagnie  INT(11) NOT NULL,
    PRIMARY KEY (id_camion),
    KEY id_compagnie (id_compagnie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Un chauffeur peut desormais conduire un car OU un camion (jamais les deux) :
-- id_car devient nullable, id_camion est ajoute (nullable), et type_vehicule
-- indique lequel des deux fait foi. La coherence "exactement un des deux
-- renseigne" est imposee par le code (app/models/Chauffeurs_car.php), pas par
-- un CHECK SQL (portabilite MySQL/MariaDB variable selon l'hebergeur).
ALTER TABLE chauffeur
    MODIFY id_car INT(11) NULL,
    ADD COLUMN id_camion INT(11) NULL AFTER id_car,
    ADD COLUMN type_vehicule ENUM('car','camion') NOT NULL DEFAULT 'car' AFTER id_camion;

-- Backfill : tous les chauffeurs existants sont deja rattaches a un car.
UPDATE chauffeur SET type_vehicule = 'car' WHERE id_car IS NOT NULL;

-- Un envoi de colis peut desormais etre affecte a un camion plutot qu'a un car.
-- id_car etait deja nullable (DEFAULT NULL) ; on ajoute la colonne miroir.
ALTER TABLE envoi
    ADD COLUMN id_camion INT(11) DEFAULT NULL AFTER id_car;

-- ligne_envoi.numero_car (nom trompeur : stocke en realite un id_car, pas un
-- "numero") doit devenir nullable pour permettre une ligne "camion" du jour ;
-- numero_camion est son miroir (stocke en realite un id_camion).
ALTER TABLE ligne_envoi
    MODIFY numero_car INT(11) NULL,
    ADD COLUMN numero_camion INT(11) DEFAULT NULL AFTER numero_car;

-- Diagnostic post-migration : verifier qu'aucun chauffeur n'est orphelin
-- (ni id_car ni id_camion) apres coup.
-- SELECT * FROM chauffeur WHERE id_car IS NULL AND id_camion IS NULL;
