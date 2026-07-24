<?php

/**
 * Génère un billet en commandes ESC/POS brutes pour imprimante thermique 80mm,
 * et l'envoie soit en réseau (TCP/IP, port 9100), soit en USB local, selon
 * PRINTER_MODE (.env). La génération du contenu est indépendante du mode d'envoi.
 */
class ThermalPrinter
{
    private const LARGEUR_COLONNES = 32; // ~32 caractères par ligne en police normale sur 80mm

    // --- Génération du contenu (indépendante du mode d'envoi) ---

    public static function buildBilletContent(array $billet): string
    {
        $ESC = "\x1B";
        $GS  = "\x1D";
        $sep = str_repeat('-', self::LARGEUR_COLONNES) . "\n";

        $r = $ESC . "@"; // init imprimante

        // En-tête centré
        $r .= $ESC . "a" . "\x01";
        $r .= $ESC . "!" . "\x30";
        $r .= self::clean($billet['compagnie'] ?? '') . "\n";
        $r .= $ESC . "!" . "\x00";
        if (!empty($billet['slogan'])) {
            $r .= self::clean($billet['slogan']) . "\n";
        }
        $r .= $sep;
        $r .= $ESC . "!" . "\x10";
        $r .= "BILLET DE VOYAGE\n";
        $r .= "N " . self::clean($billet['numero'] ?? '-') . "\n";
        $r .= $ESC . "!" . "\x00";
        $r .= $sep;

        // Détails alignés à gauche
        $r .= $ESC . "a" . "\x00";
        $r .= sprintf("%-12s%s\n", "Client", self::clean($billet['client'] ?? '-'));
        $r .= sprintf("%-12s%s\n", "Date", self::clean($billet['date'] ?? '-'));
        $r .= sprintf("%-12s%s\n", "Depart", self::clean($billet['depart'] ?? '-'));
        $r .= sprintf("%12s%s\n", "", self::clean($billet['heure'] ?? '-'));
        $r .= sprintf("%-12s%s\n", "Destination", self::clean($billet['destination'] ?? '-'));
        $r .= sprintf("%-12s%s\n", "Place(s)", self::clean($billet['places'] ?? '-'));
        $r .= $sep;

        // Montant en gras/agrandi
        $r .= $ESC . "!" . "\x10";
        $r .= "MONTANT PAYE   " . self::clean($billet['montant'] ?? '-') . " FCFA\n";
        $r .= $ESC . "!" . "\x00";
        $r .= $sep;

        // Mentions légales
        $r .= "Merci d'etre a la gare 45 minutes\n";
        $r .= "avant l'heure de depart.\n";
        $r .= "Ce billet est valable 1 semaine\n";
        $r .= "apres sa date d'emission.\n";
        $r .= $sep;

        // Pied de page centré
        $r .= $ESC . "a" . "\x01";
        $r .= "Emis par " . self::clean($billet['emisPar'] ?? '-') . "\n";
        $r .= "Merci d'avoir choisi " . self::clean($billet['compagnie'] ?? '') . "\n";
        $r .= "\n\n\n";
        $r .= $GS . "V" . "\x00"; // coupe papier

        return $r;
    }

    public static function buildColisContent(array $colis): string
    {
        $ESC = "\x1B";
        $GS  = "\x1D";
        $sep = str_repeat('-', self::LARGEUR_COLONNES) . "\n";

        $r = $ESC . "@"; // init imprimante

        $r .= $ESC . "a" . "\x01";
        $r .= $ESC . "!" . "\x30";
        $r .= self::clean($colis['compagnie'] ?? '') . "\n";
        $r .= $ESC . "!" . "\x00";
        if (!empty($colis['slogan'])) {
            $r .= self::clean($colis['slogan']) . "\n";
        }
        $r .= $sep;
        $r .= $ESC . "!" . "\x10";
        $r .= "RECU DE COLIS\n";
        $r .= $ESC . "!" . "\x00";
        $r .= self::clean($colis['nom'] ?? '-') . "\n";
        $r .= $sep;

        $r .= $ESC . "a" . "\x00";
        $r .= sprintf("%-13s%s\n", "Code", self::clean($colis['code'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Expediteur", self::clean($colis['expediteur'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Tel", self::clean($colis['numeroExp'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Destinataire", self::clean($colis['destinataire'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Tel", self::clean($colis['numeroDest'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Depart", self::clean($colis['depart'] ?? '-'));
        $r .= sprintf("%-13s%s\n", "Destination", self::clean($colis['destination'] ?? '-'));
        $r .= $sep;

        $r .= $ESC . "!" . "\x10";
        $r .= "VALEUR   " . self::clean($colis['valeur'] ?? '-') . " FCFA\n";
        $r .= "FRAIS    " . self::clean($colis['frais'] ?? '-') . " FCFA\n";
        $r .= $ESC . "!" . "\x00";
        $r .= $sep;

        // QR code (vérification/suivi), centré, avec le code du colis rappelé en dessous
        $r .= $ESC . "a" . "\x01";
        $r .= self::qrCode(self::clean($colis['code'] ?? '-'));
        $r .= self::clean($colis['code'] ?? '-') . "\n";
        $r .= $sep;

        $r .= "Enregistre par " . self::clean($colis['agent'] ?? '-') . "\n";
        $r .= "Merci d'avoir choisi " . self::clean($colis['compagnie'] ?? '') . "\n";
        $r .= "\n\n\n";
        $r .= $GS . "V" . "\x00"; // coupe papier

        return $r;
    }

    // Commande ESC/POS standard (GS ( k) pour imprimer un QR code, supportée par la
    // quasi-totalité des imprimantes thermiques modernes (Epson TM et clones compatibles).
    private static function qrCode(string $data): string
    {
        $prefix = "\x1D" . "(k";
        $len = strlen($data) + 3;

        $r = '';
        $r .= $prefix . chr(4) . chr(0) . chr(0x31) . chr(0x41) . chr(50) . chr(0); // modèle 2
        $r .= $prefix . chr(3) . chr(0) . chr(0x31) . chr(0x43) . chr(6);            // taille module
        $r .= $prefix . chr(3) . chr(0) . chr(0x31) . chr(0x45) . chr(49);           // correction erreur (M)
        $r .= $prefix . chr($len % 256) . chr(intdiv($len, 256)) . chr(0x31) . chr(0x50) . chr(0x30) . $data; // stocke
        $r .= $prefix . chr(3) . chr(0) . chr(0x31) . chr(0x51) . chr(0x30) . "\n"; // imprime

        return $r;
    }

    // La plupart des imprimantes thermiques génériques (jeu de caractères par défaut)
    // n'affichent pas correctement les accents UTF-8 : on les retire par sécurité.
    private static function clean(string $texte): string
    {
        $sansAccents = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);
        return $sansAccents !== false ? $sansAccents : $texte;
    }

    // --- Envoi réseau : imprimante avec IP fixe, écoute TCP/IP (port 9100 en général) ---

    public static function sendToNetworkPrinter(string $ip, int $port, string $data): array
    {
        $socket = @fsockopen($ip, $port, $errno, $errstr, 5);
        if (!$socket) {
            return ['success' => false, 'message' => "Imprimante réseau injoignable ($ip:$port) : $errstr ($errno)"];
        }

        fwrite($socket, $data);
        fclose($socket);
        return ['success' => true, 'message' => 'Ticket envoyé à l\'imprimante réseau.'];
    }

    // --- Envoi USB : imprimante branchée en local sur le poste qui imprime ---
    //
    // Windows : l'imprimante doit être installée dans le Panneau de configuration (pilote
    // "Generic / Text Only" ou pilote constructeur), sous le nom exact donné dans
    // PRINTER_USB_NAME ; le spouleur Windows relaie ensuite les octets bruts au périphérique.
    // Linux : écriture directe sur le périphérique bloc de l'imprimante (ex: /dev/usb/lp0).

    public static function sendToUsbPrinter(string $data, ?string $devicePath = null): array
    {
        $devicePath = $devicePath ?: (defined('PRINTER_USB_NAME') ? PRINTER_USB_NAME : 'POS-80');

        if (stripos(PHP_OS, 'WIN') === 0) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'ticket_') . '.prn';
            file_put_contents($tmpFile, $data);

            $commande = 'COPY /B ' . escapeshellarg($tmpFile) . ' ' . escapeshellarg('\\\\.\\' . $devicePath);
            exec($commande, $sortie, $code);
            @unlink($tmpFile);

            if ($code !== 0) {
                return ['success' => false, 'message' => "Imprimante USB \"$devicePath\" non détectée ou inaccessible."];
            }
            return ['success' => true, 'message' => 'Ticket envoyé à l\'imprimante USB.'];
        }

        // Linux : $devicePath est ici un chemin de périphérique (ex: /dev/usb/lp0)
        if (@file_put_contents($devicePath, $data) === false) {
            return ['success' => false, 'message' => "Imprimante USB \"$devicePath\" non détectée ou inaccessible."];
        }
        return ['success' => true, 'message' => 'Ticket envoyé à l\'imprimante USB.'];
    }

    // --- Point d'entrée unique : lit PRINTER_MODE (.env) et route vers le bon envoi ---

    public static function printBillet(array $billet): array
    {
        return self::envoyer(self::buildBilletContent($billet));
    }

    public static function printColis(array $colis): array
    {
        return self::envoyer(self::buildColisContent($colis));
    }

    private static function envoyer(string $data): array
    {
        $mode = strtolower(defined('PRINTER_MODE') ? PRINTER_MODE : 'network');

        if ($mode === 'usb') {
            return self::sendToUsbPrinter($data);
        }

        $ip = defined('PRINTER_IP') ? PRINTER_IP : '192.168.1.100';
        $port = (int) (defined('PRINTER_PORT') ? PRINTER_PORT : 9100);
        return self::sendToNetworkPrinter($ip, $port, $data);
    }
}
