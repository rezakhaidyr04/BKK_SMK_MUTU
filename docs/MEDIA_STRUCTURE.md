# Media Structure Guide

Panduan ini dipakai agar file gambar/upload di project tetap rapi, hemat storage, dan tidak masuk ke codingan secara manual.

## Prinsip utama

### 1. Asset statis aplikasi
Simpan di:

```text
public/images/
```

Contoh:
- `public/images/logos/mutu_logo.png`
- `public/images/foto_siswa/siswa.png`
- `public/images/perusahaan/perusahaan.jpeg`

Gunakan untuk:
- logo website
- ilustrasi landing page
- gambar default bawaan aplikasi
- icon atau visual yang jarang berubah

### 2. File upload dari user/admin
Simpan di:

```text
storage/app/public/
```

Lalu database hanya menyimpan:
- path file
- nama file relatif

Contoh nilai di database:

```text
company-logos/logo-12-1724160000.webp
news-thumbnails/thumb-berita-1724160000.webp
event-posters/poster-1724160000.webp
```

## Pola yang dipakai project ini sekarang

Project ini sudah memakai pola yang benar untuk upload dinamis:

- `app/Http/Controllers/ProfileController.php`
  - avatar / foto profil disimpan ke folder `profile-photos`
- `app/Http/Controllers/Company/ProfileController.php`
  - logo perusahaan disimpan ke folder `company-logos`
- `app/Http/Controllers/Admin/NewsController.php`
  - thumbnail berita disimpan ke folder `news-thumbnails`
- `app/Http/Controllers/Admin/NewsController.php`
  - gambar isi berita disimpan ke folder `news-images`
- `app/Http/Controllers/Admin/EventController.php`
  - poster event disimpan ke folder `event-posters`
- `app/Http/Controllers/CertificateController.php`
  - sertifikat disimpan ke folder private `certificates`
- `app/Http/Controllers/UserDocumentController.php`
  - dokumen user disimpan ke folder private `user-documents`
- `app/Http/Controllers/CvBuilderController.php`
  - file CV disimpan ke folder private `cv-files`
- `app/Services/ImageProcessor.php`
  - gambar diproses lalu disimpan ke `storage/app/public/...`
  - path relatif dikembalikan untuk disimpan ke database

## Struktur folder yang direkomendasikan

### Asset statis

```text
public/images/
  logos/
  foto_siswa/
  perusahaan/
  defaults/
```

### Upload dinamis publik

```text
storage/app/public/
  profile-photos/
  company-logos/
  news-thumbnails/
  event-posters/
  certificates/
  cv-files/
  user-documents/
```

### Upload dinamis privat

```text
storage/app/private/
  company_verifications/
  sensitive-documents/
```

Gunakan folder privat untuk file yang tidak boleh diakses langsung dari URL publik.

## Mapping penggunaan yang disarankan

### User / siswa / alumni
- foto profil → `profile-photos/`
- CV → `cv-files/`
- dokumen pendukung → `user-documents/`
- sertifikat → `certificates/`

### Company
- logo perusahaan → `company-logos/`
- dokumen verifikasi → `company_verifications/{company_id}/` pada disk private

### Admin content
- thumbnail berita → `news-thumbnails/`
- poster event → `event-posters/`

## Cara tampilkan file

### Asset statis

```blade
asset('images/logos/mutu_logo.png')
```

### Upload publik dari storage

```blade
Storage::url($model->thumbnail)
```

atau:

```blade
asset('storage/' . $model->thumbnail)
```

## Aturan yang sebaiknya diikuti

1. Jangan simpan binary gambar ke database.
2. Jangan hardcode upload user ke `public/images/...` secara manual.
3. Jangan campur asset statis dengan upload user.
4. Simpan hanya path file di database.
5. Hapus file lama saat user mengganti gambar, jika memang file lama sudah tidak dipakai.
6. Untuk gambar upload, gunakan service terpusat seperti `ImageProcessor` agar ukuran dan format konsisten.

## Rekomendasi praktis untuk project ini

### Tetap pakai `public/images` untuk:
- logo BKK
- gambar hero/landing page
- ilustrasi siswa
- foto perusahaan statis untuk halaman welcome

### Tetap pakai `storage/app/public` untuk:
- logo perusahaan dari form web
- thumbnail berita
- poster event
- foto profil jika nanti ditambahkan
- dokumen visual lain yang diupload dari dashboard

## Kesimpulan

Struktur terbaik untuk project ini:

- **gambar statis** → `public/images`
- **upload dinamis** → `storage/app/public`
- **dokumen sensitif** → `storage/app/private`
- **database** → simpan path saja, bukan isi file
