# CinemaHub - TMDB Movie Discovery App

## 🚀 Setup Instructions

### 1. Daftar TMDB API Key (Gratis, 5 menit)

1. Buka https://www.themoviedb.org/signup
2. Daftar akun dengan email Anda
3. Verifikasi email
4. Login ke akun TMDB
5. Pergi ke Settings → API → Request API Key
6. Pilih "Developer"
7. Isi form aplikasi:
   - Application Name: CinemaHub (atau nama bebas)
   - Application URL: http://localhost (untuk development)
   - Application Summary: Learning project for web development
8. Copy **API Key (v3 auth)**

### 2. Konfigurasi Environment

1. Buka file `.env`
2. Cari baris `TMDB_API_KEY=your_api_key_here`
3. Ganti `your_api_key_here` dengan API Key Anda
4. Simpan file

### 3. Konfigurasi Email (untuk Verifikasi)

1. Buka file `.env`
2. Konfigurasikan setting email:

**Untuk Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cinemahub.com"
MAIL_FROM_NAME="CinemaHub"
```

> **Note:** Untuk Gmail, gunakan App Password (bukan password biasa)
> - Aktifkan 2-Factor Authentication di akun Google
> - Buat App Password di https://myaccount.google.com/apppasswords

**Untuk Testing (tanpa kirim email asli):**
```env
MAIL_MAILER=log
```
Email akan tercatat di `storage/logs/laravel.log`

### 4. Jalankan Aplikasi

```bash
# Install dependencies (jika belum)
composer install
npm install

# Generate application key (jika belum)
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Jalankan server
php artisan serve
```

### 5. Buka di Browser

Buka http://localhost:8000

## ✨ Fitur-Fitur

### Core Features (Sesuai Requirement)
- ✅ Daftar Film - Grid/Card layout modern dengan poster
- ✅ Pencarian - Real-time search dengan autocomplete
- ✅ Filter & Sort - Genre, Rating, Tahun, Popularitas
- ✅ Pagination - Multiple pages navigation

### Authentication Features
- ✅ Register - Pendaftaran user dengan validasi
- ✅ Login - Autentikasi dengan remember me
- ✅ Email Verification - Verifikasi email wajib untuk akses watchlist
- ✅ Forgot Password - Reset password via email
- ✅ User Profile - Halaman profil dengan statistik

### Extra Features (Bonus)
- ✅ Detail Film - Modal/page dengan info lengkap, trailer, cast
- ✅ Categories - Now Playing, Popular, Top Rated, Upcoming
- ✅ Watchlist - Save film ke watchlist (database)
- ✅ Trending - Film trending minggu ini
- ✅ Recommendations - Film serupa di halaman detail
- ✅ Dark Mode - Tema gelap modern (Netflix-style)
- ✅ Responsive Design - Mobile, tablet, desktop optimized
- ✅ Loading States - Smooth transitions & animations
- ✅ Error Handling - Graceful error messages

## 🎨 Design Features

- **Color Scheme**: Dark theme dengan accent red (Netflix-inspired)
- **Layout**: Card-based grid dengan hover effects
- **Typography**: Modern Inter font
- **Icons**: Font Awesome 6
- **CSS Framework**: Tailwind CSS
- **Animations**: Smooth transitions & hover effects

## 📱 Responsive Breakpoints

- Mobile: 2 columns
- Tablet: 3-4 columns
- Desktop: 5 columns
- Large Desktop: 6 columns (trending page)

## 🔧 Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Tailwind CSS
- **API**: The Movie Database (TMDB) API
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Inter)

## 📝 Note

Aplikasi ini dibuat untuk tugas praktikum Rekayasa Web dengan fitur lengkap dan design menarik!
