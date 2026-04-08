# Panduan Deployment Laravel 11 ke Vercel + Supabase

Dokumen ini berisi rangkuman langkah dan pengaturan mutlak yang dibutuhkan jika Anda ingin men-*deploy* aplikasi Laravel 11 ke Vercel dengan database PostgreSQL di Supabase. Karena sistem Vercel bersifat *Serverless / Read-Only*, konfigurasi *default* Laravel akan *crash* tanpa modifikasi di bawah ini.

## 1. File Wajib: `vercel.json`
Simpan file ini di direktori paling luar (sejajar dengan `composer.json`). Pastikan menempatkan aplikasi di titik region yang sejalan dengan database Anda (misal `sin1` untuk Singapura).
```json
{
    "version": 2,
    "regions": ["sin1"],
    "builds": [
        {
            "src": "api/index.php",
            "use": "vercel-php@0.9.0"
        },
        {
            "src": "public/**",
            "use": "@vercel/static"
        }
    ],
    "routes": [
        {
            "src": "^/build/(.*)$",
            "dest": "/public/build/$1"
        },
        {
            "src": "/(css|js|images|fonts|svg)/(.*)",
            "dest": "/public/$1/$2"
        },
        {
            "src": "^/(.*)$",
            "dest": "/api/index.php"
        }
    ]
}
```

## 2. File Pintu Masuk Serverless: `api/index.php`
Ini adalah jembatan dari Vercel ke aplikasi Laravel yang memastikan seluruh *Path Cache* diarahkan ke folder `/tmp` (yang merupakan satu-satunya folder *Writeable* di Serverless Vercel). Masukkan di dalam folder bernama `api/`.
```php
<?php

// Buat direktori esensial di dalam folder `/tmp`
$tmpStorage = '/tmp/storage';
$tmpCache = '/tmp/bootstrap/cache';

if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0755, true);
    mkdir($tmpStorage . '/framework/cache', 0755, true);
    mkdir($tmpStorage . '/framework/views', 0755, true);
    mkdir($tmpStorage . '/framework/sessions', 0755, true);
    mkdir($tmpStorage . '/logs', 0755, true);
}

if (!is_dir($tmpCache)) {
    mkdir($tmpCache, 0755, true);
}

// Timpa titik rekam Cache otomatis milik Laravel agar aman
$_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $tmpCache . '/config.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpCache . '/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpCache . '/events.php';

// Serahkan sisa perjalanan ke inti utama Laravel
require __DIR__ . '/../public/index.php';
```

## 3. Override Storage Path & Secure HTTPS
Pada file `bootstrap/app.php`, Anda perlu memastikan Storage asli berpindah mengikuti *environment*, serta menyuruh Laravel mempercayai HTTPS dari Vercel agar koneksi Form Submit dan Loading Image tetap aman.
```php
// Pada blok return Application::configure:
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    // ...

// Tepat sebelum baris return $app di bawah, sisipkan:
if (isset($_ENV['VERCEL']) || env('VERCEL', false)) {
    $app->useStoragePath('/tmp/storage');
}
return $app;
```

## 4. Pengaturan Variabel Vercel (.env)
Buka Dashboard Vercel > Settings Project > Environment Variables dan wajib memasukkan hal-hal ini:
```env
APP_ENV=production
APP_DEBUG=false
# APP_KEY harus selalu anda pasangkan

VERCEL=true
LOG_CHANNEL=stderr

# Supabase (Wajib gunakan Session Pooler agar ramah port/IPv4)
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres

# Manajemen Memori File System
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/

CACHE_STORE=database
VIEW_COMPILED_PATH=/tmp/storage/framework/views
```

---
**Catatan Teknis:**
* `view [does not exist]` error sering timbul apabila `bootstrap/cache` tidak diarahkan ke `/tmp`.
* Kecepatan koneksi antara Vercel dan Supabase selalu ditentukan oleh keselarasan Region. Jika lambat, cek Region App dan Region DB.
