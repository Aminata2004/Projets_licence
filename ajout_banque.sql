-- Fonctionnalité "Dépôt en banque" : en fin de journée, l'admin peut demander au chef
-- d'escale de déposer une partie de la recette de sa caisse dans un compte banque de la
-- compagnie. Workflow à deux temps, sur demande explicite de l'utilisateur : le chef
-- d'escale crée une demande (en_attente), l'argent ne sort de la caisse qu'après
-- confirmation par un Admin/super_admin (confirme) ; l'admin peut aussi la rejeter
-- (rejete), auquel cas rien ne bouge financièrement.
--
-- À exécuter une seule fois.


