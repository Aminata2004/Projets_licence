
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

        $this->view('admin/liste_ticket', ['listeClients' => $listeClients]);
    }

    public function edit()
    {
        $model = new Liste_du_jour();
        if (isset($_POST['edit'])) {
            $model->updateBillet([
                'idBillets'       => $_POST['idBillets'],
                'id_client'       => $_POST['id_client'],
                'Client'          => $_POST['Client'],
                'date_expiration' => $_POST['date_expiration'],
            ]);
        }
        header("Location: " . BASE_URL . "/admin/Liste_tickets");
        exit;
    }
}
