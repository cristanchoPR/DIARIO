<?php
$f1 = 'C:\Users\crist\.gemini\antigravity\brain\b682ef9a-4416-485a-ab9e-f516c6d8e110\media__1785226263107.png';
$f2 = 'C:\Users\crist\.gemini\antigravity\brain\b682ef9a-4416-485a-ab9e-f516c6d8e110\media__1785226288575.png';

$s1 = getimagesize($f1);
$s2 = getimagesize($f2);

// The wifi icon is more square than a wide tab screenshot
$ratio1 = $s1[0] / $s1[1];
$ratio2 = $s2[0] / $s2[1];

if ($ratio1 < $ratio2) {
    // f1 is more square
    copy($f1, 'public/favicon.png');
} else {
    // f2 is more square
    copy($f2, 'public/favicon.png');
}
echo "Done copying favicon";
