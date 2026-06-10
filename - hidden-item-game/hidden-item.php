<?php

$grid = [
    ['#', '#', '#', '#', '#', '#', '#', '#'],
    ['#', '.', '.', '.', '.', '.', '.', '#'],
    ['#', '.', '#', '#', '#', '.', '.', '#'],
    ['#', '.', '.', '.', '#', '.', '#', '#'],
    ['#', 'X', '#', '.', '.', '.', '.', '#'],
    ['#', '#', '#', '#', '#', '#', '#', '#']
];

$startX = 4;
$startY = 1;

echo "--- Permainan Hidden Item ---\n";
// foreach ($grid as $row) {
//     echo implode(" ", $row) . "\n";
// }
echo "Posisi awal ditemukan di: ($startX, $startY)\n";

echo "Masukkan langkah Up/North (A): ";
$a = (int)trim(fgets(STDIN));
echo "Masukkan langkah Right/East (B): ";
$b = (int)trim(fgets(STDIN));
echo "Masukkan langkah Down/South (C): ";
$c = (int)trim(fgets(STDIN));

$targetRow = $startX - $a + $c;
$targetCol = $startY + $b;

echo "\n--- Hasil Analisis ---\n";

if ($targetRow < 0 || $targetRow >= count($grid) || $targetCol < 0 || $targetCol >= count($grid[0])) {
    echo "Error: Koordinat di luar batas grid!\n";
} 
elseif ($grid[$targetRow][$targetCol] === '#') {
    echo "Item tidak ditemukan di ($targetRow, $targetCol) karena terhalang tembok (#).\n";

    // $grid[$targetRow][$targetCol] = '@';
    
    // echo "\nGrid dengan posisi item:\n";
    // foreach ($grid as $row) {
    //     echo implode(" ", $row) . "\n";
    // }
} 
else {
    echo "Item ditemukan di koordinat: ($targetRow, $targetCol)\n";
    
    $grid[$targetRow][$targetCol] = '$';
    
    echo "\nGrid dengan posisi item:\n";
    foreach ($grid as $row) {
        echo implode(" ", $row) . "\n";
    }
}

?>