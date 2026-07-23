<?php

// Les valeurs par défaut ci-dessous ne sont utilisées que si la variable n'est pas définie
// dans le fichier .env à la racine du projet (voir .env.example).
define("BASE_URL", getenv('BASE_URL') ?: "http://localhost:8080/Gestion_compagnie_mcv/public"); // OK pour Apache
// define('ROOT', dirname(__DIR__));  // ou un chemin absolu local
define("DBNAME", getenv('DB_NAME') ?: "db_compagnies_mvc");
define("DBHOST", getenv('DB_HOST') ?: "localhost");
define("DBUSERNAME", getenv('DB_USERNAME') ?: "root");
define("DBPASSWORD", getenv('DB_PASSWORD') ?: "");
define("DBDRIVER", getenv('DB_DRIVER') ?: "mysql");

define("MAIL_HOST", getenv('MAIL_HOST') ?: "smtp.gmail.com");
define("MAIL_PORT", getenv('MAIL_PORT') ?: 587);
define("MAIL_ENCRYPTION", getenv('MAIL_ENCRYPTION') ?: "tls");
define("MAIL_USERNAME", getenv('MAIL_USERNAME') ?: "");
define("MAIL_PASSWORD", getenv('MAIL_PASSWORD') ?: "");
define("MAIL_FROM_NAME", getenv('MAIL_FROM_NAME') ?: "Airbarry");

// Impression thermique (billets) : "network" (IP fixe, port 9100) ou "usb" (imprimante
// installée localement sur le poste qui imprime, cf. app/core/ThermalPrinter.php).
define("PRINTER_MODE", getenv('PRINTER_MODE') ?: "network");
define("PRINTER_IP", getenv('PRINTER_IP') ?: "192.168.1.100");
define("PRINTER_PORT", getenv('PRINTER_PORT') ?: 9100);
define("PRINTER_USB_NAME", getenv('PRINTER_USB_NAME') ?: "POS-80");
