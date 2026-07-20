# 🎓 BKK SMK MUTU Cikampek

<div align="center">

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-10-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-purple.svg)
![Status](https://img.shields.io/badge/status-production%20ready-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

**Platform Bursa Kerja Khusus (BKK) modern untuk siswa & alumni SMK MUTU Cikampek**

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Dokumentasi](#-dokumentasi) • [Tim](#-tim)

</div>

---

## 📖 Tentang Project

**BKK SMK MUTU** adalah platform digital modern yang menghubungkan siswa dan alumni SMK MUTU Cikampek dengan peluang karir terbaik. Dibangun dengan teknologi terkini dan desain UI/UX setara platform profesional seperti LinkedIn, Glints, dan JobStreet.

### 🎯 Tujuan

- Memudahkan siswa & alumni mencari pekerjaan
- Mempermudah perusahaan menemukan talent berkualitas
- Monitoring penyaluran kerja oleh sekolah
- Meningkatkan tingkat keterserapan lulusan di dunia kerja

---

## ✨ Fitur Utama

### 👨‍🎓 Untuk Siswa & Alumni

| Fitur | Deskripsi |
|-------|-----------|
| 🔍 **Advanced Job Search** | Filter & search dengan multiple criteria |
| 📊 **Job Match System** | Rekomendasi pekerjaan berdasarkan skills |
| 📝 **Application Tracker** | Timeline status lamaran real-time |
| 💼 **CV Builder ATS-Friendly** | Generate CV profesional |
| 📜 **Certificate Management** | Upload & showcase sertifikat |
| 🔖 **Bookmark Jobs** | Simpan lowongan untuk dilamar nanti |
| 📅 **Career Events** | Info seminar, workshop, job fair |
| 📰 **Career News** | Tips karir & info industri |
| 💬 **Direct Messaging** | Chat langsung dengan HR perusahaan |
| 📈 **Profile Completion** | Progress tracker kelengkapan profil |

### 🏢 Untuk Perusahaan

| Fitur | Deskripsi |
|-------|-----------|
| 📢 **Job Posting** | Post lowongan dengan mudah |
| 👥 **Applicant Management** | Kelola pelamar secara efisien |
| 📊 **Recruitment Analytics** | Dashboard statistik rekrutmen |
| 🔍 **Talent Search** | Cari kandidat berdasarkan skills |
| ⭐ **Applicant Rating** | Review & rating pelamar |
| 💬 **Direct Contact** | Chat langsung dengan kandidat |

### 🎯 Untuk Admin / Guru BK

| Fitur | Deskripsi |
|-------|-----------|
| 📊 **Analytics Dashboard** | Overview lengkap seluruh sistem |
| 👥 **User Management** | Kelola semua pengguna |
| 🏢 **Company Verification** | Verifikasi & approve perusahaan |
| 📈 **Placement Reports** | Laporan penyaluran kerja |
| 📊 **Statistics & Charts** | Visualisasi data keterserapan |
| 🔔 **Notification Center** | Broadcast pengumuman |

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

**Login Admin:** `admin@bkk.com` / `admin123`

### Menjalankan test

```bash
php artisan test
```

> 📚 Panduan lengkap tersedia di [QUICK_START.md](QUICK_START.md)

---

## 🛠️ Technology Stack

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Database** | MySQL 8.0 |
| **Auth** | Laravel Breeze |
| **Role** | Spatie Permissions |
| **Frontend** | Tailwind CSS, Alpine.js |
| **Template** | Blade Templates |
| **Icons** | Heroicons |
| **Pattern** | MVC, Repository, Service Layer |

---

## 📁 Struktur Project

```
BKK SMK MUTU/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # 13+ controllers
│   │   └── Middleware/
│   ├── Models/                # 15+ models
│   └── Repositories/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── dashboard/
│       ├── jobs/
│       └── applications/
├── database/
│   └── migrations/
├── routes/
│   └── web.php
└── public/
```

---

## 👥 User Roles

| Role | Akses |
|------|-------|
| **Admin** | Full access ke seluruh sistem |
| **Guru BK** | Monitoring & laporan |
| **Siswa** | Cari lowongan & melamar |
| **Alumni** | Cari lowongan & melamar |
| **Perusahaan** | Post lowongan & kelola pelamar |

---

## 🔐 Keamanan

- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection Protection
- ✅ Input Validation
- ✅ Password Hashing (Bcrypt)
- ✅ Role-based Access Control (RBAC)
- ✅ Session Security
- ✅ File Upload Validation
- ✅ Rate Limiting

---

## ⚡ Performa

- ✅ Query Optimization & Eager Loading
- ✅ Route, Config & View Caching
- ✅ Pagination
- ✅ Asset Optimization

---

## 📚 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [QUICK_START.md](QUICK_START.md) | Cara cepat menjalankan project |
| [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | Panduan deploy ke production |
| [TRANSFORMATION_SUMMARY.md](TRANSFORMATION_SUMMARY.md) | Detail transformasi sistem |
| [AKUN_TEST.md](AKUN_TEST.md) | Daftar akun untuk testing |

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
- 📱 WhatsApp: +62 xxx xxxx xxxx
- 🌐 Website: https://bkksmkmutu.sch.id

---

<div align="center">

**Dibuat dengan ❤️ untuk Siswa & Alumni SMK MUTU Cikampek**

**v1.0.0 • 2026 • Production Ready**

[⬆ Kembali ke Atas](#-bkk-smk-mutu-cikampek)

</div>