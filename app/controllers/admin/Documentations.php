<?php

// Manuel d'utilisation du systeme, de la connexion au dernier module. Accessible a TOUT
// utilisateur connecte, quel que soit son role ou ses permissions (contrairement aux
// autres controleurs admin qui filtrent par requirePermission) : c'est de la
// documentation, pas un ecran metier, donc aucune restriction n'a de sens ici.
class Documentations extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $this->view('admin/documentation', []);
    }

    public function pdf()
    {
        ob_start();
        include ROOT . '/app/views/admin/pdf/documentation.php';
        $html = ob_get_clean();

        $opt = new \Dompdf\Options();
        $opt->setChroot(ROOT);
        $opt->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($opt);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('manuel_utilisation_transgest.pdf', ['Attachment' => false]);
        exit;
    }
}
