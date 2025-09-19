<?php
class Add_billets extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $model = new Add_billet();
        date_default_timezone_set('Africa/Bamako');
        $data['destinations'] = $model->getDestinationsWithHeuresAndEscales();

        if (isset($_POST['save'])) {
            $result = $model->saveBillets();

            if ($result === true) {
                $_SESSION['flash'] = "Réservation enregistrée !";
                $this->view('admin/add_billets');
                exit;
            } else {
                $data['erreurs'] = $result; // tableau d'erreurs
            }
        }

        $this->view('admin/add_billets', $data);
    }
}
