# 🕌 Musabaqah Tarikh Islam — Platform CBT Olimpiade Sejarah Islam Nasional

<div align="center">

![Status](https://img.shields.io/badge/Status-Production_Ready-brightgreen?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3+-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.x-orange?style=for-the-badge&logo=mysql)

**Platform kompetisi sejarah Islam nasional berbasis Computer Based Test (CBT) dengan arsitektur premium, sistem anti-cheat, bagan turnamen real-time, dan UI/UX tingkat enterprise.**

*Dikembangkan oleh [HVM Digital](https://hvmdigital.id) untuk Himpunan Mahasiswa Sejarah Peradaban Islam UINSA Surabaya.*

</div>

---

## 📖 Daftar Isi

- [Tentang Platform](#-tentang-platform)
- [Fitur Lengkap](#-fitur-lengkap)
- [Tech Stack](#️-tech-stack--architecture)
- [Struktur Peran (Role)](#-struktur-peran-role)
- [Instalasi Lokal](#-instalasi-lokal-development)
- [Deploy ke Hostinger](#-deploy-ke-production-hostinger)
- [Struktur Database](#-struktur-database-utama)
- [Konfigurasi Lanjutan](#-konfigurasi-lanjutan)
- [Developer](#-developed-by)

---

## 🌟 Tentang Platform

**Musabaqah Tarikh Islam (MTI)** adalah platform manajemen kompetisi ilmiah berbasis web yang dirancang untuk menyelenggarakan olimpiade sejarah peradaban Islam skala nasional. Sistem ini menggabungkan:

- **CBT (Computer Based Test)** — ujian online yang aman, stabil, dan anti-cheat
- **Sistem Turnamen Berjenjang** — penyisihan → semifinal → grand final
- **Manajemen Event Lengkap** — dari pembuatan soal hingga distribusi sertifikat
- **Leaderboard Real-time** — klasemen bergerak yang diperbarui otomatis setiap 30 detik
- **UI Premium** — desain glassmorphism dengan gradasi hijau cerah (#1db349 → #a5cf36)

---

## 🚀 Fitur Lengkap

### 🌐 Landing Page & Web Publik

| Fitur | Deskripsi |
|-------|-----------|
| **Hero Section Dinamis** | Teks sambutan, statistik, dan CTA yang dapat diedit penyelenggara tanpa coding |
| **Marquee Logo Partner** | Slider logo otomatis institusi pendukung & sponsor |
| **Instagram Feed Sync** | Galeri postingan Instagram yang bisa diatur manual |
| **Public Leaderboard** | Arsip klasemen juara antar event, tampilan podium premium |
| **Public Bagan Turnamen** | Bagan turnamen publik dengan Champion Banner hijau + animasi |
| **SEO Optimized** | Meta tag, OG tag, sitemap-friendly |
| **Auto WebP Compression** | Setiap gambar upload dikonversi & dikompres otomatis ke WebP |

### 🏛️ Dashboard Penyelenggara (Organizer)

#### Event & Babak
| Fitur | Deskripsi |
|-------|-----------|
| **Buat Event Multi-Babak** | Satu event dapat memiliki banyak babak bertingkat |
| **2 Sistem Kompetisi** | **Poin** (klasemen akumulatif) atau **Kualifikasi** (eliminasi per babak) |
| **Status Event Lengkap** | Draft → Published → Ongoing → Completed |
| **Poster Event 4:5** | Upload poster dengan rasio potret premium, tampil di grid 4 card/baris |
| **Setup Babak Wizard** | Wizard otomatis membuat jenjang babak (300 → 150 → 75 peserta) |
| **Jadwal Otomatis** | Waktu mulai/selesai per babak, status berubah real-time sesuai jam server |

#### Bank Soal & Penilaian
| Fitur | Deskripsi |
|-------|-----------|
| **Soal Pilihan Ganda** | Sistem bobot dinamis: Easy (+2), Medium (+3), Hard (+4), Salah (-1), Kosong (0) |
| **Soal Esai** | Penilaian manual oleh juri, score 0–100 per soal |
| **Import Soal Excel** | Bulk import ratusan soal sekaligus via template XLSX |
| **Auto-Grading PG** | Pilihan ganda dihitung otomatis saat peserta submit |
| **Grading Interface Esai** | UI slider premium untuk juri menilai esai satu per satu |
| **Auto-Advance setelah Grading** | Setelah semua esai dinilai, sistem otomatis memperbarui status peserta |

#### Manajemen Peserta
| Fitur | Deskripsi |
|-------|-----------|
| **Bulk Import Excel** | Import ribuan peserta sekaligus via XLSX template |
| **Auto Generate Kode Akses** | Kode unik format `MTI-XXXXXXXX` per peserta, terenkripsi |
| **Export Access List** | Download daftar peserta + kode akses siap dibagikan |
| **ID Card Modal Premium** | Klik nama peserta untuk melihat profil lengkap bergaya kartu |
| **Inline Edit Kode** | Ubah kode akses peserta langsung dari tabel tanpa reload |
| **Tampilan Card Grid 4:5** | Daftar peserta dan event ditampilkan dalam grid poster 4 card/baris |

#### Sistem Kualifikasi (Bagan Turnamen)
| Fitur | Deskripsi |
|-------|-----------|
| **Bagan Turnamen Visual** | Visualisasi bracket multi-babak yang elegan |
| **Proses Lolos Otomatis** | Input kuota → sistem loloskan top-N peserta ke babak selanjutnya |
| **Status Sementara** | Saat babak berlangsung, peserta unggul ditandai "sementara lolos" |
| **Auto-Finalize** | Saat waktu babak habis, status "sementara" berubah jadi "lolos" atau "gugur" |
| **Champion Detection** | Peserta skor tertinggi di babak final otomatis diberi label Juara |
| **Public Bracket Premium** | Bagan publik dengan dark green background, connector lines, & Champion Banner |

#### E-Sertifikat
| Fitur | Deskripsi |
|-------|-----------|
| **Upload Desain Sertifikat** | Upload template sertifikat kosong (PNG/JPG) |
| **Coordinate Picker** | Tentukan posisi nama & nomor register via antarmuka visual |
| **Generate & Kirim Massal** | Sistem buat sertifikat PDF personal dan kirim ke dashboard peserta |
| **Riwayat Pengiriman** | Rekap semua sertifikat yang sudah terkirim per event |

#### Statistik & Laporan
| Fitur | Deskripsi |
|-------|-----------|
| **Statistik per Event** | Grafik distribusi skor, rata-rata, peserta aktif |
| **Laporan Pelanggaran** | Rekap peserta yang terdeteksi melanggar (pindah tab, copy-paste) |
| **Leaderboard Organizer** | Tampilan klasemen internal untuk monitoring panitia |

#### Kelola Website (No-Code)
| Fitur | Deskripsi |
|-------|-----------|
| **Foto Slideshow** | Kelola foto dokumentasi yang tampil sebagai marquee di homepage |
| **Teks Hero (Live Preview)** | Edit teks sambutan beranda dengan preview langsung |
| **Partner & Sponsor** | Upload logo mitra, tampil marquee otomatis |
| **Instagram Feeds** | Atur galeri postingan IG yang tampil di section khusus |
| **Footer & Kontak** | Edit alamat, WhatsApp, email, TikTok, YouTube |
| **Kendala Peserta (Ticketing)** | Sistem tiket bantuan — peserta ajukan masalah, panitia respons |

---

### 👤 Dashboard Peserta (CBT System)

| Fitur | Deskripsi |
|-------|-----------|
| **Login Kode Akses** | Masuk cukup dengan username + kode akses, tanpa registrasi |
| **Status Dashboard Real-time** | Tampilan berubah otomatis: Menunggu → Berlangsung → Selesai |
| **Banner Lolos Premium** | Peserta yang lolos babak berikutnya melihat banner "Selamat! Anda Lolos ke [Nama Babak]" |
| **Banner Eliminasi** | Peserta gugur menerima pesan semangat yang hangat |
| **Mulai Ujian + Briefing** | Pop-up konfirmasi premium berisi penjelasan sistem anti-cheat & aturan |
| **Navigasi Nomor Soal** | Sidebar nomor soal berwarna berbeda: abu (belum) vs hijau (sudah dijawab) |
| **Auto-save Jawaban** | Jawaban tersimpan otomatis setiap dipilih, indikator "✓ Tersimpan" |
| **State Recovery** | Refresh halaman? Timer & jawaban tetap utuh, tidak ada yang hilang |
| **Konfirmasi Submit Premium** | Modal premium bergaya brand, warning merah jika masih ada soal kosong |
| **Timer Server-Side** | Jam hitung mundur berbasis server, tidak bisa dimanipulasi |
| **Anti-Cheat 5 Pelanggaran** | Batas 5 pelanggaran: pindah tab, layar penuh ditutup, copy-paste, klik kanan → auto-submit |
| **Leaderboard Live** | Peringkat peserta yang diperbarui real-time (untuk sistem poin) |
| **Klaim E-Sertifikat** | Download sertifikat PDF personal yang sudah tercetak nama resmi |
| **Hubungi Panitia** | FAB (Floating Action Button) untuk buka tiket bantuan ke panitia |

---

## 🛠️ Tech Stack & Architecture

### Backend
| Komponen | Teknologi |
|----------|-----------|
| **Framework** | Laravel 11.x |
| **Language** | PHP 8.3+ |
| **Database** | MySQL 8.x / MariaDB |
| **ORM** | Eloquent (Eager Loading, Strict Types, Casts) |
| **Auth** | Laravel Session + Custom Access Code Guard |
| **Queue** | Database Queue (untuk job async) |
| **Storage** | Local Disk + Storage::link() untuk public assets |

### Frontend
| Komponen | Teknologi |
|----------|-----------|
| **Templating** | Laravel Blade Components |
| **CSS** | Vanilla CSS3 — Custom Properties, Glassmorphism, Keyframe Animations |
| **JavaScript** | Vanilla ES6+ — Fetch API, localStorage, Real-time polling |
| **Icons** | FontAwesome 6 (Solid, Regular, Brands) |
| **Fonts** | Google Fonts — Montserrat, Manrope, Inter |
| **Color Brand** | Gradasi `#1db349` → `#a5cf36` (hijau cerah ke lime) |

### Packages
| Package | Fungsi |
|---------|--------|
| `maatwebsite/excel` | Import/Export peserta & soal via XLSX |
| `intervention/image` | Auto-compress & konversi ke WebP |
| `barryvdh/laravel-dompdf` | Generate sertifikat PDF |
| `Carbon` | Manipulasi waktu & timezone Asia/Jakarta |

---

## 👥 Struktur Peran (Role)

```
┌─────────────────────────────────────────────────────────────┐
│                    MUSABAQAH PLATFORM                       │
├──────────────┬──────────────────┬───────────────────────────┤
│   PUBLIK     │  PENYELENGGARA   │        PESERTA            │
│              │   /organizer/*   │       /peserta/*          │
├──────────────┼──────────────────┼───────────────────────────┤
│ /leaderboard │ Kelola Event     │ Dashboard status          │
│ /            │ Kelola Babak     │ Mulai & kerjakan ujian    │
│ /artikel     │ Bank Soal        │ Lihat skor & peringkat    │
│              │ Import Peserta   │ Klaim sertifikat          │
│              │ Nilai Esai       │ Ajukan tiket bantuan      │
│              │ E-Sertifikat     │                           │
│              │ Kelola Website   │                           │
└──────────────┴──────────────────┴───────────────────────────┘
```

---

## 💻 Instalasi Lokal (Development)

### Prasyarat
- PHP 8.2+ (disarankan 8.3)
- Composer 2.x
- MySQL 8.x / MariaDB
- Node.js 18+ (hanya untuk build jika ada asset Vite)
- Laragon / XAMPP / Laravel Herd

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/[org]/surabayahype.git
cd surabayahype

# 2. Install PHP dependencies
composer install

# 3. Copy file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Setup database (buat database MySQL dulu)
# Edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Jalankan migrasi + seeder
php artisan migrate
# Opsional: php artisan db:seed

# 7. Buat storage symlink (untuk file upload)
php artisan storage:link

# 8. Jalankan server lokal
php artisan serve
```

### Akses Default
| Role | URL | Login |
|------|-----|-------|
| Penyelenggara | `http://localhost:8000/organizer` | Email + Password |
| Peserta | `http://localhost:8000/peserta` | Username + Kode Akses |
| Landing Page | `http://localhost:8000/` | Publik |

---

## 🚀 Deploy ke Production (Hostinger)

### File Siap Deploy
- **`database/hostinger_deploy.sql`** — Dump database lengkap (±916 KB)
- **`.env.hostinger.example`** — Template konfigurasi server

### Konfigurasi `.env` Wajib Diubah
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_DATABASE=nama_db_hostinger
DB_USERNAME=user_db_hostinger
DB_PASSWORD=password_db_hostinger
```

### Perintah Post-Deploy (via SSH/Terminal Hostinger)
```bash
php artisan migrate          # Jika ada migrasi baru
php artisan storage:link     # Buat symlink storage
php artisan config:cache     # Cache konfigurasi
php artisan route:cache      # Cache routing
php artisan view:cache       # Cache Blade views
php artisan optimize         # Optimasi keseluruhan
```

> Lihat panduan lengkap di file `DEPLOY_HOSTINGER.md`

---

## 🗄️ Struktur Database Utama

```
users               — Akun penyelenggara (email + password)
participants        — Peserta (access_code login, bracket tracking)
events              — Data event/kompetisi
rounds              — Babak dalam event (sequence, scoring config)
questions           — Bank soal (PG + Esai, difficulty, point)
options             — Pilihan jawaban soal PG
exam_sessions       — Sesi ujian peserta per babak
exam_questions      — Soal yang ditampilkan dalam sesi (shuffled)
answers             — Jawaban peserta (option_id / essay_answer)
certificates        — Link sertifikat per peserta
participant_tickets — Tiket bantuan peserta ke panitia
landing_images      — Foto slideshow beranda
landing_settings    — Konfigurasi footer, hero text, sosmed
```

---

## ⚙️ Konfigurasi Lanjutan

### Anti-Cheat Threshold
Di model `Round`, field `auto_submit_threshold` (default: **5**) menentukan berapa kali pelanggaran sebelum ujian peserta di-submit otomatis.

### Sistem Kualifikasi — Auto-Advance
Setelah waktu babak habis (`end_time`), sistem secara otomatis:
1. Mengubah status peserta "sementara lolos" → **lolos**
2. Mengubah yang tidak lolos kuota → **gugur/tereliminasi**
3. Membuka akses sesi ujian babak berikutnya bagi yang lolos

> Trigger: `tryAutoAdvance()` di `RoundService`, dipanggil saat:
> - Penilaian esai selesai (semua `score IS NOT NULL`)
> - Waktu babak terdeteksi habis (pengecekan saat akses dashboard)

### Bobot Soal Pilihan Ganda
```php
// config/exam.php (atau di ExamService)
'easy'   => ['correct' => 2, 'wrong' => -1, 'empty' => 0],
'medium' => ['correct' => 3, 'wrong' => -1, 'empty' => 0],
'hard'   => ['correct' => 4, 'wrong' => -1, 'empty' => 0],
```

### Storage & File Upload
```
storage/app/public/
├── posters/          — Poster event
├── avatars/          — Foto profil peserta
├── certificates/     — Template & file sertifikat
├── photos/           — Foto slideshow homepage
├── logos/            — Logo partner & sponsor
└── instagram/        — Foto Instagram feeds
```

---

## 📁 Struktur Direktori

```
surabayahype/
├── app/
│   ├── Http/Controllers/
│   │   ├── Organizer/         — Controller penyelenggara
│   │   └── Peserta/           — Controller peserta
│   ├── Models/                — Eloquent models
│   ├── Services/
│   │   ├── ExamService.php    — Logic ujian & scoring
│   │   └── RoundService.php   — Auto-advance & qualification
│   └── Jobs/                  — Background jobs
├── database/
│   ├── migrations/            — Skema database
│   └── hostinger_deploy.sql   — Dump database siap deploy
├── resources/views/
│   ├── layouts/               — Layout app, public, exam
│   ├── peserta/               — Views dashboard & ujian peserta
│   ├── organizer/             — Views dashboard penyelenggara
│   ├── leaderboard/           — Views leaderboard & bracket publik
│   └── components/            — Shared components (modal, etc)
├── routes/web.php             — Semua definisi route
├── .env.hostinger.example     — Template konfigurasi Hostinger
├── README.md                  — Dokumentasi teknis ini
└── READFLOW.md                — Panduan penggunaan penyelenggara
```

---

## 🔐 Keamanan

- **CSRF Protection** — Semua form dilindungi Laravel CSRF token
- **Session Guard** — Sesi terpisah untuk organizer & peserta
- **Anti-Cheat System** — 5 jenis deteksi pelanggaran + auto-submit
- **Access Code Encryption** — Kode akses peserta di-hash di database
- **Input Sanitization** — Semua input di-escape via Blade `{{ }}` & `e()`
- **APP_DEBUG=false** — Wajib di production agar error tidak terekspos

---

## 👨‍💻 Developed By

<div align="center">

### [HVM Digital](https://hvmdigital.id)
*Digital Agency & Web Development Specialist — Surabaya*

Platform CBT Musabaqah Tarikh Islam dirancang, diarsiteki, dan dibangun oleh tim HVM Digital dengan standar enterprise: keamanan tinggi, performa optimal, dan UI/UX premium yang membanggakan.

**Contact:** [hvmdigital.id](https://hvmdigital.id) | Surabaya, Indonesia

</div>

---

*Last updated: Juni 2026 | Laravel 11 | PHP 8.3 | MySQL 8*
