<?php

// Fungsi untuk menjalankan perintah di terminal
function runCommand($command)
{
    echo "Menjalankan perintah: $command\n";
    $output = null;
    $resultCode = null;
    exec($command, $output, $resultCode);

    if ($resultCode === 0) {
        echo "Perintah berhasil dijalankan.\n";
    } else {
        echo "Perintah gagal dijalankan. Kode hasil: $resultCode\n";
        echo implode("\n", $output) . "\n";
    }
}

// Menjalankan perintah migrasi dan seeding
runCommand("php artisan migrate");
runCommand("php artisan db:seed --class=UserRolePermissionSeeder");
runCommand("php resource.php");

// Menjalankan build frontend di terminal baru (Windows)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    runCommand("start cmd.exe /k \"npm run dev\"");
} else {
    // Jika di Linux/MacOS
    runCommand("nohup npm run dev &");
}

// Menjalankan php artisan serve di terminal baru (untuk Windows)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    runCommand("start cmd.exe /k \"php artisan serve\"");
} else {
    // Jika di Linux/MacOS
    runCommand("nohup php artisan serve &");
}

echo "Semua perintah selesai dijalankan.\n";
