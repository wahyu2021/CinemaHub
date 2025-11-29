# 🛠️ CinemaHub Setup Guide

Panduan langkah demi langkah untuk menginstal dan menjalankan proyek CinemaHub di lingkungan lokal Anda.

## 📋 Prasyarat (Requirements)

Pastikan komputer Anda sudah terinstall:

1.  **PHP** (Versi 8.2 atau terbaru)
2.  **Composer** (Dependency Manager untuk PHP)
3.  **Node.js & NPM** (Untuk compile frontend aset)
4.  **Database MySQL** (Bisa pakai XAMPP, Laragon, atau Docker)
5.  **Git** (Opsional, untuk clone repo)

---

## 🚀 Langkah Instalasi

### 1. Clone Repository / Unduh File
Jika menggunakan Git:
```bash
git clone https://github.com/username-anda/tugas-api-publik.git
cd tugas-api-publik
```
*Atau ekstrak file ZIP proyek ke folder kerja Anda.*

### 2. Install Dependency Backend (Laravel)
Jalankan perintah berikut di terminal dalam folder proyek:
```bash
composer install
```

### 3. Install Dependency Frontend (NPM)
Install paket-paket JavaScript yang dibutuhkan:
```bash
npm install
```

### 4. Konfigurasi Environment (.env)
Salin file contoh konfigurasi:
```bash
cp .env.example .env
```

Buka file `.env` menggunakan text editor (VS Code, Notepad, dll) dan atur konfigurasi berikut:

**Database:**
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinemahub_db  <-- Ganti dengan nama database Anda
DB_USERNAME=root          <-- Default XAMPP/Laragon biasanya 'root'
DB_PASSWORD=              <-- Kosongkan jika tidak ada password
```

**TMDB API Key (PENTING!):**
Anda harus mendaftar di [TheMovieDB.org](https://www.themoviedb.org/) untuk mendapatkan API Key gratis.
```ini
TMDB_API_KEY=masukkan_api_key_anda_disini_tanpa_tanda_petik
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_IMAGE_BASE_URL=https://image.tmdb.org/t/p
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Buat Database & Migrasi
1.  Buat database kosong di MySQL (misal: `cinemahub_db`) melalui phpMyAdmin atau terminal.
2.  Jalankan migrasi untuk membuat tabel:
```bash
php artisan migrate
```
*(Opsional) Jika ingin data dummy pengguna:*
```bash
php artisan db:seed
```

### 7. Menjalankan Aplikasi
Anda perlu membuka **dua terminal** berbeda:

**Terminal 1 (Menjalankan Server Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Kompilasi Aset Frontend - Vite):**
```bash
npm run dev
```

### 8. Selesai! 🎉
Buka browser dan akses: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🐛 Troubleshooting Umum

**Q: Error "Vite manifest not found"?**
A: Pastikan Anda sudah menjalankan `npm run dev` di terminal terpisah.

**Q: Error Database "Connection Refused"?**
A: Pastikan XAMPP/MySQL sudah menyala dan konfigurasi DB di `.env` sudah benar.

**Q: Gambar film tidak muncul?**
A: Pastikan `TMDB_API_KEY` di `.env` valid dan koneksi internet lancar.

**Q: Permission Error pada folder storage?**
A: Jalankan perintah (Linux/Mac): `chmod -R 777 storage bootstrap/cache`