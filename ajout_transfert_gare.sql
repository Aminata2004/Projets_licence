-- Historique des transferts de passagers entre deux gares d'une même localité (feature
-- "transfert de passagers entre gares" : quand le car d'une gare n'est pas complet, ses
-- passagers réservés peuvent être consolidés sur le car d'une gare voisine plutôt que de
-- faire partir deux cars à moitié vides). Ces tables tracent chaque transfert (qui, quoi,
-- combien, quand) de façon permanente, y compris le mouvement de caisse associé.
--
-- À exécuter une seule fois, après ajout_id_agence_programmation_voyage.sql.

CREATE TABLE transferts_gare (
  id_transfert INT AUTO_INCREMENT PRIMARY KEY,
  id_compagnie INT NOT NULL,
  id_agence_source INT NOT NULL,
  id_agence_destination INT NOT NULL,
  id_programmation_source INT NOT NULL,
  id_programmation_destination INT NOT NULL,
  id_caisse_source INT NOT NULL,
  id_caisse_destination INT NOT NULL,
  nombre_billets INT NOT NULL,
  nombre_passagers INT NOT NULL,
  montant_total DECIMAL(12,2) NOT NULL,
  id_utilisateur INT NOT NULL,
  date_transfert DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE transfert_billets (
  id_transfert_billet INT AUTO_INCREMENT PRIMARY KEY,
  id_transfert INT NOT NULL,
  idBillets INT NOT NULL,
  ancien_num_gare VARCHAR(50) NOT NULL,
  nouveau_num_gare VARCHAR(50) NOT NULL,
  FOREIGN KEY (id_transfert) REFERENCES transferts_gare(id_transfert)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
