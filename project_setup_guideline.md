# Buku Panduan Memulai Proyek: Laravel + Supabase + Vercel
*Panduan ini dibuat agar Anda memiliki alur kerja (workflow) terencana sejak menit pertama proyek Anda dibuat.*

---

## FASE 1: Inisiasi Proyek & Version Control (Git) 
Jangan pernah mulai menulis kode bisnis sebelum fondasinya diamankan di GitHub. Vercel akan bergantung sepenuhnya pada repositori ini.

1. **Buat Aplikasi Laravel Baru**
   ```bash
   composer create-project laravel/laravel nama-proyek
   cd nama-proyek
   ```

2. **Segera Daftarkan ke Git!**
   Jadikan ini sebagai insting pertama Anda:
   ```bash
   git init
   git add .
   git commit -m "Initial Laravel Setup"
   ```

3. **Sambungkan ke GitHub**
   - Buat Repositori kosong di GitHub (Tanpa README/License awal).
   - *Push* kerangka proyek ini ke GitHub:
   ```bash
   git branch -M main
   git remote add origin https://github.com/USERNAME_ANDA/nama-proyek.git
   git push -u origin main
   ```

---

## FASE 2: Pengaturan Database di Awal (Supabase)
Tinggalkan MySQL Lokal seperti XAMPP jika Anda memang membidik agar aplikasi ini dilepas ke eksterior (*Cloud*).

1. **Buat Database di Supabase**
   - Masuk ke dasbor Supabase -> **New Project**.
   - Salin Password yang Anda buat.
   - Buka pengaturan **Database Settings** -> **Connection String** -> Pilih tab **URI / PDO**.
   - **PENTING**: Centang kotak **"Use Connection Pooling" (Port 6543)** jika Anda bekerja dari jaringan Wi-Fi/Lokal biasa untuk menghindari konflik IPv4.

2. **Atur `.env` Laravel Anda Sejak Awal**
   Hapus konfigurasi `DB_CONNECTION=sqlite`/`mysql`, lalu gantikan dengan:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-0-REGION.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.kunciunik
   DB_PASSWORD=PasswordSupabaseAnda
   ```

3. **Pastikan Komputer Anda Paham PostgreSQL**
   Jika Anda di Windows, buka `C:\xampp\php\php.ini`, hapus tanda titik koma (`;`) di depan `extension=pdo_pgsql` dan `extension=pgsql`.

4. **Uji Coba Sinkronisasi**
   ```bash
   php artisan migrate
   ```
   *Jika berhasil, tabel-tabel bawaan Laravel akan langsung mendarat di Supabase!*

---

## FASE 3: Penyiapan Infrastruktur Vercel (Sebelum Ngoding Frontend)
Pasang sabuk pengaman lingkungan Vercel sejak hari pertama agar Anda bisa sering men-*deploy* dan menguji aplikasi secara langsung setiap saat.

1. **Buat File Konfigurasi Vercel (`vercel.json`)**
   Sama persis seperti yang kita buat, pastikan Region Vercel ("sin1") searah dengan letak Region Supabase Anda:
   ```json
   {
       "version": 2,
       "regions": ["sin1"],
       "builds": [
           { "src": "api/index.php", "use": "vercel-php@0.9.0" },
           { "src": "public/**", "use": "@vercel/static" }
       ],
       "routes": [
           { "src": "^/build/(.*)$", "dest": "/public/build/$1" },
           { "src": "/(css|js|images|fonts|svg)/(.*)", "dest": "/public/$1/$2" },
           { "src": "^/(.*)$", "dest": "/api/index.php" }
       ]
   }
   ```

2. **Buat Jembatan Serverless (`api/index.php`)**
   Buat folder `api/` secara manual dan isi dengan baris pencegah error *Read-Only Vercel* ini:
   ```php
   <?php
   // Buat direktori /tmp khusus Vercel
   $tmpStorage = '/tmp/storage';
   $tmpCache = '/tmp/bootstrap/cache';

   if (!is_dir($tmpStorage)) {
       mkdir($tmpStorage, 0755, true);
       mkdir($tmpStorage . '/framework/cache', 0755, true);
       mkdir($tmpStorage . '/framework/views', 0755, true);
       mkdir($tmpStorage . '/framework/sessions', 0755, true);
       mkdir($tmpStorage . '/logs', 0755, true);
   }
   if (!is_dir($tmpCache)) { mkdir($tmpCache, 0755, true); }

   // Pindahkan beban file internal Laravel
   $_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
   $_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
   $_ENV['APP_CONFIG_CACHE'] = $tmpCache . '/config.php';
   $_ENV['APP_ROUTES_CACHE'] = $tmpCache . '/routes.php';
   $_ENV['APP_EVENTS_CACHE'] = $tmpCache . '/events.php';

   require __DIR__ . '/../public/index.php';
   ```

3. **Modifikasi Inti (`bootstrap/app.php`)**
   Tambahkan penerimaan Proksi dan paksa storage pindah ke `/tmp` saat berada di atas awan Vercel:
   ```php
       ->withMiddleware(function (Middleware $middleware): void {
           $middleware->trustProxies(at: '*');
       })

   // Tempatkan di bawahnya, sebelum return $app;
   if (isset($_ENV['VERCEL']) || env('VERCEL', false)) {
       $app->useStoragePath('/tmp/storage');
   }
   ```

---

## FASE 4: Deployment & Variabel Lingkungan
*Push* aplikasi siap tempur ini kembali ke GitHub.

1. **Sambungkan GitHub ke Vercel**
   - Di dasbor Vercel, klik *Import Project* dari repo GitHub Anda.
   - Jangan buru-buru menekan *"Deploy"*. Buka tab **Environment Variables**.

2. **Setorkan Rahasia Anda**
   Masukkan seluruh isi rahasia aplikasi Anda ke Vercel:
   ```env
   # Ganti nilai Key / URL sesuai project Anda
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:xxx...
   APP_URL=https://proyek-anda.vercel.app

   VERCEL=true
   LOG_CHANNEL=stderr

   DB_CONNECTION=pgsql
   # (Masukkan DB_HOST s/d DB_PASSWORD yang sama)

   SESSION_DRIVER=cookie
   CACHE_STORE=database
   ```
3. Tekan **Deploy!**

---

**Selesai!** 🚀
Mulai dari detik ini, aplikasi Anda yang masih kosong namun memiliki struktur level "Dewa" ini:
- Sudah berkolaborasi dengan database yang bisa diakses global.
- Sangat aman dan tak memakan beban penyimpanan berkat *Cookie Session*.
- Akan otomatis diperbarui secara *live* setiap kali Anda menekan `git push` saat membuat fitur-fitur seru baru sambil berjalan.
