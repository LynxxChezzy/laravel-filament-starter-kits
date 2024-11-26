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

### Instalasi Dependensi
Laravel menggunakan Composer dan Node untuk mengelola dependensi. Jalankan perintah berikut di terminal Anda untuk menginstal semua dependensi dan mengonfigurasi aplikasi Laravel secara otomatis:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```
`composer install` Ini akan mengunduh dan menginstal semua dependensi yang tercantum di dalam file `composer.json`.
`npm install` Ini akan mengunduh dan menginstal semua dependensi yang tercantum di dalam file `package.json`.
`cp .env.example .env` Ini akan  menyalin file `.env.example` menjadi `.env` untuk konfigurasi aplikasi.
`php artisan key:generate` ini akan menghasilkan key unik untuk enkripsi data Laravel dan otomatis menambahkannya ke file `.env`.

### Konfigurasi `.env`
Buka file `.env` dan sesuaikan pengaturan koneksi basis data dan lainnya sesuai dengan konfigurasi basis data yang Anda gunakan sebagai contoh:

Konfigurasi Nama Aplikasi:
```php
APP_NAME="Laravel Filament"
```
Sesuaikan dengan `nama aplikasi` yang ingin anda buat.
Konfigurasi URL Aplikasi:
```php
APP_URL=http://127.0.0.1:8000
```
Wajib menggunakan `http://127.0.0.1:8000` untuk filament atau aplikasi tidak akan bisa jalan.

Konfigurasi Waktu Local:
```php
APP_TIMEZONE="Asia/Jakarta" 
```
Sesuaikan dengan `waktu local` anda.

Konfigurasi Bahasa:
```php
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
```
Sesuaikan dengan `kode Negara` anda.

Konfigurasi Basis data:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter-kits 
DB_USERNAME=root
DB_PASSWORD=
```
Sesuaikan dengan nama database yang ingin anda buat.

### Migrasi Database dan Seed Data
Setelah mengonfigurasi `.env` serta basis data, jalankan perintah berikut untuk membuat `tabel`, lalu memasukkan data `User`, `Role`, dan `Permission` agar aplikasi dapat langsung digunakan:
```bash
php artisan migrate
php artisan db:seed --class=UserRolePermissionSeeder
php artisan serve
```
`php artisan migrate` perintah untuk membuat `tabel` secara otomatis melalui `migrations`.
`php artisan db:seed --class=UserRolePermissionSeeder` perintah untuk membuat data `User`, `Role`, serta `Permission` agar aplikasi bisa diakses.
`php artisan serve` perintah untuk menjalankan server lokal atau aplikasi laravel.

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
