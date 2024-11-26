<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## (ID) Langkah Instalasi

### Clone Repositori dari GitHub

Jalankan perintah berikut untuk meng-clone repositori starter kit dari GitHub ke lokal Anda.

```bash
git clone https://github.com/ZanQuenChezzyy/laravel-filament-starter-kits.git
```
Perintah ini akan mengunduh seluruh kode dari repositori ke folder dengan nama `laravel-filament-starter-kits`.

### Masuk ke Direktori Proyek
Setelah proses clone selesai, masuk ke dalam `direktori/folder` dengan perintah:

```bash
cd laravel-filament-starter-kits
```
Anda sekarang berada di dalam folder proyek tersebut, dan siap untuk melakukan konfigurasi lebih lanjut.

### Instalasi Dependensi Composer
Laravel menggunakan Composer untuk mengelola dependensi. Jalankan perintah berikut di terminal vs code anda untuk menginstal dependensi yang dibutuhkan oleh proyek:

```bash
composer install
```
Ini akan mengunduh dan menginstal semua dependensi yang tercantum di dalam file `composer.json`.

### Salin File .env dan Konfigurasi Environment
Laravel memerlukan file `.env` untuk konfigurasi lingkungan (environment). Anda bisa menyalin file `.env.example` menjadi `.env` dengan perintah berikut:

```bash
cp .env.example .env
```

### Generate Key Aplikasi Laravel
Laravel membutuhkan aplikasi key untuk enkripsi data. Anda dapat meng-generate key dengan menjalankan perintah berikut:

```bash
php artisan key:generate
```
Perintah ini akan menghasilkan key unik dan otomatis menambahkannya ke file `.env`.

### Konfigurasi `.env`
Buka file `.env` dan sesuaikan pengaturan koneksi basis data dan lainnya sesuai dengan konfigurasi basis data yang Anda gunakan sebagai contoh:

Konfigurasi Nama Aplikasi:
```php
# Sesuaikan dengan nama aplikasi yang ingin anda buat
APP_NAME="Laravel Filament" 
```

Konfigurasi Waktu Local:
```php
# Sesuaikan dengan waktu local anda
APP_TIMEZONE="Asia/Jakarta" 
```

Konfigurasi Bahasa:
```php
# Sesuaikan dengan kode Negara anda 
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
```

Konfigurasi Basis data:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter-kits # Sesuaikan dengan nama database yang ingin anda buat
DB_USERNAME=root
DB_PASSWORD=
```

### Migrasi Database dan Seed Data
Setelah mengonfigurasi `.env` serta basis data, jalankan perintah berikut untuk membuat tabel-tabel yang udah di buat oleh aplikasi:
```bash
php artisan migrate
```
lalu ketikkan perintah berikut agar data User, Role, Serta Permission langsung di buat oleh aplikasi:
```bash
php artisan db:seed --class=UserRolePermissionSeeder
```

### Menjalankan Server Lokal
Setelah semua langkah selesai, Anda bisa menjalankan aplikasi Laravel menggunakan perintah:
```bash
php artisan serve
```

### Akses Filament Admin Panel
Anda dapat mengakses panel admin Filament melalui URL berikut:
```bash
http://127.0.0.1:8000
```
Gunakan kredensial berikut untuk mengakses Admin Panel:

Masuk sebagai admin:  
email : `admin@starter.com`  
password : `12345678`

Masuk sebagai user:  
email : `user@starter.com`  
password : `12345678`

## Lisensi

Laravel framework dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
