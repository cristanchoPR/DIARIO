<?php
$img = imagecreatefrompng('public/images/logo_aldia_oficial.png');
if (!$img) {
    die("Failed to load image");
}
$width = imagesx($img);
$height = imagesy($img);

// The user said there are still borders on the sides.
// Let's crop 15 pixels from left and right, and 5 pixels from top and bottom.
$cropX = 18;
$cropY = 5;
$cropWidth = $width - ($cropX * 2);
$cropHeight = $height - ($cropY * 2);

$crop = imagecrop($img, ['x' => $cropX, 'y' => $cropY, 'width' => $cropWidth, 'height' => $cropHeight]);
if ($crop) {
    imagepng($crop, 'public/images/logo_aldia_oficial_cropped.png');
    echo "Cropped successfully. Original: {$width}x{$height}, Cropped: {$cropWidth}x{$cropHeight}\n";
} else {
    echo "Crop failed.\n";
}
