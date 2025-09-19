 public function saveBillets(): bool
 {
 extract($_POST);
 $pdo = $this->connect();

 // Validation basique
 if (empty($Client) || empty($destinationId) || empty($jourVoyage) || empty($programme)) {
 $this->set_flash("Tous les champs obligatoires doivent être remplis.", "danger");
 return false;
 }

 $aujourdhui = date('Y-m-d');
 $demain = date('Y-m-d', strtotime('+1 day'));
 if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
 $this->set_flash("Date invalide (aujourd’hui ou demain).", "danger");
 return false;
 }

 // Recherche du car programmé avec destinationId (pas escale)
 $sql = "SELECT id_car_programmer
 FROM programmation_voyage
 WHERE id_horaire = :id_horaire
 AND date_enregistre = :date_enregistre
 AND id_trajet = :id_trajet
 AND localite_user = :localite_user
 AND id_compagnie= :id_compagnie
 LIMIT 1";

 $idCarProg = $this->fetchOne($sql, [
 ':id_horaire' => $programme,
 ':date_enregistre' => $jourVoyage,
 ':id_trajet' => $destinationId,
 ':localite_user' => $_SESSION['ville'],
 ':id_compagnie' => $_SESSION['id_compagnie']
 ]);

 if (!$idCarProg) {
 $this->set_flash("Programme introuvable.", "danger");
 return false;
 }

 // Récupérer info du car
 $car = $this->fetchOne(
 "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
 [':num' => $idCarProg['id_car_programmer']]
 );

 if (!$car) {
 $this->set_flash("Car introuvable.", "danger");
 return false;
 }

 $dispo = $car['nbr_place'] - $car['nbr_place_reserve'];
 if ($nombrePassages > $dispo) {
 $this->set_flash("Places insuffisantes : $dispo restantes.", "danger");
 return false;
 }

 $start = (int)$car['nbr_place_reserve'] + 1;
 $nombrePassagesInt = (int)$nombrePassages;
 $end = $start + $nombrePassagesInt - 1;
 $numero_place = ($nombrePassagesInt == 1) ? "$start" : "$start-$end";


 // Choix du prix et destination finale selon escale
 if (!empty($escaleNom)) {
 // Prix manuel venant du champ montants_payers (note: vérifier nom exact du champ dans formulaire)
 $prixUtilise = !empty($montant_payers) ? $montant_payers : 0;
 $destFinale = $escaleNom; // Enregistre nom escale à la place de destinationId
 } else {
 $prixUtilise = $montant_payer; // Prix normal
 $destFinale = $destinationId; // Destination normale
 }

 try {
 $pdo->beginTransaction();

 // Insertion client
 $stmt = $pdo->prepare(
 "INSERT INTO client (Client, montant_payer, date_enregistrement, id_compagnie, idUser)
 VALUES (:c, :m, :d, :ic, :u)"
 );
 $stmt->execute([
 ':c' => $Client,
 ':m' => $prixUtilise,
 ':d' => date('Ymd'),
 ':ic' => $_SESSION['id_compagnie'],
 ':u' => $_SESSION['id_utilisateur']
 ]);
 $idClient = $pdo->lastInsertId();

 // Insertion billet
 $stmt = $pdo->prepare(
 "INSERT INTO billets (id_client, numeroBillets, jourVoyage, Heur_departs,
 nombrePassages, destinationId, departId, date_expiration,
 numeroPlace, date_reservation, status_reservation)
 VALUES (:cl, :num, :j, :h, :n, :dest, :dep, :exp, :place, :res, :stat)"
 );
 $stmt->execute([
 ':cl' => $idClient,
 ':num' => $numeroBillets,
 ':j' => $jourVoyage,
 ':h' => $programme,
 ':n' => $nombrePassages,
 ':dest' => $destFinale,
 ':dep' => $_SESSION['id_agence'],
 ':exp' => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
 ':place' => $numero_place,
 ':res' => date('Ymd'),
 ':stat' => 'presentiel'
 ]);

 // Mise à jour du car
 $stmt = $pdo->prepare(
 "UPDATE car
 SET nbr_place_reserve = nbr_place_reserve + :n
 WHERE numero_car = :num"
 );
 $stmt->execute([
 ':n' => (int)$nombrePassages,
 ':num' => trim($idCarProg['id_car_programmer'])
 ]);
 if ($stmt->rowCount() === 0) {
 $pdo->rollBack();
 $this->set_flash("Mise à jour du car échouée ou aucune modification détectée.", "danger");
 return false;
 }

 $pdo->commit();
 $this->set_flash("Réservation enregistrée avec succès.", "info");
 return true;
 } catch (Throwable $e) {
 $pdo->rollBack();
 $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
 return false;
 }
 }


 http://localhost:8080/Gestion_compagnie_mcv/index.php?url=admin/loguins/index
















 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
     <div class="breadcrumb-title pe-3">G-colis</div>
     <div class="ps-3">
         <nav aria-label="breadcrumb">
             <ol class="breadcrumb mb-0 p-0">
                 <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                 </li>
                 <li class="breadcrumb-item active text-primary" aria-current="page">Enregistrement des colis</li>
             </ol>
         </nav>
     </div>
     <div class="ms-auto">
         <div class="btn-group">
             <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges" class="btn btn-primary split-bg-primary text-white"> Liste des colis</a> &nbsp;
             <a href="javascript:history.back()" class="btn btn-primary "><i
                     class="fadeIn animated bx bx-left-arrow-alt"></i></a>

         </div>
     </div>
 </div>
 <!--end breadcrumb-->
 <div class="row">

     <div class="col-xxl-12">
         <?php $this->view("admin/set_flash") ?>
         <div class="col-xl-12 mx-auto">
             <div class="card">
                 <div class="card-body">
                     <ul class="nav nav-pills mb-3" role="tablist">
                         <li class="nav-item" role="presentation">
                             <a class="nav-link active" data-bs-toggle="pill" href="#info-pills-home" role="tab" aria-selected="true">
                                 <div class="d-flex align-items-center">
                                     <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- en attente = horloge -->
                                     <div class="tab-title">Colis en attente</div>
                                 </div>
                             </a>
                         </li>

                         <li class="nav-item" role="presentation">
                             <a class="nav-link" data-bs-toggle="pill" href="#info-pills-profile" role="tab" aria-selected="false">
                                 <div class="d-flex align-items-center">
                                     <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- reçu = boîte de réception -->
                                     <div class="tab-title">Colis reçu</div>
                                 </div>
                             </a>
                         </li>

                         <li class="nav-item" role="presentation">
                             <a class="nav-link" data-bs-toggle="pill" href="#info-pills-contact" role="tab" aria-selected="false">
                                 <div class="d-flex align-items-center">
                                     <div class="tab-icon"><i class='bx bx-check-shield font-18 me-1'></i></div> <!-- livré = check sécurisé -->
                                     <div class="tab-title">Colis livré</div>
                                 </div>
                             </a>
                         </li>

                     </ul>
                     <div class="tab-content" id="pills-tabContent">
                         <div class="tab-pane fade show active" id="info-pills-home" role="tabpanel">
                             <form action="" method="post">
                                 <table id="example" class="table table-striped table-bordered table-hover-effect" style="width:100%">
                                     <thead>
                                         <tr>
                                             <th><input type="checkbox" id="selectAll"></th>
                                             <th>Nom colis</th>
                                             <th>Nature</th>
                                             <th>Valeur</th>
                                             <th>Frais de transaction</th>
                                             <th>Destination</th>
                                             <th>Status</th>
                                             <th>Action</th>
                                         </tr>
                                     </thead>

                                     <tbody>
                                         <?php $this->view('admin/helpers') ?>
                                         <?php foreach ($liste_colis as $c): ?>
                                             <?php if ($c['destination'] === $_SESSION['ville']): ?>
                                                 <tr>
                                                     <td>
                                                         <input type="checkbox"
                                                             name="selected_colis[]"
                                                             value="<?= (int)$c['id_colis'] ?>"
                                                             class="checkbox-car">
                                                     </td>
                                                     <td><?= $c['nom_colis'] ?></td>
                                                     <td><?= $c['nature']     ?></td>
                                                     <td><?= $c['valeur']   ?></td>
                                                     <td><?= $c['fraix_transaction'] ?></td>
                                                     <td><?= $c['destination'] ?></td>
                                                     <td><?= afficherBadgeStatus($c['status']) ?></td>
                                                     <td class=" ">
                                                         <div class="dropup ">
                                                             <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                 &#8943; <!-- Trois points horizontaux -->
                                                             </a>
                                                             <div class="dropdown-menu dropdown-menu-end">
                                                                 <a class="dropdown-item" href="#">Modifier</a>
                                                                 <a class="dropdown-item" href="#">Désactiver</a>
                                                             </div>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             <?php endif ?>
                                         <?php endforeach ?>
                                     </tbody>
                                 </table>

                                 <button class="btn btn-primary mt-4" type="submit" name="reception">Réception</button>
                             </form>

                         </div>
                         <div class="tab-pane fade" id="info-pills-profile" role="tabpanel">
                             <div class="d-flex align-items-center">

                                 <form class="ms-auto position-relative">
                                     <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                                     <input class="form-control ps-5" type="text" placeholder="search">
                                 </form>
                             </div>
                             <div class=" mt-3">
                                 <table class="table align-middle">
                                     <thead class="table-secondary">
                                         <tr>
                                             <th>Nom colis</th>
                                             <th>Nature</th>
                                             <th>Valeur</th>
                                             <th>Frais de transaction</th>
                                             <th>Destination</th>
                                             <th>Status</th>
                                             <th>Action</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <?php foreach ($liste_colis_recue as $colis): ?>
                                             <?php if ($colis['destination'] === $_SESSION['ville']): ?>
                                                 <tr>

                                                     <td><?= $colis['nom_colis'] ?></td>
                                                     <td><?= $colis['nature']     ?></td>
                                                     <td><?= $colis['valeur']   ?></td>
                                                     <td><?= $colis['fraix_transaction'] ?></td>
                                                     <td><?= $colis['destination'] ?></td>
                                                     <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                                     <td class=" ">
                                                         <div class="dropup ">
                                                             <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                 &#8943; <!-- Trois points horizontaux -->
                                                             </a>
                                                             <div class="dropdown-menu dropdown-menu-end">
                                                                 <a class="dropdown-item" href="#">Livraison</a>
                                                                 <a class="dropdown-item" href="#">Details</a>
                                                             </div>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             <?php endif ?>
                                         <?php endforeach ?>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                         <div class="tab-pane fade" id="info-pills-contact" role="tabpanel">
                             <div class="d-flex align-items-center">

                                 <form class="ms-auto position-relative">
                                     <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                                     <input class="form-control ps-5" type="text" placeholder="search">
                                 </form>
                             </div>
                             <div class=" mt-3">
                                 <table class="table align-middle">
                                     <thead class="table-secondary">
                                         <tr>
                                             <th>Nom colis</th>
                                             <th>Nature</th>
                                             <th>Valeur</th>
                                             <th>Frais de transaction</th>
                                             <th>Destination</th>
                                             <th>Date de livraison</th>
                                             <th>Status</th>
                                             <th>Action</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <?php foreach ($liste_colis_livre as $colis_livre): ?>
                                             <?php if ($colis_livre['destination'] === $_SESSION['ville']): ?>
                                                 <tr>

                                                     <td><?= $colis_livre['nom_colis'] ?></td>
                                                     <td><?= $colis_livre['nature']     ?></td>
                                                     <td><?= $colis_livre['valeur']   ?></td>
                                                     <td><?= $colis_livre['fraix_transaction'] ?></td>
                                                     <td><?= $colis_livre['destination'] ?></td>
                                                     <td><?= $colis_livre['date_livraison'] ?></td>
                                                     <td><?= afficherBadgeStatus($colis_livre['status']) ?></td>
                                                     <td class=" ">
                                                         <div class="dropup ">
                                                             <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                 &#8943; <!-- Trois points horizontaux -->
                                                             </a>
                                                             <div class="dropdown-menu dropdown-menu-end">
                                                                 <a class="dropdown-item" href="#">Livraison</a>
                                                                 <a class="dropdown-item" href="#">Details</a>
                                                             </div>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             <?php endif ?>
                                         <?php endforeach ?>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>




  <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="col-xl-12 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="pill" href="#info-pills-home" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- en attente = horloge -->
                                                <div class="tab-title">Colis en attente</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="pill" href="#info-pills-profile" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- reçu = boîte de réception -->
                                                <div class="tab-title">Colis reçu</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="pill" href="#info-pills-contact" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-check-shield font-18 me-1'></i></div> <!-- livré = check sécurisé -->
                                                <div class="tab-title">Colis livré</div>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="info-pills-home" role="tabpanel">
                                        <form action="" method="post">
                                            <div class="table-responsive shadow-sm rounded">
                                                <table id="example" class="table table-striped table-hover align-middle text-center mb-0" style="width:100%">
                                                    <thead class="table-light text-primary">
                                                        <tr>
                                                            <th><input type="checkbox" id="selectAll"></th>
                                                            <th>Nom colis</th>
                                                            <th>Nature</th>
                                                            <th>Valeur</th>
                                                            <th>Frais de transaction</th>
                                                            <th>Destination</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $this->view('admin/helpers') ?>
                                                        <?php foreach ($liste_colis as $c): ?>
                                                            <?php if ($c['destination'] === $_SESSION['ville']): ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" name="selected_colis[]" value="<?= (int)$c['id_colis'] ?>" class="form-check-input checkbox-car">
                                                                    </td>
                                                                    <td class="fw-medium"><?= htmlspecialchars($c['nom_colis']) ?></td>
                                                                    <td><?= htmlspecialchars($c['nature']) ?></td>
                                                                    <td><?= number_format($c['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                                    <td><?= number_format($c['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                                    <td><?= htmlspecialchars($c['destination']) ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $status = $c['status'];
                                                                        if ($status == 'en_attente') echo '<span class="badge bg-warning text-dark">En attente</span>';
                                                                        elseif ($status == 'recu') echo '<span class="badge bg-info text-dark">Reçu</span>';
                                                                        elseif ($status == 'livre') echo '<span class="badge bg-success">Livré</span>';
                                                                        else echo '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                                <li><a class="dropdown-item" href="#"><i class="bx bx-edit me-1"></i> Modifier</a></li>
                                                                                <li><a class="dropdown-item" href="#"><i class="bx bx-block me-1"></i> Désactiver</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mt-4 text-end">
                                                <button class="btn btn-success rounded-pill shadow-sm px-4" type="submit" name="reception">
                                                    <i class="bx bx-check me-1"></i> Réception
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="tab-pane fade" id="info-pills-profile" role="tabpanel">

                                        <div class="d-flex align-items-center mb-3">
                                            <form class="ms-auto position-relative w-50">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill shadow-sm" type="text" placeholder="Rechercher un colis...">
                                            </form>
                                        </div>

                                        <div class="table-responsive shadow-sm rounded">
                                            <table class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-primary ">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_recue as $colis): ?>
                                                        <?php if ($colis['destination'] === $_SESSION['ville']): ?>
                                                            <tr>

                                                                <td><?= $colis['nom_colis'] ?></td>
                                                                <td><?= $colis['nature']     ?></td>
                                                                <td><?= $colis['valeur']   ?></td>
                                                                <td><?= $colis['fraix_transaction'] ?></td>
                                                                <td><?= $colis['destination'] ?></td>
                                                                <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                                                <td class=" ">
                                                                    <div class="dropup ">
                                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            &#8943; <!-- Trois points horizontaux -->
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="#">Livraison</a>
                                                                            <a class="dropdown-item" href="#">Details</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>

                                            </table>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="info-pills-contact" role="tabpanel">

                                        <div class="d-flex align-items-center mb-3">
                                            <form class="ms-auto position-relative w-50">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill shadow-sm" type="text" placeholder="Rechercher un colis...">
                                            </form>
                                        </div>

                                        <div class="table-responsive shadow-sm rounded">
                                            <table class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-primary ">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_livre as $colis_livre): ?>
                                                        <?php if ($colis_livre['destination'] === $_SESSION['ville']): ?>
                                                            <tr>

                                                                <td><?= $colis_livre['nom_colis'] ?></td>
                                                                <td><?= $colis_livre['nature']     ?></td>
                                                                <td><?= $colis_livre['valeur']   ?></td>
                                                                <td><?= $colis_livre['fraix_transaction'] ?></td>
                                                                <td><?= $colis_livre['destination'] ?></td>
                                                                <td><?= $colis_livre['date_livraison'] ?></td>
                                                                <td><?= afficherBadgeStatus($colis_livre['status']) ?></td>
                                                                <td class=" ">
                                                                    <div class="dropup ">
                                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            &#8943; <!-- Trois points horizontaux -->
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="#">Livraison</a>
                                                                            <a class="dropdown-item" href="#">Details</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>

                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>