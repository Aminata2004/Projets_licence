<?php
session_start();
ob_start(); // ← autorise les echo dans le contrôleur
define('ROOT', __DIR__); // ✅ Ne rien ajouter après __DIR__

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/core/autoload.php';


require_once ("app/core/autoload.php");
$app=new App();



 ob_end_flush(); ?>
