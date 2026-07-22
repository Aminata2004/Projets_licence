<?php
/**
 * Génère les icônes PWA (public/assets_site/img/icons/) sans dépendance GD/Imagick.
 * Usage : php scripts/generate_pwa_icons.php
 * À relancer uniquement si le design de l'icône doit changer.
 */

function png_chunk(string $type, string $data): string
{
    return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
}

function png_encode(int $width, int $height, array $pixels): string
{
    $raw = '';
    for ($y = 0; $y < $height; $y++) {
        $raw .= "\x00";
        for ($x = 0; $x < $width; $x++) {
            [$r, $g, $b, $a] = $pixels[$y * $width + $x];
            $raw .= chr($r) . chr($g) . chr($b) . chr($a);
        }
    }
    $compressed = gzcompress($raw, 9);
    $png = "\x89PNG\r\n\x1a\n";
    $png .= png_chunk('IHDR', pack('NNCCCCC', $width, $height, 8, 6, 0, 0, 0));
    $png .= png_chunk('IDAT', $compressed);
    $png .= png_chunk('IEND', '');
    return $png;
}

function new_canvas(int $size, array $bg): array
{
    return array_fill(0, $size * $size, $bg);
}

function fill_rect(array &$canvas, int $size, float $fx0, float $fy0, float $fx1, float $fy1, array $color): void
{
    $x0 = (int) round($fx0 * $size);
    $x1 = (int) round($fx1 * $size);
    $y0 = (int) round($fy0 * $size);
    $y1 = (int) round($fy1 * $size);
    for ($y = $y0; $y < $y1; $y++) {
        for ($x = $x0; $x < $x1; $x++) {
            if ($x >= 0 && $x < $size && $y >= 0 && $y < $size) {
                $canvas[$y * $size + $x] = $color;
            }
        }
    }
}

function fill_circle(array &$canvas, int $size, float $fcx, float $fcy, float $fr, array $color): void
{
    $cx = $fcx * $size;
    $cy = $fcy * $size;
    $r = $fr * $size;
    $x0 = max(0, (int) floor($cx - $r));
    $x1 = min($size - 1, (int) ceil($cx + $r));
    $y0 = max(0, (int) floor($cy - $r));
    $y1 = min($size - 1, (int) ceil($cy + $r));
    for ($y = $y0; $y <= $y1; $y++) {
        for ($x = $x0; $x <= $x1; $x++) {
            $dx = $x + 0.5 - $cx;
            $dy = $y + 0.5 - $cy;
            if ($dx * $dx + $dy * $dy <= $r * $r) {
                $canvas[$y * $size + $x] = $color;
            }
        }
    }
}

function draw_icon(int $size): array
{
    $navy = [15, 59, 94, 255];
    $white = [255, 255, 255, 255];
    $orange = [230, 126, 34, 255];

    $canvas = new_canvas($size, $navy);

    // Carrosserie du bus
    fill_rect($canvas, $size, 0.14, 0.30, 0.86, 0.62, $white);

    // Fenêtres
    $windowCenters = [0.28, 0.50, 0.72];
    foreach ($windowCenters as $cx) {
        fill_rect($canvas, $size, $cx - 0.06, 0.35, $cx + 0.06, 0.50, $navy);
    }

    // Bande avant
    fill_rect($canvas, $size, 0.14, 0.56, 0.86, 0.62, $orange);

    // Roues (anneau blanc + moyeu foncé)
    foreach ([0.30, 0.70] as $cx) {
        fill_circle($canvas, $size, $cx, 0.665, 0.075, $white);
        fill_circle($canvas, $size, $cx, 0.665, 0.045, $navy);
    }

    return $canvas;
}

$outDir = __DIR__ . '/../public/assets_site/img/icons';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

$targets = [
    'icon-192.png' => 192,
    'icon-512.png' => 512,
    'apple-touch-icon.png' => 180,
];

foreach ($targets as $filename => $size) {
    $canvas = draw_icon($size);
    file_put_contents($outDir . '/' . $filename, png_encode($size, $size, $canvas));
    echo "Généré : $filename ({$size}x{$size})\n";
}
