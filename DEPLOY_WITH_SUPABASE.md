# Panduan Deployment ke Vercel dengan Database Supabase

Dokumen ini menjelaskan cara mengonfigurasi proyek Laravel ini agar menggunakan **MySQL (XAMPP)** di lingkungan lokal dan **Supabase (PostgreSQL)** saat di-deploy ke **Vercel**.

## 1. Persiapan Database Supabase

1.  Buka [Supabase](https://supabase.com/) dan buat akun/login.
2.  Buat "New Project".
3.  Setelah project dibuat, masuk ke **Project Settings** -> **Database**.
4.  Di bagian **Connection string**, pilih tab **URI** atau **Node.js** (kita butuh parameter host, user, password, dll).
    *   Catat **Host** (biasanya `db.ref.supabase.co`).
    *   Catat **Port** (biasanya `5432` atau `6543`). Gunakan `5432` untuk koneksi langsung (Session mode) atau `6543` (Transaction mode) jika tersedia dan disarankan untuk serverless (Vercel).
    *   Catat **User** (biasanya `postgres`).
    *   Catat **Password** (password yang Anda buat saat membuat project).
    *   Catat **Database Name** (biasanya `postgres`).

## 2. Konfigurasi Environment Variables di Vercel

Saat men-deploy ke Vercel, jangan mengubah file `.env` secara langsung atau meng-commitnya. Gunakan fitur **Environment Variables** di dashboard Vercel.

1.  Masuk ke dashboard proyek Anda di Vercel.
2.  Pergi ke tab **Settings** -> **Environment Variables**.
3.  Tambahkan variable berikut (copy dari credentials Supabase Anda):

| Key | Value (Contoh) | Keterangan |
| :--- | :--- | :--- |
| `DB_CONNECTION` | `pgsql` | **Wajib** diubah ke `pgsql` untuk Supabase |
| `DB_HOST` | `aws-0-ap-southeast-1.pooler.supabase.com` | Host dari Supabase |
| `DB_PORT` | `6543` atau `5432` | Port Supabase |
| `DB_DATABASE` | `postgres` | Nama database |
| `DB_USERNAME` | `postgres.xxx` | Username database |
| `DB_PASSWORD` | `rahasia123` | Password database project Anda |
| `DB_SSLMODE` | `require` | Supabase membutuhkan SSL |

**Catatan Penting:**
*   Pastikan `DB_CONNECTION` di set ke `pgsql` di Vercel.
*   Di local (komputer Anda), biarkan `.env` tetap menggunakan `DB_CONNECTION=mysql` agar tetap jalan dengan XAMPP.

## 3. Menjalankan Migrasi Database (Production)

Karena Vercel adalah lingkungan serverless (runtime PHP), menjalankan command `php artisan migrate` secara langsung di server Vercel agak sulit/tidak persisten.

**Cara yang Disarankan:**
Jalankan migrasi dari komputer lokal Anda, tetapi arahkan koneksi ke database Supabase.

1.  Di komputer lokal Anda, buka file `.env` (sementara).
2.  Ubah konfigurasi database di `.env` lokal Anda agar mengarah ke Supabase (gunakan data dari Langkah 1).
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=db.your-project.supabase.co
    DB_PORT=5432
    DB_DATABASE=postgres
    DB_USERNAME=postgres
    DB_PASSWORD=your-password
    DB_SSLMODE=require
    ```
3.  Jalankan perintah migrate:
    ```bash
    php artisan migrate --force
    ```
4.  Setelah sukses, **kembalikan** pengaturan `.env` lokal Anda ke pengaturan MySQL (XAMPP) semula.

## 4. Troubleshooting

*   **Error "SSL connection is required":** Pastikan Anda sudah menambahkan Environment Variable `DB_SSLMODE` dengan nilai `require` di Vercel.
*   **Error "Connection timed out":** Cek apakah Host dan Port sudah benar. Supabase sering menggunakan port `6543` untuk pooler (lebih baik untuk serverless seperti Vercel) atau `5432` untuk koneksi langsung.
