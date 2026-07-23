<#
.SYNOPSIS
    Installe le pont d'impression thermique local sur ce poste de comptoir :
    PHP (si absent), les fichiers du pont, la config imprimante, et le
    démarrage automatique avec Windows.

.EXAMPLE
    .\install-windows.ps1 -PrinterMode network -PrinterIp 192.168.1.100 -PrinterPort 9100

.EXAMPLE
    .\install-windows.ps1 -PrinterMode usb -PrinterUsbName "POS-80"

.NOTES
    À exécuter en PowerShell sur CHAQUE poste équipé d'une imprimante thermique.
    Peut être relancé sans risque (réinstalle/écrase proprement).
#>

param(
    [ValidateSet('network', 'usb')]
    [string]$PrinterMode = 'network',
    [string]$PrinterIp = '192.168.1.100',
    [int]$PrinterPort = 9100,
    [string]$PrinterUsbName = 'POS-80',
    [string]$AllowedOrigins = 'https://annexpress.malitecnologie.com,https://devannexpress.malitecnologie.com',
    [string]$InstallDir = 'C:\print-bridge',
    [string]$PhpDir = 'C:\php',
    [string]$RepoRawBase = 'https://raw.githubusercontent.com/Aminata2004/Projets_licence/main/local-print-bridge'
)

$ErrorActionPreference = 'Stop'

function Write-Etape($texte) {
    Write-Host ""
    Write-Host "==> $texte" -ForegroundColor Cyan
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
$vbsContent = 'CreateObject("Wscript.Shell").Run """{0}"" -S 127.0.0.1:9200 ""{1}""", 0, False' -f $phpExe, $bridgePath
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

Start-Process -FilePath $phpExe -ArgumentList "-S 127.0.0.1:9200 `"$bridgePath`"" -WindowStyle Hidden
Start-Sleep -Seconds 1

try {
    $test = Invoke-WebRequest -Uri 'http://127.0.0.1:9200/print' -Method OPTIONS -Headers @{ Origin = ($AllowedOrigins -split ',')[0] } -UseBasicParsing
    Write-Host ""
    Write-Host "Le pont d'impression répond correctement (code $($test.StatusCode))." -ForegroundColor Green
} catch {
    Write-Host ""
    Write-Host "Le pont ne répond pas encore. Vérifiez qu'aucun pare-feu ne bloque le port 9200 en local." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Installation terminée. Le pont démarrera automatiquement à chaque ouverture de session Windows." -ForegroundColor Green
