# 🏛️ Cleopatra Laravel Boilerplate / Blueprint

Boilerplate dashboard modern menggunakan **Laravel 12**, **Vite 7**, **Tailwind CSS v4**, dan tema premium **Cleopatra Admin Dashboard**. Template ini sudah dikonfigurasi dengan sistem autentikasi kustom, manajemen hak akses (Roles & Permissions), serta alur kerja developer yang sangat efisien (Developer Experience).

---

## ✨ Fitur-Fitur Utama

Boilerplate ini menyediakan berbagai fitur dasar yang siap digunakan untuk mempercepat pengembangan aplikasi Anda:

### 1. 🎨 Frontend & UI Modern
- **Laravel 12.x** dengan engine template Blade.
- **Tailwind CSS v4.0.0** terintegrasi menggunakan compiler terbaru `@tailwindcss/vite` untuk performa build yang instan.
- **Cleopatra Dashboard Template**: Tampilan admin panel premium dengan UI yang rapi, transisi halus, dan responsif.
- **ApexCharts**: Widget visualisasi grafik interaktif (dihubungkan via CDN).
- **Font Awesome Pro**: Icon pack premium untuk visual yang lebih profesional.
- **Komponen Layout Terpisah**:
  - [app.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/app.blade.php) (Layout Utama)
  - [navbar.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/partials/navbar.blade.php) (Navbar atas dengan dropdown notifikasi & menu user)
  - [sidebar.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/partials/sidebar.blade.php) (Sidebar navigasi responsif)
  - [footer.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/partials/footer.blade.php) (Footer standar)

### 2. 🔑 Autentikasi Kustom & Keamanan
- Halaman Login Kustom [login.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/auth/login.blade.php) terintegrasi dengan tema Cleopatra.
- [AuthController](file:///c:/laragon/www/boilerplate-laravel/app/Http/Controllers/AuthController.php) & [AuthMiddleware](file:///c:/laragon/www/boilerplate-laravel/app/Http/Middleware/AuthMiddleware.php) (`isLogin`):
  - Proteksi serangan **Session Fixation** (melalui regenerasi ID sesi setelah login sukses).
  - Proteksi **CSRF** pada setiap form input & logout.
  - Dukungan fitur **Remember Me** ("Ingat saya di perangkat ini").
  - Otomatis mengarahkan pengguna yang sudah login ke Dashboard jika mencoba mengakses kembali halaman `/login`.

### 3. 🛡️ Role & Permission (Hak Akses)
- Integrasi package **`spatie/laravel-permission`** untuk mengelola hak akses tingkat lanjut.
- Trait `HasRoles` telah disematkan pada model [User](file:///c:/laragon/www/boilerplate-laravel/app/Models/User.php).
- Tabel migrasi hak akses (`create_permission_tables`) sudah dikonfigurasi.
- **User & Role Seeders**: Menyiapkan akun administrator dan admin secara otomatis untuk mempermudah testing awal.

### 4. ⚡ Developer Experience (DX) & Tooling
- **Composer Setup Command**: Satu perintah untuk menginstal dependensi PHP & JS, menyalin berkas lingkungan, membuat kunci enkripsi, dan migrasi database.
- **Composer Dev Server (Concurrently)**: Menjalankan seluruh environment lokal sekaligus (Vite dev server, artisan server, queue listener, dan real-time logger) hanya dengan satu perintah.

---

## 👥 Akun Demo Bawaan (Default Credentials)

Saat Anda menjalankan seeder database (`php artisan db:seed`), akun-akun berikut akan dibuat secara otomatis:

| Nama User | Email | Password | Role |
| :--- | :--- | :--- | :--- |
| **Administrator** | `administrator@xxx.com` | `administrator@xxx.com` | `administrator` |
| **Admin** | `admin@xxx.com` | `admin@xxx.com` | `admin` |

---

## 🚀 Panduan Instalasi & Penggunaan

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan boilerplate di komputer lokal Anda.

### 📋 Prasyarat Sistem
- **PHP >= 8.2**
- **Composer**
- **Node.js & NPM**
- **Database** (MySQL / MariaDB, PostgreSQL, atau SQLite)

---

### ⚙️ Cara Instalasi (Metode Manual)

1. **Clone repositori ini** ke direktori lokal Anda.
2. **Buat berkas `.env`** dengan menyalin `.env.example`:
   ```bash
   cp .env.example .env
   ```
3. **Konfigurasi Database** di dalam berkas `.env`:
   Jika menggunakan MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=password_database_anda
   ```
   *Catatan: Pastikan database kosong dengan nama yang sesuai sudah dibuat di server database Anda.*

4. **Instal dependensi PHP & generate Application Key**:
   ```bash
   composer install
   php artisan key:generate
   ```
5. **Jalankan Migrasi Database dan Seeders**:
   Perintah ini akan membuat semua tabel yang diperlukan beserta akun demo bawaan:
   ```bash
   php artisan migrate --seed
   ```
6. **Instal dependensi JavaScript & build assets**:
   ```bash
   npm install
   npm run build
   ```

---

### ⚡ Cara Instalasi Cepat (Metode Otomatis)

Boilerplate ini menyediakan perintah otomatis menggunakan composer script untuk memotong langkah-langkah di atas:

```bash
composer run setup
```
> [!NOTE]
> Perintah `composer setup` secara otomatis akan menginstal dependensi PHP, membuat file `.env` (jika belum ada), melakukan generate key, menjalankan migrasi database, menginstal npm, dan melakukan build aset frontend. 
> Sebelum menjalankan perintah ini, pastikan konfigurasi database di file `.env` Anda sudah sesuai.

---

## 💻 Menjalankan Server Lokal

Untuk mulai mengembangkan aplikasi atau melihat tampilan dashboard di browser lokal Anda:

Jalankan perintah berikut:
```bash
composer run dev
```

Perintah di atas menggunakan package `concurrently` untuk menjalankan 4 layanan sekaligus di latar belakang:
1. **Server Laravel** (`php artisan serve`) di `http://127.0.0.1:8000`
2. **Vite Dev Server** (`npm run dev`) untuk pembaruan instan aset (HMR)
3. **Queue Listener** (`php artisan queue:listen`) untuk memproses jobs antrean
4. **Laravel Pail** (`php artisan pail`) untuk monitoring log aplikasi real-time

Sekarang buka **[http://localhost:8000/login](http://localhost:8000/login)** di browser Anda dan masuk menggunakan salah satu [Akun Demo Bawaan](#-akun-demo-bawaan-default-credentials).

---

## 📂 Struktur Folder Penting

Berikut adalah beberapa berkas dan direktori penting dalam boilerplate ini:

- [AuthController.php](file:///c:/laragon/www/boilerplate-laravel/app/Http/Controllers/AuthController.php) - Logika otentikasi login & logout.
- [AuthMiddleware.php](file:///c:/laragon/www/boilerplate-laravel/app/Http/Middleware/AuthMiddleware.php) - Middleware kustom `isLogin` untuk memproteksi halaman login dari user yang sudah terautentikasi.
- [UserRoleSeeder.php](file:///c:/laragon/www/boilerplate-laravel/database/seeders/UserRoleSeeder.php) - Pengaturan role default (`administrator` & `admin`) serta pembuatan akun demo.
- [app.blade.php](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/app.blade.php) - Layout utama dashboard Cleopatra.
- [resources/views/layouts/partials/](file:///c:/laragon/www/boilerplate-laravel/resources/views/layouts/partials) - Komponen layout seperti sidebar, navbar, dan footer.
- [app.css](file:///c:/laragon/www/boilerplate-laravel/resources/css/app.css) - File CSS utama yang menggunakan sintaksis baru Tailwind CSS v4.
- [vite.config.js](file:///c:/laragon/www/boilerplate-laravel/vite.config.js) - Konfigurasi integrasi Vite 7 dengan plugin `@tailwindcss/vite`.
