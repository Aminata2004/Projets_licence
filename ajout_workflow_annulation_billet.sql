-- Workflow de demande/validation pour l'annulation d'un billet vendu au guichet :
-- un simple Utilisateur ne peut pas annuler ; un chef d'escale peut seulement DEMANDER
-- l'annulation (motif, date, demandeur tracés) ; seul un Admin peut ensuite confirmer
-- (ce qui restitue réellement la place et enregistre le remboursement comme une dépense
-- formelle) ou rejeter (le billet redevient actif, sans trace).
--
-- status_billets prend désormais aussi la valeur 'annulation_demandee' en plus de 'annule'
-- (voir Liste_du_jour::demanderAnnulationBillet()/confirmerAnnulationBillet()/rejeterAnnulationBillet()).
--
-- À exécuter une seule fois.

ALTER TABLE billets
    ADD COLUMN demande_annulation_par INT NULL AFTER motif_annulation,
    ADD COLUMN demande_annulation_le DATETIME NULL AFTER demande_annulation_par;

-- Nouvelle catégorie de dépense pour tracer les remboursements liés à une annulation de
-- billet confirmée par un Admin (au lieu d'une simple déduction silencieuse de la caisse).
ALTER TABLE depense MODIFY COLUMN categorie
    ENUM('Carburant','Entretien/Reparation','Peage','Fournitures','Communication','Salaire','Loyer','Assurance','Remboursement annulation','Autre') NOT NULL;
