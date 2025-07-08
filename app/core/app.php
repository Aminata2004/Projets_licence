<?php

/**
 * Created by PhpStorm.
 * User: SNT
 * Date: 21/11/2022
 * Time: 12:11
 */
class App
{

    protected $controller = 'Loguins';
    protected $method = "index";
    protected $params = [];
    // public function  __construct()
    // {
    //     $URL = $this->getURL();
    //     if (file_exists('app/controllers/' . $URL[0] . '.php')) {
    //         $this->controller = ucfirst($URL[0]);
    //         unset($URL[0]);
    //     }

    //     require_once('app/controllers/' . $this->controller . '.php');
    //     $this->controller = new $this->controller();

    //     if (isset($URL[1])) {
    //         if (method_exists($this->controller, $URL[1])) {
    //             $this->method = ucfirst($URL[1]);
    //             unset($URL[1]);
    //         }
    //     }

    //     $URL = array_values($URL);
    //     $this->params = $URL;
    //     call_user_func_array([$this->controller, $this->method], $this->params);
    // }

    public function __construct()
{
    $URL = $this->getURL();

    // Si le contrôleur demandé existe
    if (isset($URL[0]) && file_exists('app/controllers/' . ucfirst($URL[0]) . '.php')) {
        $this->controller = ucfirst($URL[0]);
        unset($URL[0]);
    }

    require_once('app/controllers/' . $this->controller . '.php');
    $this->controller = new $this->controller();

    // Si une méthode est spécifiée et existe
    if (isset($URL[1]) && method_exists($this->controller, $URL[1])) {
        $this->method = $URL[1];
        unset($URL[1]);
    }

    $this->params = $URL ? array_values($URL) : []; // Assure que c’est un tableau

    call_user_func_array([$this->controller, $this->method], $this->params);
}


    // private function  getURL(){
    //    $url= $_GET['url'] ?? 'Home';
    //     return explode("/",filter_var(trim($url,'/')),FILTER_SANITIZE_URL);
    // }

    private function getURL()
    {
        if (isset($_GET['url'])) {
            return explode("/", filter_var(trim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return []; // ← S'il n'y a rien dans l'URL, on retourne un tableau vide
    }
}
