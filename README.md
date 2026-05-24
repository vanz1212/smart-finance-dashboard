# SMART FINANCE ANALYTICS DASHBOARD

Project ini dirapikan menjadi aplikasi Laravel dengan struktur dan pengaturan PostgreSQL.

## Struktur Project Utama
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/`
- `resources/`
- `routes/`
- `storage/`

## Fitur yang sudah disiapkan
- Halaman landing dengan pilihan menuju Smart Finance Dashboard atau Stata-like Analysis
- Routing Laravel untuk 3 halaman: `/`, `/smart-finance`, `/stata`
- Template Blade dengan layout bersih dan responsive
- Konfigurasi dasar PostgreSQL di `.env.example`

## Langkah persiapan
1. Pastikan PHP 8.1+, Composer, dan PostgreSQL sudah terpasang.
2. Jalankan perintah di folder project:

```powershell
composer install
```

3. Salin konfigurasi lingkungan:

```powershell
copy .env.example .env
```

4. Buat APP_KEY:

```powershell
php artisan key:generate
```

5. Sesuaikan koneksi PostgreSQL pada file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=smart_finance
DB_USERNAME=postgres
DB_PASSWORD=secret
```

6. Jalankan server lokal:

```powershell
php artisan serve
```

7. Buka browser:

```
http://127.0.0.1:8000
```

## Catatan
- Jika Anda belum memiliki skeleton Laravel sepenuhnya, jalankan `composer install` terlebih dahulu.
- File `public/css/app.css` sudah disiapkan dengan UI modern.
- Folder `data/` dan `report/` tetap dipertahankan sebagai tempat penyimpanan dataset dan output.
