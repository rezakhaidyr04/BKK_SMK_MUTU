<?php
// Generate public/images/logo_mutu.png (200x200)
$base = __DIR__ . '/../public/images';
@mkdir($base, 0755, true);
$out = $base . '/logo_mutu.png';
$svg = $base . '/logo_mutu.svg';

if (file_exists($out)) {
    echo "File already exists: $out\n";
    exit(0);
}

if (extension_loaded('imagick')) {
    try {
        $im = new Imagick();
        $im->setBackgroundColor(new ImagickPixel('transparent'));
        $im->readImage($svg);
        $im->setImageFormat('png32');
        $im->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
        $im->writeImage($out);
        echo "Created PNG via Imagick: $out\n";
        exit(0);
    } catch (Exception $e) {
        echo "Imagick failed: " . $e->getMessage() . "\n";
    }
}

if (!function_exists('imagecreatetruecolor')) {
    echo "Neither Imagick nor GD available to generate PNG.\n";
    exit(1);
}

$w = 200; $h = 200;
$img = imagecreatetruecolor($w, $h);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);

// radial gradient circle from #2563EB to #7C3AED
$sr = 37; $sg = 99; $sb = 235;
$er = 124; $eg = 58; $eb = 237;
$cx = $w/2; $cy = $h/2; $rmax = $w/2;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $dx = $x - $cx;
        $dy = $y - $cy;
        $d = sqrt($dx*$dx + $dy*$dy) / $rmax;
        if ($d > 1.0) {
            // outside circle -> transparent
            imagesetpixel($img, $x, $y, $transparent);
            continue;
        }
        $ir = (int)($sr + ($er - $sr) * $d);
        $ig = (int)($sg + ($eg - $sg) * $d);
        $ib = (int)($sb + ($eb - $sb) * $d);
        $col = imagecolorallocate($img, $ir, $ig, $ib);
        imagesetpixel($img, $x, $y, $col);
    }
}

// Draw inner white text 'BKK'
$white = imagecolorallocate($img, 255, 255, 255);
$fontSize = 5; // built-in font
$text = 'BKK';
$tw = imagefontwidth($fontSize) * strlen($text);
$th = imagefontheight($fontSize);
imagestring($img, $fontSize, (int)(($w - $tw)/2), (int)(($h - $th)/2), $text, $white);

// Save PNG
if (imagepng($img, $out)) {
    imagedestroy($img);
    echo "Created PNG via GD: $out\n";
    exit(0);
} else {
    imagedestroy($img);
    echo "Failed to write PNG to $out\n";
    exit(1);
}
