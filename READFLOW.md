# 📋 READFLOW — Panduan Penyelenggara Musabaqah Tarikh Islam

> **Untuk siapa dokumen ini?**
> Panduan ini ditujukan khusus untuk **Penyelenggara / Panitia (Organizer)** yang mengelola event kompetisi melalui Dashboard Admin.
> Baca dari atas ke bawah — setiap tahap dilakukan **secara berurutan**.

---

## 🗺️ Gambaran Besar Alur Kerja

```
[1] Buat Event
      ↓
[2] Tambah Bank Soal & Import Soal
      ↓
[3] Import Peserta (Excel)
      ↓
[4] Buat Babak & Atur Jadwal
      ↓
[5] Publikasikan Event (Published → Ongoing)
      ↓
[6] Peserta Mengerjakan Ujian ← LIVE
      ↓
[7] Nilai Esai (jika ada soal esai)
      ↓
[8] Proses Hasil & Loloskan Peserta (Sistem Kualifikasi)
      ↓
[9] Kirim Sertifikat
      ↓
[10] Event Selesai ✅
```

---

## BAGIAN 1 — Memahami Sistem Event

Sebelum membuat event, pilih terlebih dahulu **format kompetisi** yang sesuai:

### 🏅 Sistem Poin (Klasemen / Leaderboard)
- Peserta mengumpulkan poin dari setiap babak ujian
- **Tidak ada eliminasi** — semua peserta ikut semua babak
- Pemenang = peserta dengan **total poin tertinggi** di akhir event
- Tampilan: **Tabel Klasemen** yang diperbarui real-time
- **Cocok untuk:** Tryout, lomba akademis terbuka, ujian paralel

### 🏆 Sistem Kualifikasi (Bagan Turnamen / Bracket)
- Peserta **disaring per babak** — hanya yang lolos bisa lanjut ke babak berikutnya
- Setiap babak ada **kuota lolos** (contoh: 300 → 150 → 50 → 10)
- Pemenang = peserta yang bertahan sampai babak final dengan skor tertinggi
- Tampilan: **Bagan Turnamen Visual** (bracket) dengan Champion Banner
- **Cocok untuk:** Olimpiade, cerdas cermat berjenjang, turnamen eliminasi

> ⚠️ **Penting:** Pilihan sistem ini **tidak bisa diubah** setelah event dibuat. Pertimbangkan dengan matang!

---

## BAGIAN 2 — Memulai: Buat Event Baru

### Langkah-langkah:

**1.** Buka sidebar → klik **Event & Babak**

**2.** Klik tombol hijau **"+ Buat Event Baru"**

**3.** Isi formulir event:
   - **Nama Event** — contoh: "Musabaqah Tarikh Islam 2025"
   - **Kategori** — contoh: "Olimpiade Nasional"
   - **Sistem Scoring** — pilih **Poin** atau **Kualifikasi**
   - **Tanggal Mulai & Selesai** — rentang event secara keseluruhan
   - **Poster Event** — upload gambar berformat potret (rasio 4:5, JPG/PNG)

**4.** Klik **Simpan Event**

> ✅ Event baru otomatis berstatus **Draft** — belum terlihat publik

---

## BAGIAN 3 — Buat Bank Soal & Input Soal

Bank soal adalah kumpulan pertanyaan yang akan digunakan dalam babak ujian.

### A. Buat Bank Soal Baru

**1.** Masuk ke halaman **Detail Event** (klik nama event)

**2.** Buka tab **Bank Soal** → klik **"+ Buat Bank Soal"**

**3.** Beri nama bank soal (contoh: "Soal Penyisihan 2025")

### B. Tambah Soal

**Cara 1 — Input Manual:**
1. Klik **"+ Tambah Soal"**
2. Pilih tipe: **Pilihan Ganda** atau **Esai**
3. Tulis pertanyaan dan (untuk PG) pilihan jawaban A–E
4. Tandai jawaban yang benar, pilih tingkat kesulitan (Easy/Medium/Hard)
5. Simpan

**Cara 2 — Import Excel (direkomendasikan untuk soal banyak):**
1. Download **Template Excel** dari halaman Bank Soal
2. Isi soal sesuai format template (ada kolom: soal, opsi A–E, kunci, difficulty)
3. Upload file → klik **Import**
4. Sistem akan memproses dan menampilkan hasilnya

> 💡 **Bobot Nilai Pilihan Ganda:**
> - Easy: Benar **+2**, Salah **-1**, Kosong **0**
> - Medium: Benar **+3**, Salah **-1**, Kosong **0**
> - Hard: Benar **+4**, Salah **-1**, Kosong **0**

---

## BAGIAN 4 — Import Peserta

### Cara Import:

**1.** Di halaman Detail Event → tab **Peserta**

**2.** Klik **"Download Template Excel"** untuk mendapatkan format yang benar

**3.** Isi template dengan kolom:
   - Nama Lengkap
   - Username (unik, dipakai untuk login)
   - Institusi/Sekolah
   - Kelas/Jurusan
   - Email (opsional)

**4.** Upload file template yang sudah diisi → klik **Import**

**5.** Sistem akan:
   - Membuat akun peserta
   - Men-generate **Kode Akses** unik (format `MTI-XXXXXXXX`) untuk setiap peserta
   - Menampilkan laporan hasil import (berhasil / gagal / duplikat)

**6.** Klik **"Export Daftar Akses"** untuk download daftar peserta + kode akses dalam format Excel — siap dibagikan ke peserta!

> 🔑 **Info Kode Akses:** Peserta login menggunakan **Username** (bukan email) + **Kode Akses** yang sudah digenerate. Jaga kerahasiaannya!

---

## BAGIAN 5 — Buat Babak (Round) & Atur Jadwal

### A. Untuk Sistem Poin

**1.** Di halaman Detail Event → tab **Babak** → klik **"+ Tambah Babak"**

**2.** Isi detail babak:
   - **Nama Babak** — contoh: "Penyisihan Hari 1"
   - **Urutan (Sequence)** — angka urutan babak (1, 2, 3...)
   - **Waktu Mulai & Selesai** — jadwal ujian babak ini
   - **Durasi Ujian** — berapa menit peserta mengerjakan soal
   - **Bank Soal** — pilih bank soal yang sudah dibuat
   - **Jumlah Soal** — berapa soal yang diambil acak dari bank soal
   - **Kuota Pelanggaran (Anti-Cheat)** — default 5x pelanggaran sebelum auto-submit

**3.** Simpan babak → ulangi untuk setiap babak

### B. Untuk Sistem Kualifikasi

**1.** Gunakan **Wizard Setup Turnamen** yang tersedia

**2.** Tentukan:
   - Jumlah babak (misal: 3 babak = Penyisihan + Semifinal + Final)
   - Jumlah peserta per babak (misal: 300 → 150 → 50)
   - Jadwal masing-masing babak

**3.** Sistem akan otomatis membuat struktur babak yang terhubung

> ⚠️ **Perhatikan jadwal:** Waktu babak menentukan kapan peserta bisa mulai mengerjakan ujian dan kapan sistem otomatis memproses hasil.

---

## BAGIAN 6 — Publikasikan Event

Setelah semua siap, ubah status event agar peserta bisa akses:

| Status | Kondisi | Peserta Bisa? |
|--------|---------|---------------|
| **Draft** | Masih persiapan | ❌ Tidak bisa akses |
| **Published** | Siap, tapi belum dimulai | ✅ Bisa login & lihat info |
| **Ongoing** | Event sedang berlangsung | ✅ Bisa mulai ujian |
| **Completed** | Event selesai | ✅ Bisa lihat hasil & sertifikat |

**Cara ubah status:**
- Di halaman Detail Event → klik tombol **"Ubah Status"** → pilih status baru

---

## BAGIAN 7 — Monitoring Ujian (Live)

Saat ujian berlangsung, pantau dari:

### Untuk Sistem Poin → Leaderboard
- Sidebar → **Leaderboard** → pilih event
- Klasemen diperbarui otomatis setiap **30 detik**
- Klik nama peserta untuk lihat **ID Card** profil lengkap mereka

### Untuk Sistem Kualifikasi → Bagan Turnamen
- Sidebar → **Leaderboard** → pilih event → Bagan Turnamen
- Peserta yang sedang mengerjakan tampil dengan skor real-time
- Label **"sementara"** menandakan posisi sementara berdasarkan skor saat ini

### Tiket Bantuan Peserta
- Sidebar → **Kendala Peserta**
- Peserta yang mengalami masalah teknis akan mengirim tiket ke sini
- Panitia bisa membalas langsung melalui sistem

---

## BAGIAN 8 — Penilaian Esai (Jika Ada)

Jika babak menggunakan soal esai, lakukan penilaian setelah babak selesai:

**1.** Masuk ke halaman **Detail Babak** (klik babak yang sudah selesai)

**2.** Klik tombol **"Nilai Esai"** (warna ungu/biru)

**3.** Anda masuk ke **Grading Interface**:
   - Kiri: Soal esai yang harus dinilai
   - Kanan: Jawaban peserta
   - Geser slider atau ketik angka untuk memberi nilai (0–100 per soal)
   - Klik **"Simpan & Lanjut"** ke peserta berikutnya

**4.** Setelah **semua peserta dinilai**, sistem otomatis:
   - Mengakumulasikan nilai esai ke total skor peserta
   - Memperbarui klasemen / bagan turnamen
   - Memproses status lolos/gugur (untuk sistem kualifikasi)

> ⚡ **Tips:** Gunakan fitur **"Filter Belum Dinilai"** untuk fokus pada jawaban yang masih pending.

---

## BAGIAN 9 — Loloskan Peserta (Khusus Sistem Kualifikasi)

Setelah babak selesai & esai sudah dinilai semua, proses siapa yang lolos:

**1.** Buka **Bagan Turnamen** → klik babak yang baru selesai

**2.** Klik tombol **"Proses Hasil Babak"**

**3.** Sistem menampilkan **Preview Peringkat** semua peserta di babak ini

**4.** Masukkan **Jumlah Kuota Lolos** (contoh: 50)
   - Top 50 → otomatis status **"Lolos"** ✅
   - Sisanya → otomatis status **"Gugur / Tereliminasi"** ❌

**5.** Klik **"Simpan & Loloskan"**

**Hasilnya:**
- Peserta yang lolos mendapatkan **notifikasi "Selamat! Anda Lolos ke [Nama Babak]"** di dashboard mereka
- Peserta gugur mendapat **pesan penyemangat** + ucapan terima kasih
- Bagan turnamen diperbarui otomatis menampilkan peserta lolos di kolom babak berikutnya
- Peserta lolos sudah bisa mulai mengerjakan ujian babak berikutnya sesuai jadwal

---

## BAGIAN 10 — Kirim Sertifikat

Setelah event selesai dan pemenang sudah ditentukan:

**1.** Sidebar → **E-Sertifikat**

**2.** Pilih event yang sudah selesai

**3.** Klik **"Upload Template Sertifikat"**:
   - Upload desain sertifikat kosong (JPG/PNG, resolusi tinggi)
   - Gunakan **Coordinate Picker** untuk menentukan posisi:
     - Nama peserta
     - Nomor peserta / kode akses
     - Tanggal

**4.** Klik **"Generate Massal"** — sistem akan membuat sertifikat PDF personal untuk semua peserta

**5.** Klik **"Kirim ke Peserta"** — sertifikat otomatis muncul di dashboard masing-masing peserta dan bisa mereka download

---

## BAGIAN 11 — Kelola Website (Tanpa Coding)

Semua elemen landing page dapat diedit langsung dari dashboard:

### 📸 Foto Slideshow
- Sidebar → **Foto Slideshow**
- Upload foto dokumentasi event
- Foto akan tampil sebagai slider otomatis (marquee) di halaman beranda

### ✍️ Teks Hero
- Sidebar → **Teks Hero**
- Edit judul besar, subjudul, dan teks CTA beranda
- Lihat perubahan langsung via **Live Preview** sebelum disimpan
- Edit juga 3 angka statistik (peserta, event, dll)

### 🤝 Partner & Sponsor
- Sidebar → **Partner & Sponsor**
- Upload logo mitra dan sponsor
- Logo tampil otomatis di beranda dengan efek marquee

### 📱 Instagram Feeds
- Sidebar → **Instagram Feeds**
- Tambahkan URL + thumbnail postingan Instagram
- Tampil di section "Ikuti Keseruannya" di beranda

### 🦶 Footer & Kontak
- Sidebar → **Footer & Kontak**
- Edit: deskripsi, alamat, nomor WhatsApp, email, TikTok, YouTube

---

## ⚡ Tips & Trik Penting

### Sebelum Hari-H
- [ ] Buat **Event Uji Coba** dan tambahkan akun Anda sendiri sebagai peserta
- [ ] Coba alur ujian dari sudut pandang peserta: login, kerjakan soal, submit
- [ ] Pastikan semua soal terbaca dengan baik (tidak ada yang error)
- [ ] Cek jadwal babak sudah benar (timezone Asia/Jakarta)
- [ ] Pastikan semua peserta sudah menerima kode akses mereka

### Saat Event Berlangsung
- Buka **Leaderboard / Bagan** di tab terpisah untuk monitoring
- Pantau **Kendala Peserta** secara berkala untuk tiket masuk
- Jika ada peserta yang kode aksesnya bermasalah → **Inline Edit Kode** langsung dari tabel peserta

### Fitur-fitur Tersembunyi yang Berguna
- **Klik nama peserta** di mana pun (leaderboard, bagan, tabel) → muncul **ID Card** profil lengkap
- **Bagan turnamen publik** bisa diakses siapa pun di `/leaderboard/[slug-event]` — bagikan ke peserta & penonton!
- **Champion Banner** otomatis muncul di bagan publik menampilkan pemenang dengan latar gradasi hijau brand
- Soal PG di**acak** per peserta (order berbeda) secara otomatis untuk mencegah contekan

---

## 🚨 Troubleshooting Umum

| Masalah | Solusi |
|---------|--------|
| Peserta tidak bisa login | Cek username & kode akses, pastikan persis sama (case-sensitive) |
| Status peserta tidak berubah setelah babak selesai | Cek apakah masih ada esai yang belum dinilai (wajib dinilai semua dulu) |
| Bagan tidak update | Tunggu 30 detik atau refresh manual, cek apakah jadwal sudah terlewat |
| Sertifikat tidak muncul di peserta | Pastikan sudah klik "Kirim ke Peserta", bukan hanya "Generate" |
| Soal tidak muncul saat ujian | Cek apakah bank soal sudah ditautkan ke babak tersebut |
| Peserta auto-submit di tengah ujian | Peserta melanggar 5x batas anti-cheat (pindah tab/copy-paste) |

---

## 📞 Kontak & Dukungan Teknis

Jika ada kendala teknis yang tidak bisa diselesaikan sendiri:

**HVM Digital — Tim Developer**
- 🌐 Website: [hvmdigital.id](https://hvmdigital.id)
- 📍 Lokasi: Surabaya, Indonesia

---

*Dokumen ini diperbarui: Juni 2026 | Platform Versi: Laravel 11, PHP 8.3*
