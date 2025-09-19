  <?php
    class AjaxFiltreListe extends Controller
    {
        public function index()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $heure = $_POST['selectheure'] ?? '';
                $destination = $_POST['id_destination'] ?? '';
                $id_compagnie = $_SESSION['id_compagnie'];

                if ($heure && $destination) {
                    $model = new Liste_du_jour();
                    date_default_timezone_set('Africa/Bamako');
                    $demain = date('Y-m-d', strtotime('+1 day'));

                    $resultats = $model->FetchSelectWheres(
                        '*',
                        'billets INNER JOIN client ON billets.id_client = client.idClient',
                        'billets.id_compagnie = :id_compagnie AND billets.destinationId = :destination AND billets.Heur_departs = :heure AND billets.jourVoyage = :jour',
                        [
                            'id_compagnie' => $id_compagnie,
                            'destination' => $destination,
                            'heure' => $heure,
                            'jour' => $demain
                        ]
                    );


                    // Générer le HTML du tbody
                    ob_start();
                    foreach ($resultats as $item) {
                        echo '<tr class="text-center">';
                        echo '<td>' . htmlspecialchars($item->Client) . '</td>';
                        echo '<td>' . htmlspecialchars($item->destinationId) . '</td>';
                        echo '<td>' . htmlspecialchars($item->nombrePassages) . '</td>';
                        echo '<td>' . htmlspecialchars($item->Heur_departs) . '</td>';
                        echo '<td>' . htmlspecialchars($item->jourVoyage) . '</td>';
                        echo '<td>' . htmlspecialchars($item->date_expiration) . '</td>';
                        echo '<td>' . htmlspecialchars($item->Client) . '</td>';
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
