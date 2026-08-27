# 🎓 BKK SMK MUTU Cikampek

<div align="center">

![Version](https://img.shields.io/badge/version-1.1.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-10-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-purple.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

**Platform Bursa Kerja Khusus (BKK) modern untuk pencari kerja & perusahaan**

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Dokumentasi](#-dokumentasi) • [Tim](#-tim)

</div>

---

## 📖 Tentang Project

**BKK SMK MUTU** adalah platform digital modern yang menghubungkan para pencari kerja (pengguna umum — bukan hanya siswa atau alumni) dengan peluang karir terbaik. Dibangun dengan teknologi terkini dan desain UI/UX setara platform profesional seperti LinkedIn, Glints, dan JobStreet.

### 🎯 Tujuan

- Memudahkan masyarakat umum mencari pekerjaan
- Mempermudah perusahaan menemukan talent berkualitas
- Monitoring penyaluran kerja oleh sekolah
- Meningkatkan tingkat keterserapan lulusan di dunia kerja

---

## ✨ Fitur Utama

### 👤 Untuk Pengguna Umum (Pencari Kerja)

| Fitur | Deskripsi |
|-------|-----------|
| 🔍 **Advanced Job Search** | Filter & search dengan multiple criteria |
| 📊 **Job Match System** | Rekomendasi pekerjaan berdasarkan skills, lokasi, pengalaman |
| 📝 **Application Tracker** | Timeline status lamaran real-time |
| 💼 **CV Builder ATS-Friendly** | Generate CV profesional (PDF) |
| 📜 **Certificate Management** | Upload & showcase sertifikat |
| 📄 **Berkas Pendukung** | Upload ijazah/SKCK/KTP untuk melengkapi lamaran |
| 🔖 **Bookmark Jobs** | Simpan lowongan untuk dilamar nanti |
| 📅 **Career Events** | Info seminar, workshop, job fair + pendaftaran |
| 📰 **Career News** | Tips karir & info industri |
| 💬 **Direct Messaging** | Chat langsung dengan HR perusahaan dari halaman lowongan |
| 📈 **Profile Completion** | Progress tracker kelengkapan profil |

### 🏢 Untuk Perusahaan

| Fitur | Deskripsi |
|-------|-----------|
| 📢 **Job Posting** | Post lowongan dengan mudah (setelah terverifikasi) |
| 👥 **Applicant Management** | Kelola status pelamar + jadwal wawancara |
| 💬 **Direct Contact** | Chat langsung dengan kandidat dari daftar pelamar |
| 🛡️ **Company Verification** | Submit dokumen legal untuk diverifikasi admin |
| 📊 **Recruitment Dashboard** | Ringkasan lowongan, lamaran, progres rekrutmen |

### 🎯 Untuk Admin

| Fitur | Deskripsi |
|-------|-----------|
| 📊 **Analytics Dashboard** | Overview lengkap seluruh sistem + chart 6 bulan |
| 👥 **User Management** | Kelola semua pengguna (admin, umum, perusahaan) |
| 🏢 **Company Verification** | Verifikasi & approve/reject perusahaan + MoA |
| 📢 **Job Broadcast** | Kirim notifikasi lowongan ke seluruh pengguna |
| 📈 **Placement Reports** | Laporan penyaluran kerja (CSV/Excel) |
| 📰 **News & Events CRUD** | Kelola berita karir & acara |

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.1+
- MySQL 8.0+
- Composer 2.x
- Node.js 18+ (untuk Vite/Tailwind)

### Langkah Instalasi

```bash
# 1. Masuk ke folder project
cd "BKK SMK MUTU"

# 2. Install dependencies backend
composer install

# 3. Install dependencies frontend
npm install

# 4. Setup environment
copy .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
DB_DATABASE=bkk_smk_mutu
DB_USERNAME=root
DB_PASSWORD=your_password

# 6. Buat database & jalankan migrasi
mysql -u root -p -e "CREATE DATABASE bkk_smk_mutu;"
php artisan migrate --seed

# 7. Build asset untuk development
npm run dev

# 8. Jalankan server
php artisan serve
```

**Buka browser:** `http://localhost:8000`

**Login Admin:** `admin@bkk.com` / `password123`

### Menjalankan test

```bash
php artisan test
```

---

## 🛠️ Technology Stack

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 10, PHP 8.1+ |
| **Database** | MySQL 8.0 |
| **Auth** | Laravel Breeze |
| **Role** | Kolom `users.role` + Spatie Permissions |
| **Frontend** | Tailwind CSS, Alpine.js |
| **Template** | Blade Templates |
| **Icons** | Heroicons |
| **Pattern** | MVC, Service Layer |

---

## 👥 User Roles

| Role | Akses |
|------|-------|
| **Admin** | Full access ke seluruh sistem |
| **Umum** | Cari lowongan, lamar kerja, CV builder, chat |
| **Perusahaan** | Post lowongan & kelola pelamar |

> Catatan: role lama `siswa`, `alumni`, dan `guru` sudah digabung/dihapus.
> Seluruh pengguna publik memakai role `umum`.

---

## 🔐 Keamanan

- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection Protection
- ✅ Input Validation (Form Request + rule enum)
- ✅ Password Hashing (Bcrypt)
- ✅ Role-based Access Control (RBAC)
- ✅ Session Security
- ✅ File Upload Validation + penyimpanan privat
- ✅ Rate Limiting (login, kirim pesan, generate CV)

---

## ⚡ Performa

- ✅ Query Optimization & Eager Loading
- ✅ Database Indexes (role, company_name, unread messages)
- ✅ Pagination
- ✅ Asset Optimization (WebP via ImageProcessor)

---

## 📚 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [AKUN_TEST.md](docs/AKUN_TEST.md) | Daftar akun untuk testing |

---

## 🌐 Browser Support

- ✅ Chrome, Firefox, Safari, Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)
- ✅ Responsive — Mobile First Design

---

## 👥 Tim

**BKK SMK MUTU Cikampek**

- 🌐 Website: https://bkksmkmutu.sch.id
- 📧 Email: bkk@smkmutu.sch.id
- 📍 Alamat: Cikampek, Jawa Barat

---

## 📞 Kontak & Support

- 📧 Email: bkk@smkmutu.sch.id

---

<div align="center">

**Dibuat dengan ❤️ untuk para pencari kerja SMK MUTU Cikampek**

**v1.1.0 • 2026**

</div>
