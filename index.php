<?php
// --- Activation des erreurs EN PREMIER pour diagnostiquer les 500 ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
// --------------------------------------------------------------------

// Fuseau horaire de l'application fixé une bonne fois pour toutes (le serveur peut être
// configuré sur un autre fuseau, ex: Europe/Berlin, ce qui décale "aujourd'hui" de 1-2h
// et casse tous les filtres par date du jour comme dans Envoi_colis/Programmation_voyages).
date_default_timezone_set('Africa/Bamako');

session_start();
ob_start(); // ← autorise les echo dans le contrôleur
define('ROOT', __DIR__); // ✅ Ne rien ajouter après __DIR__

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/core/autoload.php';

$app = new App();

ob_end_flush();

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





 
