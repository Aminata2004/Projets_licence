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

// 🔁 Redirection automatique si on tape directement "/admin"
if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    $basePath = '/Gestion_compagnie_mcv';

    if ($uri === $basePath . '/admin' || $uri === $basePath . '/admin/') {
        header('Location: ' . $basePath . '/index.php?url=admin/loguins/index');
        exit();
    }
}

$app = new App();



ob_end_flush();
        
 
