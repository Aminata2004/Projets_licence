<?php
/**
 * Created by PhpStorm.
 * User: SNT
 * Date: 21/11/2022
 * Time: 13:27
 */

class Controller
{
    public function e($value){

        if ($value){
            $value=htmlspecialchars($value);
            $value=htmlentities($value);
            $value=strip_tags($value);
            return $value;
        }
    }

    public function load_model($model)
    {
        // code...
        if(file_exists('app/models/'.ucfirst($model).'.php')){
            require_once('app/models/'.ucfirst($model).'.php');
            return new $model();
        }
        return false;
    }

    function view($view,$data=[]){

        if (file_exists('app/views/'.$view.'.view.php')) {
            extract($data);
            require_once('app/views/'.$view.'.view.php');
        }else {
            require_once('app/views/admin/404.view.php');
        }
    }

    public  function redirect($page)
    {
        header("Location: ".BASE_URL."/".trim($page,"/"));
        exit();
    }

    public function isLoggedIn()
{
    return isset($_SESSION['id_utilisateur']);
}

public function requireLogin()
{
    if (!$this->isLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/Loguins');
        exit();
    }
}

    /**
     * Rendu Dompdf pour imprimante thermique à rouleau (E-POS ECO250, 80mm).
     * La largeur de page est fixe (80mm par défaut) mais la hauteur est calculée
     * automatiquement à partir du contenu réel (recherche par dichotomie sur le
     * nombre de pages produites), pour ne pas gaspiller de papier à chaque impression.
     */
    private function renderThermalDompdf(string $html, float $widthMm = 80.0): \Dompdf\Dompdf
    {
        $ptPerMm = 72 / 25.4;
        $widthPt = $widthMm * $ptPerMm;

        $options = new \Dompdf\Options();
        $options->setChroot(ROOT);
        $options->setIsRemoteEnabled(true);

        $render = function (float $heightPt) use ($html, $widthPt, $options): \Dompdf\Dompdf {
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, $widthPt, $heightPt], 'portrait');
            $dompdf->render();
            return $dompdf;
        };

        $lo = 20 * $ptPerMm;
        $hi = 600 * $ptPerMm;

        for ($i = 0; $i < 9; $i++) {
            $mid = ($lo + $hi) / 2;
            $pageCount = $render($mid)->getCanvas()->get_page_count();
            if ($pageCount > 1) {
                $lo = $mid;
            } else {
                $hi = $mid;
            }
        }

        // petite marge de sécurité pour éviter tout rognage de la dernière ligne
        return $render($hi + (3 * $ptPerMm));
    }

    protected function streamThermalPdf(string $html, string $filename, float $widthMm = 80.0): void
    {
        $this->renderThermalDompdf($html, $widthMm)->stream($filename, ['Attachment' => false]);
        exit;
    }

    protected function outputThermalPdf(string $html, float $widthMm = 80.0): string
    {
        return $this->renderThermalDompdf($html, $widthMm)->output();
    }

}