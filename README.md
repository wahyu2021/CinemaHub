# 🎬 CinemaHub

**CinemaHub** adalah platform penemuan film modern dan futuristik yang dibangun menggunakan **Laravel 12**. Aplikasi ini mengintegrasikan **TMDB API** untuk menyajikan data film secara *real-time*, dilengkapi dengan fitur autentikasi pengguna, daftar tontonan (watchlist), dan dukungan multi-bahasa.

![CinemaHub Preview](https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80)

## 🚀 Fitur Utama

*   **Movie Discovery**: Menampilkan film Trending, Popular, Top Rated, dan Upcoming secara real-time.
*   **Pencarian Instan**: Cari film berdasarkan judul, genre, atau aktor.
*   **Multi-Bahasa (i18n)**: Dukungan penuh Bahasa Inggris (EN) dan Bahasa Indonesia (ID).
*   **Autentikasi Pengguna**: Login, Register, Verifikasi Email, dan Reset Password.
*   **Personal Watchlist**: Simpan film favorit ke dalam daftar tontonan pribadi.
*   **Desain Modern**: Antarmuka "Glassmorphism" dengan tema luar angkasa yang responsif.
*   **Detail Film**: Informasi lengkap termasuk trailer, pemeran, dan rekomendasi.

## 🛠️ Teknologi yang Digunakan

*   **Framework**: [Laravel 12](https://laravel.com)
*   **Database**: MySQL
*   **Frontend**: Blade Templates, [Tailwind CSS](https://tailwindcss.com), Vanilla JS
*   **API**: [The Movie Database (TMDB)](https://www.themoviedb.org/documentation/api)
*   **Icons**: FontAwesome

## 🏗️ Arsitektur & Best Practices

Proyek ini menerapkan prinsip **Clean Code** dan **SOLID**:

### 1. Service Pattern (`App\Services\TmdbService`)
Logika komunikasi dengan API eksternal dipisahkan dari Controller. Service ini menangani HTTP request, caching, dan formatting data.
*   **Caching**: Respons API disimpan dalam cache (Redis/File) untuk performa maksimal. Cache bersifat dinamis berdasarkan *locale* (bahasa) yang dipilih pengguna.

### 2. View Components (DRY)
Menghindari duplikasi kode pada tampilan dengan menggunakan Blade Components:
*   `<x-hero-slider>`: Komponen slider utama.
*   `<x-movie-section>`: Komponen slider horizontal untuk berbagai kategori.
*   `<x-movie-card>`: Kartu film standar.
*   `<x-filters.movie-filter>`: Panel filter interaktif.

### 3. Model Accessors & Casts
Menggunakan fitur modern Laravel 12 `Casts\Attribute` pada model `User` untuk memformat data (contoh: menghitung lama hari bergabung).

### 4. Middleware (`App\Http\Middleware\SetLocale`)
Mendeteksi dan mengatur bahasa aplikasi secara otomatis berdasarkan sesi pengguna.

## ⚙️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal:

### Prasyarat
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   API Key dari [TMDB](https://www.themoviedb.org/)

### Langkah-langkah

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/cinema-hub.git
    cd cinema-hub
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    
    Buka file `.env` dan atur konfigurasi berikut:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=cinemahub
    DB_USERNAME=root
    DB_PASSWORD=

    # TMDB API Key (Wajib)
    TMDB_API_KEY=masukkan_api_key_anda_disini
    TMDB_BASE_URL=https://api.themoviedb.org/3
    TMDB_IMAGE_BASE_URL=https://image.tmdb.org/t/p
    ```

4.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database**
    ```bash
    php artisan migrate
    ```

6.  **Jalankan Aplikasi**
    Buka dua terminal terpisah:
    
    *Terminal 1 (Frontend Build):*
    ```bash
    npm run dev
    ```
    
    *Terminal 2 (Laravel Server):*
    ```bash
    php artisan serve
    ```

Akses aplikasi di: `http://127.0.0.1:8000`

## 🧪 Pengujian (Testing)

Proyek ini dilengkapi dengan Feature Test dasar untuk memastikan halaman utama dapat diakses.

Jalankan test dengan perintah:
```bash
php artisan test
```

## 📁 Struktur Folder Penting

```
app/
├── Http/Controllers/   # Logika aplikasi (Movie, Auth, Profile)
├── Http/Middleware/    # Middleware (SetLocale)
├── Models/             # Eloquent Models (User, Watchlist)
├── Services/           # External API Logic (TmdbService)
└── View/Components/    # Blade Components Logic

resources/
├── views/
│   ├── components/     # Reusable UI Components
│   ├── layouts/        # Main App Layouts & Partials
│   ├── movies/         # Movie Pages
│   ├── auth/           # Authentication Pages
│   └── profile/        # User Profile Pages
└── lang/               # Localization Files (en/id)
```

## 📝 Lisensi

Proyek ini adalah perangkat lunak open-source di bawah lisensi [MIT](https://opensource.org/licenses/MIT).