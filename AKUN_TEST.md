# 🔐 Daftar Akun BKK SMK MUTU CIKAMPEK

> File ini berisi semua akun default dan akun test untuk keperluan development.
> **JANGAN upload file ini ke GitHub / server production!**

---

## 👑 Akun Admin & Staff

| Role | Nama | Email | Password |
|------|------|-------|----------|
| **Admin** | Super Admin BKK | admin@bkk.com | password123 |
| **Guru** | Guru BKK | guru@bkk.com | password123 |

---

## 🏢 Akun Perusahaan (Test)

| Nama Perusahaan | Email | Password | Industri |
|-----------------|-------|----------|----------|
| PT Contoh BKK *(default)* | company@bkk.com | password123 | Umum |
| PT Maju Bersama | pt.maju@bkk.com | password123 | Manufaktur |
| PT Teknologi Nusantara | pt.tekno@bkk.com | password123 | IT |
| PT Ritel Cikampek | pt.retail@bkk.com | password123 | Ritel |
| PT Cepat Kirim Logistik | pt.logistik@bkk.com | password123 | Logistik |
| PT Cikampek Hospitality | pt.hotel@bkk.com | password123 | Perhotelan |

---

## 🎓 Akun Siswa (Test)

| Nama | Email | Password | Jurusan |
|------|-------|----------|---------|
| Siswa Demo BKK *(default)* | siswa@bkk.com | password123 | - |
| Budi Santoso | budi.santoso@siswa.bkk.com | password123 | Teknik Mesin |
| Siti Rahayu | siti.rahayu@siswa.bkk.com | password123 | Akuntansi |
| Rizky Pratama | rizky.pratama@siswa.bkk.com | password123 | Rekayasa Perangkat Lunak |
| Dewi Anggraini | dewi.anggraini@siswa.bkk.com | password123 | Perhotelan |
| Andi Kurniawan | andi.kurniawan@siswa.bkk.com | password123 | Teknik Komputer & Jaringan |
| Maya Fitriani | maya.fitriani@siswa.bkk.com | password123 | Tata Boga |
| Dimas Setiawan | dimas.setiawan@siswa.bkk.com | password123 | Teknik Otomotif |
| Nurul Hidayah | nurul.hidayah@siswa.bkk.com | password123 | Administrasi Perkantoran |

---

## 🌐 URL Akses

| Halaman | URL |
|---------|-----|
| Beranda | http://127.0.0.1:8000 |
| Login | http://127.0.0.1:8000/login |
| Daftar | http://127.0.0.1:8000/register |
| Dasbor | http://127.0.0.1:8000/dashboard |
| Lowongan | http://127.0.0.1:8000/jobs |
| Admin Panel | http://127.0.0.1:8000/admin/users |

---

## 🛠️ Perintah Berguna

```bash
# Jalankan server
php artisan serve

# Pulihkan akun jika terhapus
php artisan db:restore-admin

# Pulihkan + reset password ke default
php artisan db:restore-admin --force

# Isi ulang data dummy (siswa, perusahaan, lowongan, lamaran)
php artisan db:seed --class=DummyDataSeeder

# Reset database + seed ulang (HATI-HATI: hapus semua data!)
php artisan migrate:fresh --seed
```

---

## 📊 Data Test yang Tersedia

- ✅ **5 Perusahaan** di wilayah Cikampek (terverifikasi)
- ✅ **9 Lowongan** aktif dengan deskripsi, kualifikasi, gaji, dan deadline
- ✅ **8 Siswa** dari berbagai jurusan SMK MUTU Cikampek
- ✅ **16 Lamaran** dengan status: submitted, under_review, interviewed, accepted, rejected
- ✅ **Bookmark** tersimpan untuk setiap siswa

---

*Dibuat: Juni 2026 | Proyek: BKK SMK MUTU CIKAMPEK*
