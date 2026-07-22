<?php

/**
 * Created by PhpStorm.
 * User: SNT
 * Date: 21/11/2022
 * Time: 12:11
 */
// class App
// {

//     protected $controller = 'Loguins';
//     protected $method = "index";
//     protected $params = [];
//     // public function  __construct()
//     // {
//     //     $URL = $this->getURL();
//     //     if (file_exists('app/controllers/' . $URL[0] . '.php')) {
//     //         $this->controller = ucfirst($URL[0]);
//     //         unset($URL[0]);
//     //     }

//     //     require_once('app/controllers/' . $this->controller . '.php');
//     //     $this->controller = new $this->controller();

//     //     if (isset($URL[1])) {
//     //         if (method_exists($this->controller, $URL[1])) {
//     //             $this->method = ucfirst($URL[1]);
//     //             unset($URL[1]);
//     //         }
//     //     }

//     //     $URL = array_values($URL);
//     //     $this->params = $URL;
//     //     call_user_func_array([$this->controller, $this->method], $this->params);
//     // }

//     public function __construct()
// {
//     $URL = $this->getURL();

//     // Si le contrôleur demandé existe
//     if (isset($URL[0]) && file_exists('app/controllers/' . ucfirst($URL[0]) . '.php')) {
//         $this->controller = ucfirst($URL[0]);
//         unset($URL[0]);
//     }

//     require_once('app/controllers/' . $this->controller . '.php');
//     $this->controller = new $this->controller();

//     // Si une méthode est spécifiée et existe
//     if (isset($URL[1]) && method_exists($this->controller, $URL[1])) {
//         $this->method = $URL[1];
//         unset($URL[1]);
//     }

//     $this->params = $URL ? array_values($URL) : []; // Assure que c’est un tableau

//     call_user_func_array([$this->controller, $this->method], $this->params);
// }


//     // private function  getURL(){
//     //    $url= $_GET['url'] ?? 'Home';
//     //     return explode("/",filter_var(trim($url,'/')),FILTER_SANITIZE_URL);
//     // }

//     private function getURL()
//     {
//         if (isset($_GET['url'])) {
//             return explode("/", filter_var(trim($_GET['url'], '/'), FILTER_SANITIZE_URL));
//         }
//         return []; // ← S'il n'y a rien dans l'URL, on retourne un tableau vide
//     }
// }



class App {
    protected $controller = null;
    protected $action = 'index';
    protected $params = [];
    protected $module = 'site'; // module par défaut

    public function __construct() {
        $url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];

        // 1. Détection du module (admin ou site)
        if (isset($url[0]) && in_array(strtolower($url[0]), ['admin', 'site'])) {
            $this->module = strtolower($url[0]);
            array_shift($url); // on enlève 'admin' ou 'site' de l'URL
        }

        // 2. Définir le contrôleur par défaut
        if (empty($url)) {
            $this->controller = $this->module === 'admin' ? 'Loguins' : 'Accueil';
        } else {
            $this->controller = ucfirst($url[0]);
            array_shift($url);
        }

        // 3. Construire chemin fichier contrôleur
        $controllerFile = "app/controllers/{$this->module}/{$this->controller}.php";

        // 4. Vérifier que le fichier existe
        if (!file_exists($controllerFile)) {
            $this->redirectToHome(); // 🔁 Redirection si le fichier est introuvable
        }

        // 5. Charger le fichier contrôleur
        require_once $controllerFile;

        // 6. Vérifier que la classe existe
        if (!class_exists($this->controller)) {
            $this->redirectToHome(); // 🔁 Redirection si la classe n'existe pas
        }

        // 7. Instancier le contrôleur
        $this->controller = new $this->controller();

        // 8. Action demandée ?
        if (isset($url[0])) {
            $this->action = $url[0];
            array_shift($url);
        }

        // 9. Paramètres
        $this->params = $url ? array_values($url) : [];

        // 10. Vérifier si la méthode existe
        if (!method_exists($this->controller, $this->action)) {
            $this->redirectToHome(); // 🔁 Redirection si méthode introuvable
        }

        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    private function redirectToHome() {
        header("Location: " . BASE_URL . "/");
        exit;
    }
}
