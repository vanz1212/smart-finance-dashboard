<?php

$dir = __DIR__ . '/public/images/avatars';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// 10 different seeds for cute characters
$seeds = ['Felix', 'Luna', 'Max', 'Buster', 'Bella', 'Charlie', 'Lucy', 'Leo', 'Milo', 'Daisy'];

for ($i = 1; $i <= 10; $i++) {
    $seed = $seeds[$i - 1];
    // Using fun-emoji or avataaars. fun-emoji is very cute and animated-like.
    $url = "https://api.dicebear.com/9.x/fun-emoji/svg?seed=" . urlencode($seed) . "&backgroundColor=b6e3f4,c0aede,d1d4f9,ffdfbf,ffd5dc";
    
    // Fetch SVG
    $svg = file_get_contents($url);
    if ($svg) {
        file_put_contents("$dir/avatar$i.svg", $svg);
        echo "Created avatar $i\n";
    } else {
        echo "Failed to create avatar $i\n";
    }
}

echo "Finished generating 10 cute avatars in $dir\n";
