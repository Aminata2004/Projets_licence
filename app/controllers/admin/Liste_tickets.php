
<?php
class Liste_tickets extends  Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    public  function  index()
    {
        date_default_timezone_set('Africa/Bamako');
        $id_compagnie = $_SESSION['id_compagnie'];
        $model = new Liste_du_jour();

        $listeClients = $model->FetchSelectWheres(
            '*',
            'billets inner join client on billets.id_client = client.idClient',
            'billets.id_compagnie = :id_compagnie',
            ['id_compagnie' => $id_compagnie]
        );

        $heureCourante = $model->getHeureDepartCourante($_SESSION['ville'] ?? '', $id_compagnie);

        $this->view('admin/liste_ticket', ['listeClients' => $listeClients, 'heureCourante' => $heureCourante]);
    }

    public function edit()
    {
        $model = new Liste_du_jour();
        if (isset($_POST['edit'])) {
            $idBillets = $_POST['idBillets'] ?? null;
            // getBilletById() est filtré par compagnie de session : confirme que ce billet
            // appartient bien à l'utilisateur avant toute modification (protection IDOR).
            $billet = $idBillets ? $model->getBilletById($idBillets) : null;

            if (!$billet) {
                $model->set_flash("Billet introuvable.", "danger");
                header("Location: " . BASE_URL . "/admin/Liste_tickets");
                exit;
            }

            $model->updateBillet([
                'idBillets'       => $idBillets,
                'id_client'       => $billet->id_client, // dérivé du billet vérifié, jamais du POST
                'Client'          => $_POST['Client'],
                'date_expiration' => $_POST['date_expiration'],
            ]);
        }
        header("Location: " . BASE_URL . "/admin/Liste_tickets");
        exit;
    }
}
