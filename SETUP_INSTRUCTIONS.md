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

### 3. Jalankan Aplikasi

```bash
# Install dependencies (jika belum)
composer install
npm install

# Generate application key (jika belum)
php artisan key:generate

# Jalankan server
php artisan serve
```

### 4. Buka di Browser

Buka http://localhost:8000

## ✨ Fitur-Fitur

### Core Features (Sesuai Requirement)
- ✅ Daftar Film - Grid/Card layout modern dengan poster
- ✅ Pencarian - Real-time search dengan autocomplete
- ✅ Filter & Sort - Genre, Rating, Tahun, Popularitas
- ✅ Pagination - Multiple pages navigation

### Extra Features (Bonus)
- ✅ Detail Film - Modal/page dengan info lengkap, trailer, cast
- ✅ Categories - Now Playing, Popular, Top Rated, Upcoming
- ✅ Favorites/Watchlist - Save film favorit (localStorage)
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
