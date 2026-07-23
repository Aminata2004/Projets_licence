  <?php
    class AjaxFiltreListeClient extends Controller
    {
        public function __construct()
        {
            $this->requireLogin();
        }

        public function index()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $heure = $_POST['selectheure'] ?? '';
                $destination = $_POST['id_destination'] ?? '';
                $id_compagnie = $_SESSION['id_compagnie'];
                // Admin n'a pas de gare fixe en session : il voit les billets de toute la compagnie.
                // numeroGare précise la gare exacte (une ville peut avoir plusieurs gares).
                $isAdmin = ($_SESSION['droit'] ?? null) === 'Admin';
                $idDepart = $isAdmin ? null : ($_SESSION['ville'] ?? null);
                $numeroGare = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
                date_default_timezone_set('Africa/Bamako');
                $aujourd = date('Y-m-d');

                if ($heure && $destination) {
                    $model = new Liste_du_jour();

                    $where = 'billets.id_compagnie = :id_compagnie AND billets.destinationId = :destination AND billets.Heur_departs = :heure AND billets.jourVoyage = :jour';
                    $params = [
                        'id_compagnie' => $id_compagnie,
                        'destination' => $destination,
                        'heure' => $heure,
                        'jour' => $aujourd // ici, la clé doit correspondre au token SQL
                    ];
                    if ($idDepart !== null) {
                        $where .= ' AND billets.departId = :depart';
                        $params['depart'] = $idDepart;
                    }
                    if ($numeroGare !== null) {
                        $where .= ' AND billets.num_gare = :numeroGare';
                        $params['numeroGare'] = $numeroGare;
                    }

                    $resultats = $model->FetchSelectWheres(
                        '*',
                        'billets INNER JOIN client ON billets.id_client = client.idClient',
                        $where,
                        $params
                    );
                    // Générer le HTML du tbody
                    ob_start();
                    foreach ($resultats as $item) {
                        $jourVoyageIso = date('Y-m-d', strtotime($item->jourVoyage));
                        $dateExpirationIso = date('Y-m-d', strtotime($item->date_expiration));
                        echo '<tr class="text-center">';
                        echo '<td data-label="Client">' . htmlspecialchars($item->Client) . '</td>';
                        echo '<td data-label="Destination">' . htmlspecialchars($item->destinationId) . '</td>';
                        echo '<td data-label="N° de place">Chaisse N°' . htmlspecialchars($item->numeroPlace) . '</td>';
                        echo '<td data-label="Heure de départ">' . htmlspecialchars($item->Heur_departs) . '</td>';
                        echo '<td data-label="Jour de voyage">' . htmlspecialchars($item->jourVoyage) . '</td>';
                        echo '<td data-label="Date d\'expiration">' . htmlspecialchars($item->date_expiration) . '</td>';
                        echo '<td data-label="Action">
                            <div class="dropup">
                                <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">&#8943;</a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Details</a>
                                    <a class="dropdown-item" href="#">Modifier</a>
                                    <a href="#" class="dropdown-item report-btn"
                                        data-idclient="' . htmlspecialchars($item->idBillets) . '"
                                        data-jour_voyage="' . htmlspecialchars($jourVoyageIso) . '"
                                        data-destinationid="' . htmlspecialchars($item->destinationId) . '"
                                        data-date_expiration="' . htmlspecialchars($dateExpirationIso) . '"
                                        data-heure_actuelle="' . htmlspecialchars($item->Heur_departs) . '"
                                        data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                                        Reporter le voyage
                                    </a>
                                    <a class="dropdown-item" href="' . BASE_URL . '/admin/Liste_du_jours/recu/' . htmlspecialchars($item->idBillets) . '" target="_blank">
                                        Imprimer (imprimante câble/USB)
                                    </a>
                                    <a class="dropdown-item thermal-print-btn" href="#" data-id="' . htmlspecialchars($item->idBillets) . '">
                                        Imprimer (imprimante WiFi)
                                    </a>
                                </div>
                            </div>
                        </td>';
                        echo '</tr>';
                    }
                    $tbody = ob_get_clean();

                    echo json_encode(['tbody' => $tbody]);
                    exit;
                } else {
                    echo json_encode(['error' => 'Veuillez sélectionner une heure et une destination.']);
                    exit;
                }
            }
            echo json_encode(['error' => 'Requête invalide.']);
            exit;
        }
        
    }
