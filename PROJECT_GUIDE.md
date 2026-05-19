# 🚀 Project Blueprint & Golden Prompt: Enterprise Accounting System (SISPENTRA Style)

Gunakan dokumen ini sebagai **"Master Prompt"** atau **"Blueprint Teknis"** jika Anda ingin membangun aplikasi serupa di masa depan dengan standar kualitas yang sama.

---

## 📝 The Golden Prompt (Copy-Paste)

> *"Bangunkan saya aplikasi berbasis web menggunakan **Laravel 11** dengan arsitektur **TALL Stack Lite** (Blade + Tailwind CSS + Alpine.js). Aplikasi ini harus memiliki standar estetika **Premium Enterprise Dashboard** dengan fitur: [Sebutkan Fitur Utama Anda]. Gunakan sistem **RBAC (Admin/Viewer)**, database **PostgreSQL/MySQL**, dan dukung **Dark Mode**. UI harus menggunakan **Clean Card Layout**, navigasi yang logis sesuai alur kerja bisnis, serta fitur export ke **Excel** dan **PDF (Vouchers)**. Pastikan UX terasa sangat responsif dengan **micro-animations** dari Alpine.js."*

---

## 🛠️ Technical Specifications

### 1. Core Stack
*   **Framework**: Laravel 11 (Latest Stable)
*   **Frontend**: Tailwind CSS (Styling) + Alpine.js (Interactivity)
*   **Templating**: Blade Components (Modular & Reusable)
*   **Database**: PostgreSQL (Recommended for production/Vercel)
*   **Auth**: Laravel Breeze (Clean starting point)

### 2. UI/UX Design System (The "Premium" Look)
Untuk mendapatkan tampilan seperti SISPENTRA, instruksikan AI untuk mengikuti panduan ini:
*   **Color Palette**: Gunakan `Slate` sebagai warna dasar (900 untuk dark, 50 untuk light) dan `Primary-600` (seperti Indigo atau Emerald) sebagai aksen.
*   **Layout**: 
    *   `Max-w-7xl` untuk container utama.
    *   **Page Header**: Judul besar (`text-3xl font-extrabold`) di kiri, tombol aksi/filter utama di kanan.
    *   **Filters Section**: Gunakan *card* putih/gelap (`rounded-2xl border`) dengan sistem **6-column grid** (`xl:grid-cols-6`) agar konsisten antar halaman.
    *   **Tables**: `divide-y`, `hover:bg-slate-50`, `whitespace-nowrap` untuk data finansial.
*   **Visual Elements**: 
    *   `Rounded-2xl` untuk semua kartu utama.
    *   `Rounded-xl` untuk tombol dan input.
    *   `Shadow-sm` sebagai default, `shadow-md` saat hover.
    *   **Glassmorphism**: Gunakan `bg-opacity` dan `backdrop-blur` pada elemen navigasi atau modal.

### 3. Security & Access Control
*   **Roles**: Minimal 2 Role (`admin` untuk manipulasi data, `viewer` untuk hanya baca).
*   **Middleware**: Buat `EnsureUserIsAdmin` untuk memproteksi rute POST/PATCH/DELETE.
*   **Protection**: Sembunyikan tombol "Tambah/Edit/Hapus" secara kondisional di Blade menggunakan `@if(auth()->user()->isAdmin())`.

### 4. Database Architecture (Accounting Standards)
*   **Chronological Logging**: Semua transaksi masuk ke tabel `journals`.
*   **Ledger Logic**: Data dihitung secara *real-time* atau via *cached query* untuk Buku Besar.
*   **Normalization**: Pisahkan data Master (Accounts, PIC) dari data transaksi.

---

## 🚀 Deployment Strategy
*   **Platform**: Vercel (untuk skalabilitas serverless) atau VPS (DigitalOcean/Linode).
*   **CI/CD**: Gunakan GitHub Actions untuk auto-deploy ke Vercel.
*   **Environment**: Pastikan `APP_ENV=production`, `APP_DEBUG=false`, dan `SESSION_DRIVER=database` (untuk stabilitas di serverless).

---

## 📖 Operational Guide (Manual)
*   **Input Data**: Selalu lakukan validasi di sisi server (Laravel Request) dan sisi klien (Alpine.js/HTML5).
*   **Navigation Flow**: Dashboard -> Daily Transactions -> Journals -> Detail Reports -> Settings.
*   **Consistency**: Jika satu halaman menggunakan tombol di kanan atas, pastikan SEMUA halaman laporan mengikuti pola yang sama.

---

## 💡 Prompting Tips for Better Code:
1.  **Be Specific about Height**: Selalu minta tinggi yang presisi untuk elemen sejajar (contoh: `h-[42px]` untuk input dan tombol cari).
2.  **Ask for Clean Diffs**: Minta AI untuk mengganti blok kode secara spesifik agar tidak merusak logika yang sudah ada.
3.  **Logical Reordering**: Instruksikan AI untuk mengurutkan menu navigasi berdasarkan alur kerja pengguna (UX-first approach).

---
*Created for: SISPENTRA Development Standards*
