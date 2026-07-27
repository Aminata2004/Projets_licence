<?php $this->view('admin/partials/headers') ?>

<?php
// Capture d'ecran annotee : cercle(s)/fleche(s) en SVG superposes en coordonnees natives
// de l'image (viewBox = dimensions reelles du fichier), donc precises quelle que soit la
// taille d'affichage. $marks : liste de ['type'=>'circle','cx','cy','rx','ry'] ou
// ['type'=>'arrow','x1','y1','x2','y2'].
function docShot($file, $w, $h, $alt, $marks = [])
{
    $uid = md5($file);
    echo '<div class="doc-shot-img"><img src="' . BASE_URL . '/images/documentation/' . $file . '" alt="' . htmlspecialchars($alt) . '" loading="lazy">';
    echo '<svg class="doc-mark-svg" viewBox="0 0 ' . $w . ' ' . $h . '" preserveAspectRatio="none">';
    echo '<defs><marker id="arrow-' . $uid . '" markerWidth="9" markerHeight="9" refX="7" refY="4.5" orient="auto"><path d="M0,0 L9,4.5 L0,9 Z" fill="#ff6a00"/></marker></defs>';
    foreach ($marks as $m) {
        if ($m['type'] === 'circle') {
            echo '<ellipse cx="' . $m['cx'] . '" cy="' . $m['cy'] . '" rx="' . $m['rx'] . '" ry="' . $m['ry'] . '" class="doc-mark-circle" />';
        } elseif ($m['type'] === 'arrow') {
            echo '<line x1="' . $m['x1'] . '" y1="' . $m['y1'] . '" x2="' . $m['x2'] . '" y2="' . $m['y2'] . '" class="doc-mark-arrow" marker-end="url(#arrow-' . $uid . ')" />';
        }
    }
    echo '</svg></div>';
}
?>

<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary">
                    <i class="bx bx-book-open me-1"></i> Documentation
                </div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manuel d'utilisation</li>
                        </ol>
                    </nav>
                </div>
                <a href="<?= BASE_URL ?>/admin/Documentations/pdf" target="_blank"
                    class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                    <i class="bx bx-file-blank me-1"></i> Télécharger en PDF
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4 doc-intro">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-2">Manuel d'utilisation — TransGest</h4>
                    <p class="mb-2 text-muted">Ce guide couvre l'ensemble du système, de la connexion au dernier module, dans l'ordre où vous en avez besoin&nbsp;: on configure d'abord ce qui ne change pas souvent (gares, horaires, cars), puis on programme les voyages, et enfin on utilise les écrans du quotidien (billets, colis, caisse).</p>
                    <p class="mb-0 text-muted"><span class="badge bg-secondary">Accessible à tous les comptes</span> Cette page ne dépend d'aucune permission — chaque utilisateur ne verra bien sûr, dans le reste de l'application, que les écrans autorisés par son rôle.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="doc-toc-label">Sommaire</div>
                    <div class="doc-toc">
                        <a href="#doc-connexion"><span>00</span>Connexion</a>
                        <a href="#doc-configuration"><span>01</span>Configuration</a>
                        <a href="#doc-programmation"><span>02</span>Programmation</a>
                        <a href="#doc-caisse"><span>03</span>Caisse</a>
                        <a href="#doc-billets"><span>04</span>Billets</a>
                        <a href="#doc-colis"><span>05</span>Colis</a>
                        <a href="#doc-finances"><span>06</span>Finances</a>
                        <a href="#doc-banque"><span>07</span>Banque</a>
                        <a href="#doc-impression"><span>08</span>Impression</a>
                    </div>
                </div>
            </div>

            <!-- ================= 00. CONNEXION ================= -->
            <section id="doc-connexion" class="doc-section">
                <h2><span class="doc-num">00</span>Connexion</h2>
                <p>Tout commence sur l'écran de connexion (<code>admin/Loguins</code>), commun à tous les rôles.</p>
                <ol class="doc-steps">
                    <li>Saisir l'<strong>adresse e-mail</strong> et le <strong>mot de passe</strong> du compte.</li>
                    <li>Valider — en cas d'erreur (compte inconnu, mot de passe incorrect, compte désactivé), un message explicite s'affiche.</li>
                    <li>Une fois connecté, vous arrivez sur <strong>Accueil</strong>, le tableau de bord. Le menu de gauche s'adapte automatiquement à votre rôle et à vos permissions&nbsp;: vous ne voyez que ce que vous êtes autorisé à utiliser.</li>
                </ol>
                <?php docShot('login.png', 1902, 901, "Écran de connexion TransGest", [
                    ['type' => 'circle', 'cx' => 1467, 'cy' => 698, 'rx' => 250, 'ry' => 35],
                    ['type' => 'arrow', 'x1' => 1467, 'y1' => 600, 'x2' => 1467, 'y2' => 660],
                ]); ?>

                <div class="doc-callout">
                    <div class="doc-callout-label">Les rôles du système</div>
                    <ul class="doc-plain">
                        <li><strong>Admin</strong> — administrateur d'une compagnie&nbsp;: accès complet à tous les modules de sa compagnie.</li>
                        <li><strong>chef_d_escale</strong> — responsable d'une gare précise&nbsp;: accès aux opérations de sa gare (billets, colis, caisse, dépenses, location de cars), certaines actions restant soumises à validation de l'Admin.</li>
                        <li><strong>Utilisateur</strong> — compte opérationnel simple (ex&nbsp;: agent billetterie), accès restreint aux écrans nécessaires à sa tâche.</li>
                    </ul>
                </div>
            </section>

            <!-- ================= 01. CONFIGURATION ================= -->
            <section id="doc-configuration" class="doc-section">
                <h2><span class="doc-num">01</span>Configuration</h2>
                <p class="text-muted">Réservée à l'<strong>Admin</strong>. C'est la première chose à mettre en place&nbsp;: rien d'autre ne fonctionne tant que gares, escales, horaires et cars ne sont pas configurés.</p>
                <div class="route-path">Menu → Paramètre → Configuration</div>

                <h3>Utilisateurs & permissions</h3>
                <p>Création des comptes de la compagnie (nom, e-mail, mot de passe, rôle, gare d'affectation pour un chef d'escale/Utilisateur). Chaque compte reçoit automatiquement les permissions par défaut de son rôle&nbsp;; elles restent ensuite ajustables individuellement sur l'écran d'assignation des permissions.</p>
                <?php docShot('gares.png', 1920, 1080, "Onglets de la Configuration, avec l'onglet Utilisateur", [
                    ['type' => 'circle', 'cx' => 544, 'cy' => 418, 'rx' => 140, 'ry' => 30],
                ]); ?>
                <p class="text-muted" style="font-size:13px;">Ci-dessus&nbsp;: l'onglet « Utilisateur » (menu vertical à gauche du panneau Configuration) donne accès à la liste des comptes et au formulaire de création.</p>
                <div class="doc-callout">
                    <div class="doc-callout-label">Nouveau module ajouté au système</div>
                    <p class="mb-0">Une nouvelle permission n'est automatiquement donnée qu'aux comptes créés <em>après</em> son ajout. Pour un compte existant, il faut la cocher manuellement sur cet écran.</p>
                </div>

                <h3>Gares</h3>
                <p>Les points de départ/arrivée de la compagnie (localité + numéro de gare). Saisie possible en plusieurs lignes à la fois via des cases à cocher, tout ou rien&nbsp;: si une ligne est invalide, aucune n'est enregistrée.</p>
                <?php docShot('gares.png', 1920, 1080, "Liste des gares", [
                    ['type' => 'circle', 'cx' => 1747, 'cy' => 540, 'rx' => 62, 'ry' => 26],
                ]); ?>

                <h3>Escale</h3>
                <p>Les arrêts intermédiaires possibles entre deux gares, utilisés ensuite lors de la création d'un trajet (section Programmation).</p>
                <?php docShot('escale.png', 1920, 1080, "Liste des escales, bouton Ajouter", [
                    ['type' => 'circle', 'cx' => 1620, 'cy' => 238, 'rx' => 83, 'ry' => 28],
                ]); ?>

                <h3>Horaire</h3>
                <p>Les créneaux horaires de départ proposés lors de la programmation d'un voyage (ex&nbsp;: 06h00, 14h00, 20h00).</p>
                <?php docShot('horaire.png', 1920, 1080, "Liste des horaires", [
                    ['type' => 'circle', 'cx' => 1560, 'cy' => 448, 'rx' => 40, 'ry' => 18],
                ]); ?>

                <h3>Cars & Chauffeurs</h3>
                <p>Le parc de véhicules de la compagnie (numéro de car, matricule, nombre de places) et les chauffeurs qui leur sont associés.</p>
                <?php docShot('cars-chauffeurs.png', 1920, 1080, "Liste des cars, onglet Chauffeurs", [
                    ['type' => 'circle', 'cx' => 989, 'cy' => 351, 'rx' => 110, 'ry' => 28],
                ]); ?>

                <h3>Place limite</h3>
                <p>Le nombre de places par défaut appliqué lors de la création d'un trajet/voyage.</p>
            </section>

            <!-- ================= 02. PROGRAMMATION ================= -->
            <section id="doc-programmation" class="doc-section">
                <h2><span class="doc-num">02</span>Programmation</h2>
                <p class="text-muted">Une fois gares, escales, horaires et cars configurés (section précédente), on peut créer les trajets et les programmer réellement. Réservée à l'<strong>Admin</strong> et, pour la programmation journalière, au <strong>chef d'escale</strong>.</p>
                <div class="route-path">Menu → G-programme</div>

                <h3>Programme du voyage</h3>
                <p>Création d'un <strong>trajet</strong>&nbsp;: gare de départ, destination, une ou plusieurs escales et horaires (sélection multiple par cases à cocher), et le tarif. Le trajet retour (destination → départ) est créé automatiquement pour chaque horaire choisi. Le système détecte et signale les doublons (même départ/destination/horaire déjà existant).</p>
                <?php docShot('programme-voyage.png', 1920, 1080, "Liste des programmes de voyage, bouton Ajouter", [
                    ['type' => 'circle', 'cx' => 1654, 'cy' => 282, 'rx' => 83, 'ry' => 28],
                ]); ?>

                <h3>Affectation des cars</h3>
                <p>Association d'un ou plusieurs cars à un ou plusieurs trajets déjà créés (cases à cocher également, avec raccourci « Tout cocher »). Un car déjà affecté à un trajet est grisé pour éviter un doublon.</p>
                <div class="doc-shot"><i class="bx bx-image-alt"></i>Capture d'écran à ajouter — affectation des cars aux trajets</div>

                <h3>Programmation du voyage</h3>
                <p>Vue de la programmation journalière effective&nbsp;: quel car part de quelle gare, à quelle heure, ce jour précis. C'est cette liste qui alimente ensuite l'écran <strong>Liste des tickets</strong> (billets) pour la vente.</p>
                <div class="doc-shot"><i class="bx bx-image-alt"></i>Capture d'écran à ajouter — programmation journalière</div>

                <h3>Transferts entre gares</h3>
                <p>En cas de voyage annulé ou perturbé, permet de transférer les passagers concernés vers un autre voyage/gare, avec le mouvement de caisse correspondant (montant déplacé de la caisse source vers la caisse destination).</p>
                <?php docShot('transferts-gares.png', 1920, 1080, "Historique des transferts, lien Programmation du voyage", [
                    ['type' => 'circle', 'cx' => 182, 'cy' => 596, 'rx' => 155, 'ry' => 50],
                ]); ?>
            </section>

            <!-- ================= 03. CAISSE ================= -->
            <section id="doc-caisse" class="doc-section">
                <h2><span class="doc-num">03</span>Caisse</h2>
                <p class="text-muted">La caisse doit être <strong>ouverte</strong> avant de pouvoir vendre un billet, enregistrer un colis, une dépense ou une location de car — ces montants viennent s'y créditer/débiter automatiquement.</p>
                <div class="route-path">Menu → Gestion de caisse</div>

                <h3>Ma Caisse</h3>
                <p>Chaque utilisateur (Utilisateur, chef d'escale, Admin) ouvre sa <strong>propre caisse individuelle</strong> en début de service (montant initial en espèces), et la ferme en fin de journée (comptage réel, écart éventuel avec le montant attendu). Tant qu'elle n'est pas ouverte, aucune vente ni aucune dépense ne peut lui être imputée.</p>
                <?php docShot('ma-caisse.png', 1573, 882, "Ma Caisse, bouton Ouvrir ma caisse", [
                    ['type' => 'circle', 'cx' => 789, 'cy' => 570, 'rx' => 145, 'ry' => 35],
                ]); ?>

                <h3>Supervision Escale <span class="doc-role role-chef">chef d'escale · Admin</span></h3>
                <p>Vue d'ensemble de toutes les caisses individuelles ouvertes à une gare&nbsp;: qui a ouvert, quel montant, permet aussi de clôturer une escale (ensemble des caisses de la gare) en fin de journée.</p>
                <?php docShot('supervision-escale.png', 1920, 1080, "Supervision Escale, bouton Procéder à la clôture", [
                    ['type' => 'circle', 'cx' => 1641, 'cy' => 614, 'rx' => 220, 'ry' => 28],
                ]); ?>

                <h3>Rapport Compagnie <span class="doc-role role-admin">Admin</span></h3>
                <p>Vue consolidée de toutes les caisses, toutes gares confondues, pour l'Admin.</p>
                <?php docShot('rapport-compagnie.png', 1920, 1080, "Rapport Compagnie, chiffre d'affaires global", [
                    ['type' => 'circle', 'cx' => 612, 'cy' => 382, 'rx' => 235, 'ry' => 165],
                ]); ?>

                <h3>Bilan de caisse</h3>
                <p>Bilans détaillés des recettes billets et colis par caisse.</p>
                <?php docShot('bilan-caisse.png', 1920, 1080, "Bilan de caisse, onglets Billets / Colis", [
                    ['type' => 'circle', 'cx' => 783, 'cy' => 378, 'rx' => 390, 'ry' => 38],
                ]); ?>
            </section>

            <!-- ================= 04. BILLETS ================= -->
            <section id="doc-billets" class="doc-section">
                <h2><span class="doc-num">04</span>Billets</h2>
                <div class="route-path">Menu → G-réservation</div>

                <h3>Achat de ticket</h3>
                <p>Vente d'un billet&nbsp;: gare de départ, destination, horaire, informations du client, numéro de place. Les champs se mettent à jour dynamiquement (sans recharger la page) au fur et à mesure des choix.</p>
                <?php docShot('achat-ticket.png', 1920, 1080, "Formulaire d'achat de ticket", [
                    ['type' => 'circle', 'cx' => 1362, 'cy' => 362, 'rx' => 480, 'ry' => 30],
                    ['type' => 'circle', 'cx' => 969, 'cy' => 968, 'rx' => 95, 'ry' => 28],
                    ['type' => 'arrow', 'x1' => 300, 'y1' => 400, 'x2' => 300, 'y2' => 940],
                ]); ?>

                <h3>Liste des tickets</h3>
                <p>Liste d'embarquement du jour (et de demain, onglet séparé)&nbsp;: qui doit embarquer, sur quel car, à quelle heure. Depuis cette liste&nbsp;: impression du reçu (câble/USB ou imprimante WiFi), report du voyage, demande/validation d'annulation, et export PDF A4 de la liste filtrée par destination/heure.</p>
                <?php docShot('liste-tickets.png', 1920, 1080, "Liste des tickets du jour, onglet Liste de demain", [
                    ['type' => 'circle', 'cx' => 679, 'cy' => 364, 'rx' => 150, 'ry' => 28],
                    ['type' => 'circle', 'cx' => 1767, 'cy' => 820, 'rx' => 75, 'ry' => 25],
                ]); ?>

                <h3>Ticket en entente</h3>
                <p>Validation des réservations faites en ligne (site public) ou en attente de confirmation de paiement, avant qu'elles n'apparaissent comme billets définitifs.</p>
                <?php docShot('ticket-entente.png', 1920, 1080, "Liste des tickets en entente", [
                    ['type' => 'circle', 'cx' => 1776, 'cy' => 452, 'rx' => 75, 'ry' => 25],
                ]); ?>

                <h3>Demandes d'annulation <span class="doc-role role-admin">Admin</span></h3>
                <p>Un chef d'escale ne peut que <em>demander</em> l'annulation d'un billet&nbsp;; c'est l'Admin qui valide (ou refuse) définitivement ici. L'Admin, lui, peut annuler directement sans passer par cette étape.</p>
                <?php docShot('demandes-annulation.png', 1920, 1080, "Demandes d'annulation en attente", [
                    ['type' => 'circle', 'cx' => 650, 'cy' => 341, 'rx' => 265, 'ry' => 22],
                ]); ?>

                <h3>Rapport billets</h3>
                <p>Rapport mensuel et rapport annuel des ventes de billets.</p>
                <?php docShot('rapport-billets.png', 1920, 1080, "Rapport mensuel, lien Rapport annuel", [
                    ['type' => 'circle', 'cx' => 168, 'cy' => 623, 'rx' => 130, 'ry' => 32],
                ]); ?>
            </section>

            <!-- ================= 05. COLIS ================= -->
            <section id="doc-colis" class="doc-section">
                <h2><span class="doc-num">05</span>Colis</h2>
                <div class="route-path">Menu → G-colis</div>

                <h3>Liste des colis</h3>
                <p>Prise en charge d'un nouveau colis (expéditeur, destinataire, nature, valeur, frais de transport, gare de destination) — génère un code unique. Export possible en PDF A4, avec filtre par destination/statut, reprenant les informations du reçu.</p>
                <?php docShot('liste-colis.png', 1920, 1080, "Liste des colis, boutons Ajouter et Exporter en PDF", [
                    ['type' => 'circle', 'cx' => 1648, 'cy' => 264, 'rx' => 105, 'ry' => 32],
                    ['type' => 'circle', 'cx' => 570, 'cy' => 557, 'rx' => 165, 'ry' => 32],
                ]); ?>

                <h3>Envoi des colis</h3>
                <p>Affectation d'un colis pris en charge à un car/trajet précis pour l'expédier réellement.</p>
                <?php docShot('envoi-colis.png', 1920, 1080, "Envoi des colis, choix du car et bouton Enregistrer", [
                    ['type' => 'circle', 'cx' => 640, 'cy' => 422, 'rx' => 245, 'ry' => 30],
                    ['type' => 'circle', 'cx' => 1756, 'cy' => 616, 'rx' => 95, 'ry' => 32],
                    ['type' => 'arrow', 'x1' => 640, 'y1' => 460, 'x2' => 1700, 'y2' => 600],
                ]); ?>

                <h3>Mouvement des colis</h3>
                <p>Suivi des colis actuellement en transit entre deux gares.</p>
                <?php docShot('mouvement-colis.png', 1920, 1080, "Mouvement des colis, onglets En attente / Reçu / Livré", [
                    ['type' => 'circle', 'cx' => 727, 'cy' => 506, 'rx' => 335, 'ry' => 30],
                ]); ?>

                <h3>Livraison des colis</h3>
                <p>Remise du colis au destinataire à l'arrivée&nbsp;: recherche par code, marquage comme livré.</p>
                <?php docShot('livraison-colis.png', 1920, 1080, "Livraison des colis, champ code et bouton Valider", [
                    ['type' => 'circle', 'cx' => 1128, 'cy' => 421, 'rx' => 730, 'ry' => 28],
                    ['type' => 'circle', 'cx' => 463, 'cy' => 498, 'rx' => 70, 'ry' => 28],
                ]); ?>

                <h3>Réclamation</h3>
                <p>Suivi des réclamations liées à un colis (perte, retard, dommage).</p>
                <?php docShot('reclamation.png', 1920, 1080, "Réclamation, recherche d'un colis", [
                    ['type' => 'circle', 'cx' => 888, 'cy' => 459, 'rx' => 75, 'ry' => 25],
                ]); ?>

                <h3>Historique</h3>
                <p>Historique complet des colis enregistrés et des colis livrés.</p>
                <?php docShot('historique-colis.png', 1920, 1080, "Historique des colis, onglet colis livré", [
                    ['type' => 'circle', 'cx' => 869, 'cy' => 348, 'rx' => 128, 'ry' => 24],
                ]); ?>
            </section>

            <!-- ================= 06. FINANCES ================= -->
            <section id="doc-finances" class="doc-section">
                <h2><span class="doc-num">06</span>Finances<span class="doc-role role-chef">Admin · chef d'escale</span></h2>
                <div class="route-path">Menu → Finances</div>

                <h3>Dépenses</h3>
                <p>Enregistrement d'une dépense, <strong>locale</strong> (rattachée à une gare et déduite de sa caisse) ou <strong>globale</strong> (à l'échelle de la compagnie, Admin uniquement). Une dépense créée par un chef d'escale reste <strong>en attente</strong> jusqu'à validation par l'Admin&nbsp;; créée par l'Admin, elle est déduite immédiatement.</p>
                <?php docShot('depenses.png', 1920, 1080, "Gestion des dépenses, catégorie et bouton Enregistrer", [
                    ['type' => 'circle', 'cx' => 629, 'cy' => 466, 'rx' => 235, 'ry' => 28],
                    ['type' => 'circle', 'cx' => 501, 'cy' => 652, 'rx' => 105, 'ry' => 30],
                ]); ?>

                <h3>Bénéfice de la compagnie <span class="doc-role role-admin">Admin</span></h3>
                <p>Vue consolidée&nbsp;: revenus billets + colis + location de cars, moins les remboursements et les dépenses, sur une période (jour / mois / depuis le début).</p>
                <?php docShot('benefice-compagnie.png', 1920, 1080, "Bénéfice de la compagnie, revenus location de cars et bénéfice net", [
                    ['type' => 'circle', 'cx' => 1306, 'cy' => 477, 'rx' => 180, 'ry' => 58],
                    ['type' => 'circle', 'cx' => 1117, 'cy' => 848, 'rx' => 280, 'ry' => 60],
                ]); ?>

                <h3>Location des cars</h3>
                <p>Location ponctuelle d'un car à un client, en dehors des trajets programmés (destination libre, dates, coordonnées client, frais). Un chef d'escale peut créer une demande (en attente de validation), l'Admin crée directement une location effective. Un manuel dédié, plus détaillé, existe pour ce module précis.</p>
                <?php docShot('location-cars.png', 1920, 1080, "Formulaire et liste des locations de cars", [
                    ['type' => 'circle', 'cx' => 1604, 'cy' => 467, 'rx' => 230, 'ry' => 28],
                    ['type' => 'circle', 'cx' => 501, 'cy' => 654, 'rx' => 105, 'ry' => 30],
                    ['type' => 'circle', 'cx' => 1775, 'cy' => 881, 'rx' => 58, 'ry' => 18],
                    ['type' => 'arrow', 'x1' => 1604, 'y1' => 495, 'x2' => 550, 'y2' => 630],
                ]); ?>
            </section>

            <!-- ================= 07. BANQUE ================= -->
            <section id="doc-banque" class="doc-section">
                <h2><span class="doc-num">07</span>Banque<span class="doc-role role-chef">Admin · chef d'escale</span></h2>
                <div class="route-path">Menu → Dépôt en banque</div>

                <h3>Comptes banque <span class="doc-role role-admin">Admin</span></h3>
                <p>Gestion des comptes bancaires de la compagnie, vers lesquels les recettes en espèces sont déposées.</p>
                <?php docShot('comptes-banque.png', 1920, 1080, "Comptes banque, boutons Demandes en attente et Nouveau compte", [
                    ['type' => 'circle', 'cx' => 1758, 'cy' => 282, 'rx' => 90, 'ry' => 24],
                    ['type' => 'circle', 'cx' => 1502, 'cy' => 282, 'rx' => 125, 'ry' => 24],
                ]); ?>

                <h3>Faire un dépôt</h3>
                <p>Enregistrement d'un dépôt en banque (montant retiré de la caisse physique, versé sur un compte).</p>
                <?php docShot('depot-banque.png', 1920, 1080, "Nouvelle demande de dépôt, bouton Envoyer la demande", [
                    ['type' => 'circle', 'cx' => 545, 'cy' => 569, 'rx' => 150, 'ry' => 30],
                ]); ?>

                <h3>Demandes en attente <span class="doc-role role-admin">Admin</span></h3>
                <p>Confirmation ou rejet des dépôts déclarés par les gares, avant qu'ils ne soient définitivement comptabilisés.</p>

                <h3>Historique des dépôts</h3>
                <p>Historique de tous les dépôts effectués.</p>
                <?php docShot('historique-depots.png', 1920, 1080, "Historique des dépôts en banque", [
                    ['type' => 'circle', 'cx' => 735, 'cy' => 342, 'rx' => 360, 'ry' => 22],
                ]); ?>
            </section>

            <!-- ================= 08. IMPRESSION ================= -->
            <section id="doc-impression" class="doc-section">
                <h2><span class="doc-num">08</span>Impression des reçus</h2>
                <p class="text-muted">Concerne les reçus de billets et de colis, imprimables depuis leurs listes respectives.</p>

                <h3>Imprimante câble/USB</h3>
                <p>Ouvre un PDF classique, imprimable via le pilote Windows habituel de l'imprimante branchée au PC.</p>
                <?php docShot('impression-billet.png', 1920, 1080, "Menu d'impression d'un billet : câble/USB ou WiFi", [
                    ['type' => 'circle', 'cx' => 1604, 'cy' => 705, 'rx' => 190, 'ry' => 20],
                ]); ?>

                <h3>Imprimante thermique WiFi</h3>
                <p>Nécessite d'avoir installé une fois le « pont d'impression » sur le PC connecté à l'imprimante réseau (script <code>install-windows.ps1</code>, fourni séparément à l'équipe technique).</p>
                <?php docShot('impression-billet.png', 1920, 1080, "Menu d'impression, option imprimante WiFi", [
                    ['type' => 'circle', 'cx' => 1573, 'cy' => 747, 'rx' => 160, 'ry' => 22],
                ]); ?>
                <?php docShot('impression-billet-2.png', 1908, 897, "Menu d'impression d'un colis, option imprimante WiFi", [
                    ['type' => 'circle', 'cx' => 1548, 'cy' => 818, 'rx' => 220, 'ry' => 24],
                ]); ?>

                <h3>Impression depuis un téléphone</h3>
                <p>Le PC hébergeant le pont doit être installé avec l'option réseau local activée. Sur le téléphone (même Wi-Fi), la première tentative d'impression échoue et propose « Configurer l'adresse »&nbsp;: y saisir l'adresse affichée par le PC (ex&nbsp;: <code>192.168.1.50:9200</code>). Elle est ensuite mémorisée sur ce téléphone pour les prochaines impressions.</p>
                <div class="doc-shot"><i class="bx bx-image-alt"></i>Capture d'écran à ajouter — écran de configuration de l'adresse du pont sur téléphone</div>
            </section>

        </main>
    </div>

    <?php $this->view('admin/partials/foot') ?>

    <style>
        .doc-intro { border-left: 4px solid var(--bs-primary); }

        .doc-toc-label {
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #8a8a8a;
            margin-bottom: 10px;
        }

        .doc-toc {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .doc-toc a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 1px solid #dfe3e8;
            border-radius: 999px;
            font-size: 13.5px;
            text-decoration: none;
            color: #2c3e50;
        }

        .doc-toc a:hover { border-color: var(--bs-primary); color: var(--bs-primary); }

        .doc-toc a span {
            font-family: ui-monospace, "SF Mono", Consolas, monospace;
            font-size: 11px;
            color: #b45309;
        }

        .doc-section {
            background: #fff;
            border: 1px solid #e5e8ec;
            border-radius: .5rem;
            padding: 28px 32px;
            margin-bottom: 24px;
            scroll-margin-top: 16px;
        }

        .doc-section h2 {
            font-size: 22px;
            font-weight: 700;
            border-bottom: 2px solid var(--bs-primary);
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .doc-num {
            font-family: ui-monospace, "SF Mono", Consolas, monospace;
            font-size: 15px;
            color: #b45309;
            margin-right: 8px;
        }

        .doc-section h3 {
            font-size: 16px;
            font-weight: 700;
            margin: 22px 0 8px;
            color: #1c2128;
        }

        .doc-section p { max-width: 76ch; }

        .doc-role {
            display: inline-block;
            font-family: ui-monospace, "SF Mono", Consolas, monospace;
            font-size: 10.5px;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 3px;
            margin-left: 8px;
            vertical-align: 2px;
            font-weight: 600;
        }

        .role-admin { background: #e9f6ef; color: #1a7a4c; }
        .role-chef { background: #fdf1de; color: #b45309; }

        .doc-plain { list-style: none; padding-left: 0; margin: 0; max-width: 76ch; }
        .doc-plain li { position: relative; padding-left: 18px; margin-bottom: 8px; }
        .doc-plain li::before { content: "—"; position: absolute; left: 0; color: #8a8a8a; }

        .doc-steps { padding-left: 20px; max-width: 76ch; }
        .doc-steps li { margin-bottom: 8px; }

        .route-path {
            display: inline-flex;
            align-items: center;
            font-family: ui-monospace, "SF Mono", Consolas, monospace;
            font-size: 13px;
            background: #f7f5f0;
            border: 1px solid #e5e0d3;
            padding: 8px 14px;
            border-radius: 4px;
            margin: 6px 0 16px;
            color: #1e4d6b;
        }

        .doc-shot-img {
            position: relative;
            display: block;
            max-width: 100%;
            margin: 10px 0 6px;
            border: 1px solid #e5e8ec;
            border-radius: 6px;
            overflow: hidden;
            line-height: 0;
        }

        .doc-shot-img img { display: block; width: 100%; height: auto; }

        .doc-mark-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .doc-mark-circle {
            fill: none;
            stroke: #ff6a00;
            stroke-width: 7;
        }

        .doc-mark-arrow {
            stroke: #ff6a00;
            stroke-width: 6;
        }

        .doc-shot {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px dashed #c8ccd1;
            border-radius: 6px;
            padding: 14px 16px;
            margin: 10px 0 6px;
            color: #8a8a8a;
            font-size: 13.5px;
            max-width: 76ch;
            background: #fafbfc;
        }

        .doc-shot i { font-size: 20px; }

        .doc-callout {
            border: 1px solid #e8c58a;
            background: #fdf1de;
            border-left: 4px solid #b45309;
            border-radius: 4px;
            padding: 12px 16px;
            margin: 14px 0;
            max-width: 76ch;
        }

        .doc-callout-label {
            font-family: ui-monospace, "SF Mono", Consolas, monospace;
            font-size: 10.5px;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #b45309;
            font-weight: 700;
            margin-bottom: 6px;
        }
    </style>

</body>
</html>
