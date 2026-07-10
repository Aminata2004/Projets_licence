<?php
  class AjaxFiltreHistorique extends Controller
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $heure = $_POST['selectheure'] ?? '';
            $destination = $_POST['id_destination'] ?? '';
            $jour = $_POST['jourVoyage'] ?? '';
            $id_compagnie = $_SESSION['id_compagnie'];

            if ($heure && $destination && $jour) {
                $model = new Liste_du_jour();

                $resultats = $model->FetchSelectWheres(
                    '*',
                    'billets INNER JOIN client ON billets.id_client = client.idClient',
                    'billets.id_compagnie = :id_compagnie AND billets.destinationId = :destination AND billets.Heur_departs = :heure AND billets.jourVoyage = :jour',
                    [
                        'id_compagnie' => $id_compagnie,
                        'destination' => $destination,
                        'heure' => $heure,
                        'jour' => $jour
                    ]
                );

                ob_start();
                foreach ($resultats as $item) {
                    echo '<tr class="text-center">';
                    echo '<td data-label="Client">' . htmlspecialchars($item->Client) . '</td>';
                    echo '<td data-label="Destination">' . htmlspecialchars($item->destinationId) . '</td>';
                    echo '<td data-label="Nbr de passage">' . htmlspecialchars($item->nombrePassages) . '</td>';
                    echo '<td data-label="Jour de voyage">' . htmlspecialchars($item->jourVoyage) . '</td>';
                    echo '<td data-label="Heure de départ">' . htmlspecialchars($item->Heur_departs) . '</td>';
                    echo '<td data-label="Date expiration">' . htmlspecialchars($item->date_expiration) . '</td>';

                    echo '</tr>';
                }
                $tbody = ob_get_clean();

                echo json_encode(['tbody' => $tbody]);
                exit;
            } else {
                echo json_encode(['error' => 'Veuillez sélectionner une heure, une destination et une date de voyage.']);
                exit;
            }
        }

        echo json_encode(['error' => 'Requête invalide.']);
        exit;
    }
}
