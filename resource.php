#!/usr/bin/env php
<?php

$models = [];
do {
    echo "Masukkan Nama Model yang ingin dibuat resource: ";
    $modelName = trim(fgets(STDIN)); // Input nama model
    if (!empty($modelName)) {
        $models[] = $modelName;
    }

    echo "Apakah ada lagi? (Y/N): ";
    $response = strtolower(trim(fgets(STDIN))); // Input pilihan Y/N
} while ($response === 'y');

if (empty($models)) {
    echo "Tidak ada model yang ditambahkan.\n";
    exit(0); // Keluar jika tidak ada model
}

echo "Menjalankan perintah artisan untuk membuat Filament Resource...\n";

foreach ($models as $model) {
    echo "Membuat Filament Resource: $model\n";
    $command = "php artisan make:filament-resource $model --simple --generate --view";
    exec($command, $output, $returnCode);

    if ($returnCode === 0) {
        echo "Berhasil membuat resource untuk $model.\n";
    } else {
        echo "Gagal membuat resource untuk $model. Pesan error:\n";
        echo implode("\n", $output) . "\n";
    }
}

echo "Semua perintah selesai dijalankan.\n";
