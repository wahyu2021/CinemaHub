# CinemaHub - Aplikasi Penemuan Film

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## Tentang Aplikasi

CinemaHub adalah aplikasi web untuk menemukan dan mengeksplorasi film menggunakan The Movie Database (TMDB) API. Aplikasi ini dibangun dengan Laravel dan menampilkan antarmuka modern yang responsif dengan tema gelap.

## Fitur Utama

### Fitur Inti (Sesuai Requirement)
- ✅ **Daftar Film** - Tampilan grid/card modern dengan poster film
- ✅ **Pencarian** - Pencarian film secara real-time
- ✅ **Filter & Sortir** - Filter berdasarkan genre, rating, tahun, dan popularitas
- ✅ **Pagination** - Navigasi halaman yang mudah

### Fitur Tambahan (Bonus)
- ✅ **Detail Film** - Halaman detail lengkap dengan informasi, trailer, dan pemeran
- ✅ **Kategori** - Sedang Tayang, Populer, Rating Tertinggi, Akan Datang
- ✅ **Favorit/Watchlist** - Simpan film favorit (menggunakan localStorage)
- ✅ **Trending** - Film trending minggu ini
- ✅ **Rekomendasi** - Film serupa di halaman detail
- ✅ **Mode Gelap** - Tema gelap modern (gaya Netflix)
- ✅ **Desain Responsif** - Optimal untuk mobile, tablet, dan desktop
- ✅ **Loading States** - Transisi dan animasi yang halus
- ✅ **Error Handling** - Pesan error yang jelas

## Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Tailwind CSS
- **API**: The Movie Database (TMDB) API
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Inter)

## Instalasi & Setup

Lihat [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md) untuk panduan lengkap instalasi dan konfigurasi.

### Ringkasan Instalasi

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi TMDB API Key di .env
# TMDB_API_KEY=your_api_key_here

# Jalankan server
php artisan serve
```

Buka http://localhost:8000 di browser Anda.

## Fitur Desain

- **Skema Warna**: Tema gelap dengan aksen merah (terinspirasi Netflix)
- **Layout**: Grid berbasis card dengan efek hover
- **Tipografi**: Font Inter modern
- **Icons**: Font Awesome 6
- **CSS Framework**: Tailwind CSS
- **Animasi**: Transisi halus & efek hover

## Responsive Breakpoints

- Mobile: 2 kolom
- Tablet: 3-4 kolom
- Desktop: 5 kolom
- Desktop Besar: 6 kolom (halaman trending)

## Lisensi

Framework Laravel adalah perangkat lunak open-source yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
