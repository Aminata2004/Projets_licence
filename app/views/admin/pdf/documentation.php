<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
  @page { margin: 18mm 16mm; }
  body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #1c2128; line-height: 1.55; }
  h1, h2, h3 { font-family: "DejaVu Serif", serif; font-weight: normal; color: #1c2128; }

  .eyebrow { font-family: "DejaVu Sans Mono", monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: #1e4d6b; }
  h1 { font-size: 28px; margin: 8px 0 6px; }
  .subtitle { color: #4b5563; font-size: 11.5px; margin-bottom: 12px; }
  .cover-rule { border-top: 2px solid #1e4d6b; margin: 10px 0 20px; }

  h2 { font-size: 16px; border-bottom: 1.5px solid #1e4d6b; padding-bottom: 5px; margin: 22px 0 8px; page-break-after: avoid; }
  h2 .num { font-family: "DejaVu Sans Mono", monospace; font-size: 10px; color: #b45309; margin-right: 6px; }
  h3 { font-size: 12px; margin: 12px 0 5px; font-weight: bold; font-family: "DejaVu Sans", sans-serif; page-break-after: avoid; }
  .role { font-size: 8.5px; font-family: "DejaVu Sans Mono", monospace; text-transform: uppercase; color: #b45309; }

  p { margin: 0 0 7px; }
  ul.plain { margin: 0 0 8px; padding-left: 14px; }
  ul.plain li { margin-bottom: 4px; }
  ol.steps { margin: 0 0 8px; padding-left: 16px; }
  ol.steps li { margin-bottom: 5px; }

  .route-path { font-family: "DejaVu Sans Mono", monospace; font-size: 9.5px; background: #f7f5f0; border: 1px solid #ddd7ca; padding: 5px 9px; color: #1e4d6b; margin: 4px 0 10px; }

  .callout { border: 1px solid #e8c58a; background: #fdf1de; padding: 7px 11px; margin: 8px 0; }
  .callout .callout-label { font-family: "DejaVu Sans Mono", monospace; font-size: 8px; letter-spacing: .5px; text-transform: uppercase; color: #b45309; font-weight: bold; margin-bottom: 3px; }
  .callout p:last-child { margin-bottom: 0; }

  .section { page-break-inside: avoid; }
</style>
</head>
<body>

<div class="eyebrow">TransGest</div>
<h1>Manuel d'utilisation</h1>
<p class="subtitle">De la connexion au dernier module, dans l'ordre où vous en avez besoin.</p>
<div class="cover-rule"></div>

<div class="section">
<h2><span class="num">00</span>Connexion</h2>
<p>Tout commence sur l'écran de connexion (<em>admin/Loguins</em>), commun à tous les rôles.</p>
<ol class="steps">
  <li>Saisir l'<strong>adresse e-mail</strong> et le <strong>mot de passe</strong> du compte.</li>
  <li>Valider — en cas d'erreur (compte inconnu, mot de passe incorrect, compte désactivé), un message explicite s'affiche.</li>
  <li>Une fois connecté, vous arrivez sur <strong>Accueil</strong>, le tableau de bord. Le menu de gauche s'adapte automatiquement à votre rôle et à vos permissions : vous ne voyez que ce que vous êtes autorisé à utiliser.</li>
</ol>
<div class="callout">
  <div class="callout-label">Les rôles du système</div>
  <ul class="plain">
    <li><strong>Admin</strong> — administrateur d'une compagnie : accès complet à tous les modules de sa compagnie.</li>
    <li><strong>chef_d_escale</strong> — responsable d'une gare précise : accès aux opérations de sa gare (billets, colis, caisse, dépenses, location de cars), certaines actions restant soumises à validation de l'Admin.</li>
    <li><strong>Utilisateur</strong> — compte opérationnel simple (ex : agent billetterie), accès restreint aux écrans nécessaires à sa tâche.</li>
  </ul>
</div>
</div>

<div class="section">
<h2><span class="num">01</span>Configuration <span class="role">Admin</span></h2>
<p>C'est la première chose à mettre en place : rien d'autre ne fonctionne tant que gares, escales, horaires et cars ne sont pas configurés.</p>
<div class="route-path">Menu &rarr; Paramètre &rarr; Configuration</div>

<h3>Utilisateurs &amp; permissions</h3>
<p>Création des comptes de la compagnie (nom, e-mail, mot de passe, rôle, gare d'affectation pour un chef d'escale/Utilisateur). Chaque compte reçoit automatiquement les permissions par défaut de son rôle ; elles restent ensuite ajustables individuellement.</p>

<h3>Gares</h3>
<p>Les points de départ/arrivée de la compagnie (localité + numéro de gare). Saisie possible en plusieurs lignes à la fois, tout ou rien : si une ligne est invalide, aucune n'est enregistrée.</p>

<h3>Escale</h3>
<p>Les arrêts intermédiaires possibles entre deux gares, utilisés ensuite lors de la création d'un trajet.</p>

<h3>Horaire</h3>
<p>Les créneaux horaires de départ proposés lors de la programmation d'un voyage.</p>

<h3>Cars &amp; Chauffeurs</h3>
<p>Le parc de véhicules de la compagnie et les chauffeurs qui leur sont associés.</p>

<h3>Place limite</h3>
<p>Le nombre de places par défaut appliqué lors de la création d'un trajet/voyage.</p>
</div>

<div class="section">
<h2><span class="num">02</span>Programmation <span class="role">Admin</span></h2>
<p>Une fois gares, escales, horaires et cars configurés, on peut créer les trajets et les programmer réellement.</p>
<div class="route-path">Menu &rarr; G-programme</div>

<h3>Programme du voyage</h3>
<p>Création d'un trajet : gare de départ, destination, une ou plusieurs escales et horaires (sélection multiple), et le tarif. Le trajet retour est créé automatiquement pour chaque horaire choisi. Le système détecte et signale les doublons.</p>

<h3>Affectation des cars</h3>
<p>Association d'un ou plusieurs cars à un ou plusieurs trajets déjà créés. Un car déjà affecté à un trajet est grisé pour éviter un doublon.</p>

<h3>Programmation du voyage</h3>
<p>Vue de la programmation journalière effective : quel car part de quelle gare, à quelle heure, ce jour précis.</p>

<h3>Transferts entre gares</h3>
<p>En cas de voyage annulé ou perturbé, permet de transférer les passagers concernés vers un autre voyage/gare, avec le mouvement de caisse correspondant.</p>
</div>

<div class="section">
<h2><span class="num">03</span>Caisse</h2>
<p>La caisse doit être ouverte avant de pouvoir vendre un billet, enregistrer un colis, une dépense ou une location de car.</p>
<div class="route-path">Menu &rarr; Gestion de caisse</div>

<h3>Ma Caisse</h3>
<p>Chaque utilisateur ouvre sa propre caisse individuelle en début de service (montant initial), et la ferme en fin de journée (comptage réel, écart éventuel). Tant qu'elle n'est pas ouverte, rien ne peut lui être imputé.</p>

<h3>Supervision Escale <span class="role">chef d'escale / Admin</span></h3>
<p>Vue d'ensemble de toutes les caisses individuelles ouvertes à une gare, avec clôture d'escale en fin de journée.</p>

<h3>Rapport Compagnie <span class="role">Admin</span></h3>
<p>Vue consolidée de toutes les caisses, toutes gares confondues.</p>

<h3>Bilan de caisse</h3>
<p>Bilans détaillés des recettes billets et colis par caisse.</p>
</div>

<div class="section">
<h2><span class="num">04</span>Billets</h2>
<div class="route-path">Menu &rarr; G-réservation</div>

<h3>Achat de ticket</h3>
<p>Vente d'un billet : gare de départ, destination, horaire, informations du client, numéro de place.</p>

<h3>Liste des tickets</h3>
<p>Liste d'embarquement du jour (et de demain) : qui doit embarquer, sur quel car, à quelle heure. Impression du reçu (câble/USB ou WiFi), report du voyage, demande/validation d'annulation, export PDF filtré.</p>

<h3>Ticket en entente</h3>
<p>Validation des réservations faites en ligne, avant qu'elles n'apparaissent comme billets définitifs.</p>

<h3>Demandes d'annulation <span class="role">Admin</span></h3>
<p>Un chef d'escale ne peut que demander l'annulation d'un billet ; l'Admin valide (ou refuse) définitivement. L'Admin peut annuler directement.</p>

<h3>Rapport billets</h3>
<p>Rapport mensuel et rapport annuel des ventes de billets.</p>
</div>

<div class="section">
<h2><span class="num">05</span>Colis</h2>
<div class="route-path">Menu &rarr; G-colis</div>

<h3>Liste des colis</h3>
<p>Prise en charge d'un nouveau colis (expéditeur, destinataire, nature, valeur, frais, gare de destination) — génère un code unique. Export PDF A4 filtré par destination/statut.</p>

<h3>Envoi des colis</h3>
<p>Affectation d'un colis pris en charge à un car/trajet précis pour l'expédier réellement.</p>

<h3>Mouvement des colis</h3>
<p>Suivi des colis actuellement en transit entre deux gares.</p>

<h3>Livraison des colis</h3>
<p>Remise du colis au destinataire à l'arrivée : recherche par code, marquage comme livré.</p>

<h3>Réclamation</h3>
<p>Suivi des réclamations liées à un colis.</p>

<h3>Historique</h3>
<p>Historique complet des colis enregistrés et des colis livrés.</p>
</div>

<div class="section">
<h2><span class="num">06</span>Finances <span class="role">Admin / chef d'escale</span></h2>
<div class="route-path">Menu &rarr; Finances</div>

<h3>Dépenses</h3>
<p>Enregistrement d'une dépense, locale (rattachée à une gare) ou globale (Admin uniquement). Créée par un chef d'escale : en attente de validation. Créée par l'Admin : déduite immédiatement.</p>

<h3>Bénéfice de la compagnie <span class="role">Admin</span></h3>
<p>Vue consolidée : revenus billets + colis + location de cars, moins remboursements et dépenses, sur une période.</p>

<h3>Location des cars</h3>
<p>Location ponctuelle d'un car à un client, en dehors des trajets programmés (destination libre, dates, coordonnées client, frais). Chef d'escale : demande en attente de validation. Admin : location effective directement.</p>
</div>

<div class="section">
<h2><span class="num">07</span>Banque <span class="role">Admin / chef d'escale</span></h2>
<div class="route-path">Menu &rarr; Dépôt en banque</div>

<h3>Comptes banque <span class="role">Admin</span></h3>
<p>Gestion des comptes bancaires de la compagnie.</p>

<h3>Faire un dépôt</h3>
<p>Enregistrement d'un dépôt en banque (montant retiré de la caisse physique, versé sur un compte).</p>

<h3>Demandes en attente <span class="role">Admin</span></h3>
<p>Confirmation ou rejet des dépôts déclarés par les gares.</p>

<h3>Historique des dépôts</h3>
<p>Historique de tous les dépôts effectués.</p>
</div>

<div class="section">
<h2><span class="num">08</span>Impression des reçus</h2>
<p>Concerne les reçus de billets et de colis.</p>

<h3>Imprimante câble/USB</h3>
<p>Ouvre un PDF classique, imprimable via le pilote Windows habituel.</p>

<h3>Imprimante thermique WiFi</h3>
<p>Nécessite d'avoir installé une fois le « pont d'impression » sur le PC connecté à l'imprimante réseau.</p>

<h3>Impression depuis un téléphone</h3>
<p>Le PC hébergeant le pont doit être installé avec l'option réseau local activée. Sur le téléphone (même Wi-Fi), la première tentative échoue et propose « Configurer l'adresse » : y saisir l'adresse affichée par le PC (ex : 192.168.1.50:9200). Elle est ensuite mémorisée sur ce téléphone.</p>
</div>

</body>
</html>
