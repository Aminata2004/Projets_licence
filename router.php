<?php
/**
 * Routeur pour le serveur intégré de PHP (php -S), utilisé uniquement en test local.
 * Reproduit la logique de public/.htaccess : sert les fichiers statiques existants
 * tels quels, et renvoie tout le reste vers index.php?url=... (project root = document
 * root du site en production, doc root effectif ici = public/, comme sur LWS).
 */

$projectRoot = __DIR__;
$publicRoot = $projectRoot . '/public';

$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = $publicRoot . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

$_GET['url'] = ltrim($path, '/');
chdir($projectRoot);
require $projectRoot . '/index.php';
