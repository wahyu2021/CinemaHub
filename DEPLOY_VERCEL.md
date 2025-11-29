# 🚀 Panduan Deploy ke Vercel (Laravel)

Berikut langkah-langkah untuk menghosting proyek ini secara GRATIS di Vercel.

## 1. Persiapan Database (Wajib)
Vercel **hanya** menghosting kode, tidak menyediakan database. Anda perlu database MySQL/PostgreSQL eksternal gratis.

**Rekomendasi Database Gratis:**
1.  **[Neon.tech](https://neon.tech)** (PostgreSQL - Sangat Cepat & Mudah)
2.  **[TiDB Cloud](https://tidbcloud.com/)** (MySQL Compatible)
3.  **[Supabase](https://supabase.com/)** (PostgreSQL)

*Contoh jika pakai Neon/Supabase (PostgreSQL):*
Pastikan di `config/database.php` Laravel Anda sudah siap handle pgsql (default Laravel sudah support).

## 2. Install Vercel CLI (Opsional tapi Disarankan)
Jika ingin deploy dari terminal:
```bash
npm install -g vercel
```

## 3. Langkah Deploy

### Cara A: Via GitHub (Paling Mudah - Otomatis)
1.  **Push** kode Anda ke repository GitHub.
2.  Buka dashboard [Vercel](https://vercel.com).
3.  Klik **"Add New..."** -> **"Project"**.
4.  Pilih repository GitHub Anda.
5.  **Environment Variables:**
    Masukkan variabel dari `.env` Anda ke setting Vercel (Settings -> Environment Variables):
    *   `APP_KEY`: (Copy dari .env lokal)
    *   `APP_DEBUG`: `true` (atau `false` kalau sudah yakin aman)
    *   `TMDB_API_KEY`: (Copy API Key Anda)
    *   `TMDB_BASE_URL`: `https://api.themoviedb.org/3`
    *   `TMDB_IMAGE_BASE_URL`: `https://image.tmdb.org/t/p`
    *   **Database Config** (Sesuaikan dengan provider DB Anda):
        *   `DB_CONNECTION`: `pgsql` (atau `mysql`)
        *   `DB_HOST`: `...`
        *   `DB_PORT`: `5432` (atau `3306`)
        *   `DB_DATABASE`: `...`
        *   `DB_USERNAME`: `...`
        *   `DB_PASSWORD`: `...`
6.  Klik **Deploy**.

### Cara B: Via Terminal (Manual)
1.  Jalankan perintah:
    ```bash
    vercel
    ```
2.  Ikuti instruksi di layar (Login -> Pilih Project -> Yes to all).
3.  Untuk setting Environment Variable via terminal agak ribet, lebih mudah setting di Dashboard Vercel setelah project terbuat.

## 4. Setelah Deploy (PENTING!)

### Migrasi Database
Karena kita tidak punya akses SSH ke server Vercel, kita tidak bisa menjalankan `php artisan migrate` secara langsung di sana.

**Solusi:**
Jalankan migrasi dari komputer lokal Anda, tapi arahkan koneksi ke database production (cloud).

1.  Edit sementara file `.env` di lokal Anda.
2.  Ganti `DB_HOST`, `DB_PASSWORD`, dll dengan kredensial database cloud (Neon/TiDB/dll).
3.  Jalankan:
    ```bash
    php artisan migrate --force
    ```
4.  Kembalikan file `.env` ke setting database lokal.

### Aset (CSS/JS) Hilang?
Jika tampilan web di Vercel berantakan (CSS hilang), itu karena file `public/build` tidak terupload atau path-nya salah.

1.  Jalankan `npm run build` di lokal.
2.  Pastikan folder `public/build` ada.
3.  Pastikan file `public/build` **TIDAK** masuk `.gitignore`. (Hapus baris `/public/build` di `.gitignore` jika ada).
4.  Commit dan Push lagi.

---

## ⚠️ Keterbatasan Vercel untuk Laravel
*   **Storage Sementara**: Jangan gunakan `Storage::disk('public')` untuk menyimpan upload user (avatar, dll) karena akan hilang setelah beberapa saat. Gunakan layanan seperti AWS S3 atau Cloudinary jika butuh upload file.
*   **Timeout**: Request maksimal 10-60 detik (tergantung plan). Proses berat harus pakai Queue (yang butuh worker terpisah).
