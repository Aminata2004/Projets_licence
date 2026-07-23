<?php
/**
 * Pont d'impression local — à lancer sur CHAQUE poste de comptoir équipé d'une
 * imprimante thermique (réseau ou USB).
 *
 * Le site est hébergé sur un serveur distant (LWS) : il ne peut jamais atteindre
 * une imprimante sur le réseau local de l'agence (IP privée type 192.168.1.x)
 * ni une imprimante USB branchée sur l'ordinateur du comptoir. Ce pont tourne sur
 * CE poste, écoute en local, et c'est lui qui parle réellement à l'imprimante.
 *
 * Lancement (depuis le dossier du projet) :
 *   php -S 127.0.0.1:9200 local-print-bridge/bridge.php
 *
 * À faire démarrer automatiquement avec Windows sur chaque poste équipé d'une
 * imprimante (ex: raccourci dans le dossier Démarrage, ou tâche planifiée).
 */

require_once __DIR__ . '/ThermalPrinter.php';

// --- Config imprimante : lue depuis local-print-bridge/.env (PAS le .env principal du
// site — ce pont n'a besoin d'aucun accès base de données, seulement des réglages imprimante) ---
function load_bridge_env(string $path): array
{
    $config = [];
    if (!file_exists($path)) {
        return $config;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || ($line[0] ?? '') === '#') {
            continue;
        }
        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $config[trim($key)] = trim($value);
    }
    return $config;
}

$config = load_bridge_env(__DIR__ . '/.env');

if (!defined('PRINTER_MODE')) {
    define('PRINTER_MODE', $config['PRINTER_MODE'] ?? 'network');
    define('PRINTER_IP', $config['PRINTER_IP'] ?? '192.168.1.100');
    define('PRINTER_PORT', (int) ($config['PRINTER_PORT'] ?? 9100));
    define('PRINTER_USB_NAME', $config['PRINTER_USB_NAME'] ?? 'POS-80');
}

// --- CORS : seuls les sites listés dans ALLOWED_ORIGINS peuvent appeler ce pont ---
$originsAutorisees = array_filter(array_map('trim', explode(',', $config['ALLOWED_ORIGINS'] ?? '')));
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && in_array($origin, $originsAutorisees, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
// Chrome (Private Network Access) bloque par défaut qu'un site public en HTTPS appelle
// une adresse locale (127.0.0.1) sans cet en-tête explicite sur la réponse au preflight.
// Sans lui, l'appel échoue silencieusement côté navigateur ("pont injoignable") même si
// le pont tourne bien et répond correctement en direct (ex: via /health).
header('Access-Control-Allow-Private-Network: true');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$chemin = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Route de diagnostic : ouvrir http://127.0.0.1:9200/health dans un navigateur sur CE
// poste permet de vérifier en un coup d'œil que le pont tourne bien, sans passer par le
// site ni par une impression réelle (utile pour distinguer "pont pas démarré" de "CORS
// bloqué" ou "imprimante injoignable").
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $chemin === '/health') {
    echo json_encode([
        'success' => true,
        'message' => 'Pont actif.',
        'printer_mode' => PRINTER_MODE,
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $chemin !== '/print') {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Route inconnue. Utilisez POST /print ou GET /health.']);
    exit;
}

$billet = json_decode(file_get_contents('php://input'), true);
if (!is_array($billet)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Corps de requête invalide (JSON attendu).']);
    exit;
}

echo json_encode(ThermalPrinter::printBillet($billet));
