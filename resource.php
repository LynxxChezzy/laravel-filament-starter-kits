#!/usr/bin/env php
<?php

// Path ke folder models
$modelsPath = __DIR__ . '/app/Models';

// Periksa apakah folder models ada
if (!is_dir($modelsPath)) {
    echo "Folder 'app/Models' tidak ditemukan.\n";
    exit(1);
}

// Ambil semua file PHP dari folder models
$modelFiles = glob($modelsPath . '/*.php');

// Pastikan ada file model
if (empty($modelFiles)) {
    echo "Tidak ada file model di folder 'app/Models'.\n";
    exit(0);
}

// Ambil nama model dari nama file
$models = array_map(function ($filePath) {
    return pathinfo($filePath, PATHINFO_FILENAME);
}, $modelFiles);

// Daftar model yang selalu dikecualikan (misalnya User dan Role)
$alwaysExcludedModels = ['User', 'Role'];

// Gabungkan model yang selalu dikecualikan ke dalam list pengecualian
$modelsToProcess = array_diff($models, $alwaysExcludedModels);

if (empty($modelsToProcess)) {
    echo "Tidak ada model yang tersedia untuk dibuatkan resource.\n";
    exit(0);
}

echo "Model yang ditemukan: " . implode(', ', $modelsToProcess) . "\n";

// Tanya ke user apakah ada model lain yang tidak ingin dibuatkan resource
echo "Apakah ada model yang tidak ingin dibuatkan resource? (Y/N): ";
$response = strtolower(trim(fgets(STDIN))); // Input pilihan Y/N

$excludedModels = [];
if ($response === 'y') {
    do {
        echo "Masukkan nama model yang tidak ingin dibuat resource: ";
        $excludedModel = trim(fgets(STDIN));

        if (!empty($excludedModel)) {
            if (in_array($excludedModel, $modelsToProcess)) {
                $excludedModels[] = $excludedModel;
                echo "Model '$excludedModel' akan dikecualikan.\n";
                // Pastikan model yang dikecualikan dihapus dari daftar model yang akan diproses
                $modelsToProcess = array_diff($modelsToProcess, [$excludedModel]);
            } else {
                echo "Model '$excludedModel' tidak ditemukan di folder 'app/Models'.\n";
            }
        }

        echo "Apakah ada lagi model yang ingin dikecualikan? (Y/N): ";
        $response = strtolower(trim(fgets(STDIN)));
    } while ($response === 'y');
}

if (empty($modelsToProcess)) {
    echo "Semua model dikecualikan. Tidak ada resource yang akan dibuat.\n";
    exit(0);
}

echo "Model yang akan dibuatkan resource: " . implode(', ', $modelsToProcess) . "\n";
echo "Menjalankan perintah artisan untuk membuat Filament Resource...\n";

// Jalankan artisan untuk setiap model yang tidak dikecualikan
foreach ($modelsToProcess as $model) {
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
