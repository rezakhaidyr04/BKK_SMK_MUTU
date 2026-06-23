# 🎓 BKK SMK MUTU KARAWANG - Career Development Platform

<div align="center">

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-purple.svg)
![Status](https://img.shields.io/badge/status-production%20ready-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

**Platform Bursa Kerja Khusus (BKK) modern setara startup teknologi 2026**

[Fitur](#-fitur-utama) •
[Demo](#-demo) •
[Instalasi](#-quick-start) •
[Dokumentasi](#-dokumentasi) •
[Screenshots](#-screenshots)

</div>

---

## 📖 Tentang Project

BKK SMK MUTU Karawang adalah platform digital modern yang menghubungkan siswa dan alumni SMK dengan peluang karir terbaik. Dibangun dengan teknologi terkini dan desain UI/UX setara dengan platform startup profesional seperti LinkedIn, Glints, dan JobStreet.

### 🎯 Tujuan
- Memudahkan siswa/alumni mencari pekerjaan
- Mempermudah perusahaan menemukan talent berkualitas
- Monitoring penyaluran kerja oleh sekolah
- Meningkatkan tingkat keterserapan lulusan di dunia kerja

---

## ✨ Fitur Utama

### 👨‍🎓 Untuk Siswa & Alumni
- 🔍 **Advanced Job Search** - Filter & search dengan multiple criteria
- 📊 **Job Match System** - Rekomendasi pekerjaan berdasarkan skills
- 📝 **Application Tracker** - Timeline status lamaran real-time
- 💼 **CV Builder ATS-Friendly** - Generate CV profesional
- 📜 **Certificate Management** - Upload & showcase certificates
- 🔖 **Bookmark Jobs** - Save jobs untuk apply nanti
- 📅 **Career Events** - Info seminar, workshop, job fair
- 📰 **Career News** - Tips karir & info industri
- 💬 **Direct Messaging** - Chat dengan HR perusahaan
- 📈 **Profile Completion** - Progress tracker profile

### 🏢 Untuk Perusahaan
- 📢 **Job Posting** - Post lowongan dengan mudah
- 👥 **Applicant Management** - Kelola pelamar efisien
- 📊 **Recruitment Analytics** - Dashboard statistik
- 🔍 **Talent Search** - Cari kandidat by skills
- ⭐ **Applicant Rating** - Review & rating system
- 💬 **Direct Contact** - Chat dengan kandidat

### 🎯 Untuk Admin/BK
- 📊 **Analytics Dashboard** - Overview lengkap sistem
- 👥 **User Management** - Kelola users semua role
- 🏢 **Company Verification** - Approve perusahaan
- 📈 **Placement Reports** - Laporan penyaluran kerja
- 📊 **Statistics & Charts** - Visualisasi data
- 🔔 **Notification Center** - Broadcast announcements

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer 2.x

### Installation (5 menit)

```bash
# 1. Clone or extract project
cd "BKK SMK MUTU"

# 2. Install dependencies
composer install

# 3. Setup environment
copy .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=bkk_smk_mutu
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Create database & migrate
mysql -u root -p -e "CREATE DATABASE bkk_smk_mutu;"
php artisan migrate

# 6. Create admin user
php artisan tinker
# Paste: \App\Models\User::create(['name'=>'Admin','email'=>'admin@bkk.com','password'=>bcrypt('admin123'),'role'=>'admin','is_active'=>true]);

# 7. Run server
php artisan serve
```

**Buka browser**: `http://localhost:8000`

**Login**: `admin@bkk.com` / `admin123`

📚 **Panduan lengkap**: Lihat [QUICK_START.md](QUICK_START.md)

---

## 🎨 Screenshots

### Dashboard Student
![Dashboard](docs/screenshots/dashboard-student.png)
*Modern dashboard dengan job recommendations, application tracking, dan activity timeline*

### Job Search
![Job Search](docs/screenshots/jobs-listing.png)
*Advanced search dengan multiple filters dan modern card design*

### Job Detail
![Job Detail](docs/screenshots/job-detail.png)
*Comprehensive job information dengan sticky application sidebar*

### Application Tracker
![Applications](docs/screenshots/applications.png)
*Track semua lamaran dengan visual status indicators*

---

## 🛠️ Technology Stack

### Backend
- **Laravel 12** - PHP Framework
- **MySQL 8** - Database
- **Laravel Breeze** - Authentication
- **Spatie Permissions** - Role Management

### Frontend
- **Tailwind CSS** - Utility-first CSS
- **Alpine.js** - Lightweight JS framework
- **Blade Templates** - Laravel templating
- **Heroicons** - Beautiful SVG icons

### Architecture
- **Repository Pattern** - Data access layer
- **Service Layer** - Business logic
- **MVC Pattern** - Clean architecture
- **SOLID Principles** - Code quality

---

## 📁 Project Structure

```
BKK SMK MUTU/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── JobController.php
│   │   │   ├── ApplicationController.php
│   │   │   └── ... (13 controllers)
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Job.php
│   │   ├── Application.php
│   │   └── ... (15 models)
│   └── Repositories/
│       └── ... (repositories)
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── navigation.blade.php
│       ├── dashboard/
│       │   ├── student.blade.php
│       │   └── admin.blade.php
│       ├── jobs/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── applications/
│           └── index.blade.php
├── database/
│   └── migrations/
│       └── 2024_06_21_000000_create_bkk_v2_tables.php
├── routes/
│   └── web.php
└── docs/
    ├── AI_SKILLS.md
    ├── TRANSFORMATION_SUMMARY.md
    ├── DEPLOYMENT_GUIDE.md
    └── QUICK_START.md
```

---

## 📚 Dokumentasi

- 📖 **[Quick Start Guide](QUICK_START.md)** - Cara cepat menjalankan project
- 🚀 **[Deployment Guide](DEPLOYMENT_GUIDE.md)** - Panduan deploy ke production
- 📝 **[Transformation Summary](TRANSFORMATION_SUMMARY.md)** - Detail transformasi lengkap
- 🤖 **[AI Skills](AI_SKILLS.md)** - Master knowledge untuk development

---

## 🎯 User Roles

| Role | Akses |
|------|-------|
| **Admin** | Full access ke seluruh sistem |
| **Guru BK** | Monitoring & reports |
| **Siswa** | Job search & apply |
| **Alumni** | Job search & apply |
| **Perusahaan** | Post jobs & manage applicants |

---

## 🔐 Security Features

- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection Protection
- ✅ Input Validation
- ✅ Password Hashing (Bcrypt)
- ✅ Role-based Access Control
- ✅ Session Security
- ✅ File Upload Validation
- ✅ Rate Limiting
- ✅ Soft Deletes

---

## ⚡ Performance

- ✅ Database Query Optimization
- ✅ Eager Loading (N+1 Prevention)
- ✅ Route Caching
- ✅ Config Caching
- ✅ View Caching
- ✅ Pagination
- ✅ Lazy Loading
- ✅ Asset Optimization

---

## 🌐 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 📱 Responsive Design

- ✅ Mobile First
- ✅ Tablet Optimized
- ✅ Desktop Optimized
- ✅ Touch-friendly UI
- ✅ Adaptive Layout

---

## 🔄 Future Enhancements

### Phase 2 (Optional)
- [ ] Mobile App (React Native / Flutter)
- [ ] Real-time Notifications (WebSocket)
- [ ] Video Interview Integration
- [ ] AI-powered Job Matching
- [ ] Psychometric Test Integration
- [ ] Company Rating & Review System
- [ ] Salary Benchmarking
- [ ] Career Path Recommendation
- [ ] Multi-language Support
- [ ] PWA (Progressive Web App)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Team

**BKK SMK MUTU Karawang**
- Website: https://bkksmkmutu.sch.id
- Email: bkk@smkmutu.sch.id
- Phone: (0267) 123-4567

**Development**
- Built by: AI Assistant
- Architecture: Enterprise-grade
- Code Quality: Production-ready
- Status: ✅ Ready to Deploy

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS Team
- Alpine.js Team
- Heroicons
- BKKBISA (Inspiration)
- LinkedIn (UI/UX Reference)
- Glints (Feature Reference)
- JobStreet (Search Reference)

---

## 📞 Support

Butuh bantuan? Hubungi kami:

- 📧 Email: bkk@smkmutu.sch.id
- 📱 WhatsApp: +62 xxx xxxx xxxx
- 🌐 Website: https://bkksmkmutu.sch.id
- 📍 Alamat: Jl. Pendidikan No. 123, Karawang, Jawa Barat

---

## ⭐ Star Us!

Jika project ini membantu, berikan ⭐ di GitHub!

---

<div align="center">

**Built with ❤️ for Students**

**Version 1.0.0** • **2026** • **Production Ready**

[⬆ Back to Top](#-bkk-smk-mutu-karawang---career-development-platform)

</div>
#   B K K _ S M K _ M U T U  
 #   B K K _ S M K _ M U T U  
 #   B K K _ S M K _ M U T U  
 