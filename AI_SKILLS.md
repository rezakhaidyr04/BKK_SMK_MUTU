# AI_SKILLS.md

## BKK SMK MUTU KARAWANG - MASTER AI KNOWLEDGE

### SYSTEM ROLE

Anda adalah tim software kelas dunia yang terdiri dari:

* Senior Software Architect
* Senior Laravel 12 Developer
* Senior UI/UX Designer
* Senior Database Architect
* Senior Security Engineer
* Senior DevOps Engineer
* Senior Product Manager
* Senior QA Engineer
* Senior Code Reviewer

Tugas utama Anda adalah membangun aplikasi Bursa Kerja Khusus (BKK) SMK modern yang memiliki kualitas lebih baik daripada BKKBISA.

---

# PROJECT INFORMATION

Nama Project:
BKK SMK MUTU KARAWANG

Jenis:
Web Application

Tujuan:
Membantu siswa dan alumni mendapatkan pekerjaan melalui sistem digital yang modern, cepat, dan mudah digunakan.

Target Pengguna:

1. Admin BKK
2. Guru BK
3. Siswa
4. Alumni
5. Perusahaan
6. Kepala Sekolah

---

# TECHNOLOGY STACK

Backend:

* Laravel 12

Frontend:

* Blade
* Tailwind CSS
* Alpine.js

Database:

* MySQL

Authentication:

* Laravel Breeze

Version Control:

* Git

Deployment:

* VPS / Shared Hosting

---

# CODING STANDARDS

Selalu gunakan:

* Clean Architecture
* SOLID Principles
* Repository Pattern
* Service Layer Pattern
* Form Request Validation
* RESTful Standard
* Eloquent Relationships
* Dependency Injection

Hindari:

* Hardcoded data
* Duplicate code
* Query berulang (N+1)
* Controller yang terlalu besar
* Logic bisnis di View

---

# UI/UX STANDARDS

Tampilan harus:

* Modern
* Clean
* Responsive
* Professional
* Mobile First
* Mudah digunakan

Inspirasi:

* BKKBISA
* LinkedIn
* Glints
* JobStreet
* Kalibrr

Design System:

Primary:
#2563EB

Secondary:
#10B981

Warning:
#F59E0B

Danger:
#EF4444

Background:
#F8FAFC

Font:
Poppins

Radius:
12px

Shadow:
Soft Modern Shadow

---

# USER ROLES

## ADMIN

Hak Akses:

* Kelola pengguna
* Kelola siswa
* Kelola alumni
* Kelola perusahaan
* Kelola lowongan
* Kelola event
* Kelola berita
* Kelola laporan
* Kelola notifikasi
* Monitoring sistem

---

## SISWA

Hak Akses:

* Melihat lowongan
* Melamar pekerjaan
* Membuat CV
* Upload sertifikat
* Mengikuti event
* Chat perusahaan
* Tracking lamaran

---

## ALUMNI

Hak Akses:

* Melamar pekerjaan
* Membuat CV
* Upload sertifikat
* Chat perusahaan
* Tracking lamaran

---

## PERUSAHAAN

Hak Akses:

* Membuat lowongan
* Mengelola lowongan
* Melihat pelamar
* Menyeleksi pelamar
* Mengirim undangan interview
* Chat pelamar

---

## GURU BK

Hak Akses:

* Monitoring siswa
* Monitoring alumni
* Monitoring penyaluran kerja
* Membuat laporan

---

# FITUR WAJIB

## AUTHENTICATION

* Login
* Register
* Logout
* Forgot Password
* Email Verification
* Remember Me
* Multi Role

---

## DASHBOARD

Admin Dashboard

* Total Siswa
* Total Alumni
* Total Perusahaan
* Total Lowongan
* Total Lamaran
* Grafik Statistik
* Aktivitas Terbaru

Siswa Dashboard

* Lowongan Terbaru
* Lamaran Aktif
* Event Terbaru
* Status Lamaran

Perusahaan Dashboard

* Lowongan Aktif
* Pelamar Baru
* Statistik Pelamar

---

## PROFIL SISWA

Data:

* NIS
* Nama Lengkap
* Jurusan
* Tahun Lulus
* Email
* Nomor HP
* Alamat
* Foto Profil

---

## PROFIL ALUMNI

Data:

* Nama
* Tahun Lulus
* Status Kerja
* Perusahaan Saat Ini
* Pengalaman Kerja

---

## PERUSAHAAN

Data:

* Nama Perusahaan
* Logo
* Website
* Email
* Telepon
* Alamat
* Deskripsi

---

## LOWONGAN PEKERJAAN

Data:

* Judul
* Posisi
* Lokasi
* Jenis Kerja
* Gaji
* Kualifikasi
* Benefit
* Deadline

Fitur:

* Search
* Filter
* Bookmark
* Apply Job

---

## LAMARAN PEKERJAAN

Status:

* Dikirim
* Diproses
* Interview
* Diterima
* Ditolak

Fitur:

* Timeline Lamaran
* Tracking Lamaran

---

## CV BUILDER

Fitur:

* Generate PDF
* Download CV
* ATS Friendly Template

---

## SERTIFIKAT

Fitur:

* Upload
* Download
* Validasi

---

## EVENT KARIR

Jenis:

* Seminar
* Workshop
* Pelatihan
* Job Fair

---

## BERITA KARIR

Kategori:

* Tips Karir
* Dunia Kerja
* Informasi Industri

---

## CHAT

Fitur:

* Real-time Chat
* Siswa ↔ Perusahaan
* Alumni ↔ Perusahaan

---

## NOTIFIKASI

Jenis:

* Lowongan Baru
* Lamaran Diproses
* Interview
* Event Baru

---

# DATABASE TABLES

users
roles
students
alumni
companies
jobs
applications
certificates
cv_files
news
events
notifications
messages
conversations
bookmarks
reports
skills
user_skills

---

# SECURITY RULES

Selalu implementasikan:

* CSRF Protection
* XSS Protection
* SQL Injection Protection
* Input Validation
* Authorization Policy
* Activity Log
* Audit Log
* Rate Limiting

Jangan pernah membuat fitur yang rentan terhadap eksploitasi.

---

# PERFORMANCE RULES

Selalu gunakan:

* Pagination
* Eager Loading
* Query Optimization
* Caching
* Lazy Loading jika diperlukan

---

# AI DEVELOPMENT WORKFLOW

Sebelum membuat kode:

1. Analisis kebutuhan.
2. Identifikasi database yang dibutuhkan.
3. Buat relasi tabel.
4. Buat struktur folder.
5. Buat migration.
6. Buat model.
7. Buat controller.
8. Buat service.
9. Buat repository.
10. Buat request validation.
11. Buat route.
12. Buat UI.
13. Review keamanan.
14. Review performa.

---

# AI RESPONSE RULES

Ketika diminta membuat fitur:

1. Jelaskan arsitektur terlebih dahulu.
2. Jelaskan database yang dibutuhkan.
3. Buat kode production-ready.
4. Buat kode lengkap.
5. Jangan menggunakan placeholder.
6. Jangan membuat kode setengah jadi.
7. Gunakan best practice Laravel terbaru.
8. Berikan hasil yang siap deploy.

---

# FINAL OBJECTIVE

Bangun aplikasi BKK SMK MUTU KARAWANG yang:

* Modern
* Cepat
* Aman
* Responsive
* Mudah digunakan
* Siap production
* Setara aplikasi startup profesional tahun 2026
* Lebih baik daripada BKKBISA
