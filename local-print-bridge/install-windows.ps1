<#
.SYNOPSIS
    Installe le pont d'impression thermique local sur ce poste de comptoir :
    PHP (si absent), les fichiers du pont, la config imprimante, et le
    démarrage automatique avec Windows.

.EXAMPLE
    .\install-windows.ps1 -PrinterMode network -PrinterIp 192.168.1.100 -PrinterPort 9100

.EXAMPLE
    # IP non fournie : le script scanne le réseau local à la recherche d'une imprimante
    # (port 9100 ouvert) et propose de choisir si plusieurs sont trouvées.
    .\install-windows.ps1 -PrinterMode network

.EXAMPLE
    .\install-windows.ps1 -PrinterMode usb -PrinterUsbName "POS-80"

.EXAMPLE
    # -AllowLan : le pont écoute aussi sur le réseau local (pas juste ce poste), pour
    # qu'un téléphone sur le même Wi-Fi puisse imprimer via ce PC. Sur le téléphone, ouvrir
    # le site puis, une fois sur l'écran "Pont d'impression injoignable", choisir
    # "Configurer l'adresse" et saisir l'IP affichée en fin d'installation (ex: 192.168.1.50:9200).
    .\install-windows.ps1 -PrinterMode network -AllowLan

.NOTES
    À exécuter en PowerShell sur CHAQUE poste équipé d'une imprimante thermique.
    Peut être relancé sans risque (réinstalle/écrase proprement).
    Ne nécessite PAS de droits administrateur (installation dans le profil utilisateur).
    Si l'exécution de scripts est bloquée, lancer via :
      powershell -ExecutionPolicy Bypass -File install-windows.ps1 -PrinterMode network -PrinterIp ...
#>

param(
    [ValidateSet('network', 'usb')]
    [string]$PrinterMode = 'network',
    # Laissé vide par défaut : en mode network, ça déclenche la détection automatique
    # (scan du réseau local) plutôt que de silencieusement configurer une IP placeholder
    # qui ne correspond à rien sur ce site.
    [string]$PrinterIp = '',
    [int]$PrinterPort = 9100,
    [string]$PrinterUsbName = 'POS-80',
    [string]$AllowedOrigins = 'https://annexpress.malitecnologie.com,https://devannexpress.malitecnologie.com',
    # Par défaut, le pont n'écoute qu'en local (127.0.0.1) : seul CE poste peut l'appeler.
    # Avec -AllowLan, il écoute sur tout le réseau (0.0.0.0) pour qu'un téléphone sur le
    # même Wi-Fi puisse aussi l'appeler, via l'IP de ce poste. Désactivé par défaut : ça
    # n'expose le pont au réseau que si on le demande explicitement, pas silencieusement
    # sur toutes les installations existantes.
    [switch]$AllowLan,
    [string]$InstallDir = (Join-Path $env:LOCALAPPDATA 'PrintBridge'),
    [string]$PhpDir = (Join-Path $env:LOCALAPPDATA 'PrintBridge\php'),
    [string]$RepoRawBase = 'https://raw.githubusercontent.com/Aminata2004/Projets_licence/main/local-print-bridge'
)

$ecouteAdresse = if ($AllowLan) { '0.0.0.0' } else { '127.0.0.1' }

$ErrorActionPreference = 'Stop'

function Write-Etape($texte) {
    Write-Host ""
    Write-Host "==> $texte" -ForegroundColor Cyan
}

# Scanne le(s) sous-réseau(x) /24 de ce poste à la recherche d'hôtes qui répondent sur le
# port d'impression réseau standard (9100, protocole "raw"/JetDirect utilisé par la quasi-
# totalité des imprimantes thermiques réseau). Un pool de runspaces (threads légers, pas de
# nouveaux processus comme le ferait Start-Job) permet de tester les 254 adresses en
# parallèle en quelques secondes au lieu de plusieurs minutes en séquentiel.
function Find-NetworkPrinters {
    param([int]$Port = 9100, [int]$TimeoutMs = 300, [int]$MaxParallele = 60)

    # Sous-réseaux locaux à scanner : toutes les IPv4 "normales" de ce poste (Wi-Fi,
    # Ethernet...), en excluant loopback et les IP APIPA (169.254.x.x = pas de réseau
    # configuré). On déduit un préfixe /24 par IP trouvée (largement le cas le plus
    # courant sur un petit réseau d'agence).
    $prefixes = Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue |
        Where-Object {
            $_.IPAddress -notlike '127.*' -and
            $_.IPAddress -notlike '169.254.*'
        } |
        ForEach-Object { ($_.IPAddress -split '\.')[0..2] -join '.' } |
        Select-Object -Unique

    if (-not $prefixes) {
        Write-Host "Impossible de déterminer le réseau local de ce poste." -ForegroundColor Yellow
        return @()
    }

    $cibles = foreach ($prefixe in $prefixes) {
        1..254 | ForEach-Object { "$prefixe.$_" }
    }

    $scriptTest = {
        param($ip, $port, $timeoutMs)
        $client = New-Object System.Net.Sockets.TcpClient
        try {
            $resultat = $client.BeginConnect($ip, $port, $null, $null)
            if ($resultat.AsyncWaitHandle.WaitOne($timeoutMs) -and $client.Connected) {
                return $ip
            }
        } catch {
            # Hôte injoignable/port fermé : pas une erreur, juste "pas d'imprimante ici"
        } finally {
            $client.Close()
        }
        return $null
    }

    $pool = [runspacefactory]::CreateRunspacePool(1, $MaxParallele)
    $pool.Open()
    $taches = foreach ($ip in $cibles) {
        $ps = [powershell]::Create().AddScript($scriptTest).AddArgument($ip).AddArgument($Port).AddArgument($TimeoutMs)
        $ps.RunspacePool = $pool
        [PSCustomObject]@{ Pipeline = $ps; Handle = $ps.BeginInvoke() }
    }

    $trouves = foreach ($tache in $taches) {
        $ip = $tache.Pipeline.EndInvoke($tache.Handle)
        $tache.Pipeline.Dispose()
        if ($ip) { $ip }
    }

    $pool.Close()
    $pool.Dispose()

    return @($trouves)
}

# --- 1. PHP ---
Write-Etape "Vérification de PHP"
$phpExe = Join-Path $PhpDir 'php.exe'

if (Test-Path $phpExe) {
    Write-Host "PHP déjà présent : $phpExe"
} elseif (Get-Command php -ErrorAction SilentlyContinue) {
    $phpExe = (Get-Command php).Source
    Write-Host "PHP déjà disponible dans le PATH : $phpExe"
} else {
    Write-Host "PHP introuvable, téléchargement..."
    $phpZipUrl = 'https://windows.php.net/downloads/releases/php-8.3.16-nts-Win32-vs16-x64.zip'
    $phpZip = Join-Path $env:TEMP 'php-nts.zip'
    try {
        Invoke-WebRequest -Uri $phpZipUrl -OutFile $phpZip -UseBasicParsing
        New-Item -ItemType Directory -Force -Path $PhpDir | Out-Null
        Expand-Archive -Path $phpZip -DestinationPath $PhpDir -Force
        Remove-Item $phpZip -Force
        Write-Host "PHP installé dans $PhpDir"
    } catch {
        Write-Host "Échec du téléchargement automatique de PHP : $_" -ForegroundColor Red
        Write-Host "Installez PHP manuellement depuis https://windows.php.net/download puis relancez ce script." -ForegroundColor Yellow
        exit 1
    }
}

if (-not (Test-Path $phpExe)) {
    Write-Host "php.exe introuvable après installation ($phpExe). Vérifiez $PhpDir." -ForegroundColor Red
    exit 1
}

# --- 2. Fichiers du pont ---
Write-Etape "Téléchargement des fichiers du pont d'impression"
New-Item -ItemType Directory -Force -Path $InstallDir | Out-Null

foreach ($fichier in @('bridge.php', 'ThermalPrinter.php')) {
    $dest = Join-Path $InstallDir $fichier
    Invoke-WebRequest -Uri "$RepoRawBase/$fichier" -OutFile $dest -UseBasicParsing
    Write-Host "  - $fichier"
}

# --- 3. Config imprimante de ce poste ---
Write-Etape "Configuration de l'imprimante"

# IP non fournie en mode network : on tente de la trouver tout seul avant de demander à
# l'utilisateur de la connaître par cœur.
if ($PrinterMode -eq 'network' -and [string]::IsNullOrWhiteSpace($PrinterIp)) {
    Write-Host "Aucune IP fournie : recherche d'imprimantes sur le réseau local (port $PrinterPort)..."
    $candidats = Find-NetworkPrinters -Port $PrinterPort

    if ($candidats.Count -eq 1) {
        $PrinterIp = $candidats[0]
        Write-Host "Imprimante trouvée automatiquement : $PrinterIp" -ForegroundColor Green
    } elseif ($candidats.Count -gt 1) {
        Write-Host "Plusieurs appareils répondent sur le port $PrinterPort :" -ForegroundColor Yellow
        for ($i = 0; $i -lt $candidats.Count; $i++) {
            Write-Host "  [$($i + 1)] $($candidats[$i])"
        }
        do {
            $choix = Read-Host "Quel est le numéro de l'imprimante de CE comptoir ? (1-$($candidats.Count))"
        } while (-not ($choix -as [int]) -or [int]$choix -lt 1 -or [int]$choix -gt $candidats.Count)
        $PrinterIp = $candidats[[int]$choix - 1]
    } else {
        Write-Host "Aucune imprimante détectée automatiquement sur ce réseau." -ForegroundColor Yellow
        $PrinterIp = Read-Host "Entrez l'adresse IP de l'imprimante manuellement (ex: 192.168.1.100)"
        if ([string]::IsNullOrWhiteSpace($PrinterIp)) {
            Write-Host "Aucune IP fournie, installation interrompue." -ForegroundColor Red
            exit 1
        }
    }
}

$envContent = @"
PRINTER_MODE=$PrinterMode
PRINTER_IP=$PrinterIp
PRINTER_PORT=$PrinterPort
PRINTER_USB_NAME=$PrinterUsbName
ALLOWED_ORIGINS=$AllowedOrigins
"@
Set-Content -Path (Join-Path $InstallDir '.env') -Value $envContent -Encoding utf8
Write-Host "Config écrite dans $InstallDir\.env (mode: $PrinterMode)"

# --- 4. Lancement silencieux + démarrage automatique ---
Write-Etape "Configuration du démarrage automatique"
$vbsPath = Join-Path $InstallDir 'lancer.vbs'
$bridgePath = Join-Path $InstallDir 'bridge.php'
$vbsContent = 'CreateObject("Wscript.Shell").Run """{0}"" -S {1}:9200 ""{2}""", 0, False' -f $phpExe, $ecouteAdresse, $bridgePath
Set-Content -Path $vbsPath -Value $vbsContent -Encoding ascii

$startupDir = [Environment]::GetFolderPath('Startup')
$shortcutPath = Join-Path $startupDir 'PrintBridge.lnk'
$ws = New-Object -ComObject WScript.Shell
$shortcut = $ws.CreateShortcut($shortcutPath)
$shortcut.TargetPath = $vbsPath
$shortcut.WorkingDirectory = $InstallDir
$shortcut.Save()
Write-Host "Raccourci de démarrage créé : $shortcutPath"

# --- 5. Démarrage immédiat + test ---
Write-Etape "Démarrage du pont et test"
Get-Process php -ErrorAction SilentlyContinue |
    Where-Object { $_.Path -eq $phpExe } |
    Stop-Process -Force -ErrorAction SilentlyContinue

Start-Process -FilePath $phpExe -ArgumentList "-S ${ecouteAdresse}:9200 `"$bridgePath`"" -WindowStyle Hidden

# Le tout premier lancement peut afficher une invite "Autoriser l'accès" du pare-feu
# Windows ; le port ne répond qu'une fois cette invite acceptée. On retente donc
# plusieurs fois avant de conclure à un échec, au lieu de tester une seule fois trop tôt.
$pontOk = $false
for ($tentative = 1; $tentative -le 8; $tentative++) {
    Start-Sleep -Seconds 1
    try {
        $test = Invoke-WebRequest -Uri 'http://127.0.0.1:9200/health' -UseBasicParsing -TimeoutSec 2
        Write-Host ""
        Write-Host "Le pont d'impression répond correctement (code $($test.StatusCode))." -ForegroundColor Green
        $pontOk = $true
        break
    } catch {
        continue
    }
}

if (-not $pontOk) {
    Write-Host ""
    Write-Host "Le pont ne répond pas encore." -ForegroundColor Yellow
    Write-Host "Vérifiez s'il y a une invite du pare-feu Windows à accepter (souvent en arrière-plan)," -ForegroundColor Yellow
    Write-Host "puis retestez dans le navigateur : http://127.0.0.1:9200/health" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Installation terminée. Le pont démarrera automatiquement à chaque ouverture de session Windows." -ForegroundColor Green

if ($AllowLan) {
    Write-Host ""
    Write-Host "Le pont écoute aussi sur le réseau local (-AllowLan) : un téléphone sur le même" -ForegroundColor Cyan
    Write-Host "Wi-Fi peut donc l'appeler via l'adresse de CE poste, pas juste 127.0.0.1." -ForegroundColor Cyan
    $ipsLocales = (Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue |
        Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' }).IPAddress
    if ($ipsLocales) {
        Write-Host "Adresse à configurer sur le téléphone :" -ForegroundColor Cyan
        $ipsLocales | ForEach-Object { Write-Host "  $($_):9200" }
    }
    Write-Host "Si Windows affiche une invite pare-feu, autorisez l'accès pour les réseaux 'Privés'." -ForegroundColor Yellow
}
