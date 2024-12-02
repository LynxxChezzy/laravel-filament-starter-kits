#!/usr/bin/env php
<?php

$models = [];

do {
    // Input nama model
    echo "Masukkan nama model yang ingin Anda buat: ";
    $modelName = trim(fgets(STDIN));

    if (!empty($modelName)) {
        if (!in_array($modelName, $models)) {
            $models[] = $modelName; // Tambahkan ke array jika belum ada
        } else {
            echo "Model '$modelName' sudah ditambahkan.\n";
        }
    }

    // Tanya apakah ada model lain
    echo "Apakah ada lagi? (y/n): ";
    $response = strtolower(trim(fgets(STDIN)));
} while ($response === 'y');

if (empty($models)) {
    echo "Tidak ada model yang ditambahkan.\n";
    exit(0); // Keluar dari script jika tidak ada model
}

echo "Menjalankan perintah artisan untuk membuat model dan migration...\n";

foreach ($models as $model) {
    echo "Membuat model: $model\n";

    // Jalankan perintah artisan
    exec("php artisan make:model $model -m", $output, $returnCode);

    if ($returnCode === 0) {
        echo "Berhasil membuat model $model.\n";

        // Path ke file model yang baru saja dibuat
        $modelFilePath = __DIR__ . "/app/Models/$model.php";

        // Pastikan file model ada
        if (file_exists($modelFilePath)) {
            // Isi template untuk model
            $modelContent = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\nuse Illuminate\Database\Eloquent\Model;\n\nclass $model extends Model\n{\n    use HasFactory;\n\n    protected \$fillable = [\n        'nama',\n    ];\n}\n";

            // Tulis ulang isi file model dengan template di atas
            file_put_contents($modelFilePath, $modelContent);
            echo "File model $model berhasil diubah dengan template yang diinginkan.\n";
        }
    } else {
        echo "Gagal membuat model $model. Pesan error:\n";
        echo implode("\n", $output) . "\n";
    }

    // Bersihkan output buffer untuk perintah berikutnya
    $output = [];
}

echo "Semua perintah selesai dijalankan.\n";
