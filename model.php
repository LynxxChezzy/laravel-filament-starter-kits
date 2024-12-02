#!/usr/bin/env php
<?php

$models = [];
do {
    echo "Masukkan nama model yang ingin Anda buat: ";
    $modelName = trim(fgets(STDIN)); // Ambil input dari terminal
    if (!empty($modelName)) {
        $models[] = $modelName;
    }

    echo "Apakah ada lagi? (y/n): ";
    $response = strtolower(trim(fgets(STDIN))); // Ambil input y/n
} while ($response === 'y');

if (empty($models)) {
    echo "Tidak ada model yang ditambahkan.\n";
    exit(0); // Keluar dari script jika tidak ada model
}

echo "Menjalankan perintah artisan untuk membuat model dan migration...\n";

foreach ($models as $model) {
    echo "Membuat model: $model\n";
    exec("php artisan make:model $model -m", $output, $returnCode);

    if ($returnCode === 0) {
        echo "Berhasil membuat $model.\n";
    } else {
        echo "Gagal membuat $model. Pesan error:\n";
        echo implode("\n", $output) . "\n";
    }
}

echo "Semua perintah selesai dijalankan.\n";
