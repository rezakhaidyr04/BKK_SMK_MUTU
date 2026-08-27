# 🔐 Daftar Akun Mutu Career Center

> File ini berisi semua akun default dan akun test untuk keperluan development.
> **JANGAN upload file ini ke GitHub / server production!**

---

## 👑 Akun Admin

| Role | Nama | Email | Password |
|------|------|-------|----------|
| **Admin** | Super Admin BKK | admin@bkk.com | password123 |

---

## 🏢 Akun Perusahaan (Test)

| Nama Perusahaan | Email | Password | Industri |
|-----------------|-------|----------|----------|
| PT Contoh BKK *(default)* | pt.contoh@bkk.com | password123 | Umum |
| PT Maju Bersama | pt.maju@bkk.com | password123 | Manufaktur |
| PT Teknologi Nusantara | pt.tekno@bkk.com | password123 | IT |
| PT Ritel Cikampek | pt.retail@bkk.com | password123 | Ritel |
| PT Cepat Kirim Logistik | pt.logistik@bkk.com | password123 | Logistik |
| PT Cikampek Hospitality | pt.hotel@bkk.com | password123 | Perhotelan |

---

## 👤 Akun Pengguna Umum (Test)

Role `umum` = pencari kerja publik (bukan hanya siswa/alumni).

| Nama | Email | Password |
|------|-------|----------|
| Pengguna Demo BKK *(default)* | umum@bkk.com | password123 |
| Budi Santoso | budi.santoso@umum.bkk.com | password123 |
| Siti Rahayu | siti.rahayu@umum.bkk.com | password123 |
| Rizky Pratama | rizky.pratama@umum.bkk.com | password123 |
| Dewi Anggraini | dewi.anggraini@umum.bkk.com | password123 |

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

# Isi ulang data dummy (pengguna, perusahaan, lowongan, lamaran)
php artisan db:seed --class=DummyDataSeeder

# Reset database + seed ulang (HATI-HATI: hapus semua data!)
php artisan migrate:fresh --seed
```

---

## 📊 Data Test yang Tersedia

- ✅ **5 Perusahaan** di wilayah Cikampek (terverifikasi)
- ✅ **9 Lowongan** aktif dengan deskripsi, kualifikasi, gaji, dan deadline
- ✅ **8 Pengguna umum** dari berbagai latar belakang keahlian
- ✅ **16 Lamaran** dengan status: submitted, under_review, interviewed, accepted, rejected
- ✅ **Bookmark** tersimpan untuk setiap pengguna

---

*Dibuat: Juni 2026 | Proyek: Mutu Career Center*
