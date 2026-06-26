<?php
// session_start();
// ob_start(); // ← autorise les echo dans le contrôleur
// define('ROOT', __DIR__); // ✅ Ne rien ajouter après __DIR__

// require_once __DIR__ . '/vendor/autoload.php';
// require_once __DIR__ . '/app/core/autoload.php';

// require_once ("app/core/autoload.php");
// $app=new App();

//  ob_end_flush(); 

session_start();
ob_start(); // ← autorise les echo dans le contrôleur
define('ROOT', __DIR__); // ✅ Ne rien ajouter après __DIR__

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/core/autoload.php';


$app = new App();

ob_end_flush();
        

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Récupérer le controller et l'action depuis CLI si nécessaire
if (php_sapi_name() === 'cli') {
    if (isset($argv[1]) && isset($argv[2])) {
        $_GET['controller'] = $argv[1];
        $_GET['action'] = $argv[2];
    }
}

// Pour URL classique (HTTP)
$url_path = $_GET['controller'] ?? 'home';   // controller par défaut
$action = $_GET['action'] ?? 'index';        // action par défaut





 
