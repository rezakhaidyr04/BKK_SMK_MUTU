# 🚀 Mutu Career Center - DEPLOYMENT GUIDE

## 📋 PREREQUISITES

### System Requirements
- PHP 8.1 atau lebih tinggi (disarankan 8.2+)
- Ekstensi PHP wajib: `pdo_mysql openssl mbstring tokenizer ctype fileinfo gd dom zip`
  (cek: `php -m`). Tanpa `gd` = upload logo/payment gagal; tanpa `fileinfo` =
  validasi MIME gagal; tanpa `dom/zip` = export PDF bisa 500.
- MySQL 8.0 atau lebih tinggi
- Composer 2.x
- Node.js 18.x atau lebih tinggi (WAJIB untuk `npm run build` — blade memakai `@vite`)
- Web Server (Apache/Nginx)

## 🔧 INSTALLATION STEPS

### 1. Clone & Setup
```bash
# Clone repository (jika dari git)
git clone [repository-url]
cd "Mutu Career Center"

# Production: salin dari template production (BUKAN .env development)
copy .env.production.example .env
# Lalu isi: APP_KEY (via key:generate), DB_*, MAIL_*, APP_URL=https://domain
```

### 1b. Build Frontend (WAJIB — halaman memakai @vite)
```bash
npm ci
npm run build
# Hasil: public/build/manifest.json + assets. Tanpa ini halaman 500/Vite error.
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate
```

### 3. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bkk_smk_mutu
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
# Create database
mysql -u root -p
CREATE DATABASE bkk_smk_mutu;
exit;

# Run migrations
php artisan migrate --force

# Seed production: HANYA role + akun admin (aman di production).
# DILARANG: db:seed penuh / DummyDataSeeder / BulkUmumSeeder / migrate:fresh
# di production — data dummy akan mencemari data asli.
php artisan db:seed --class=RoleAndAdminSeeder --force
php artisan db:seed --class=CompanyRolePermissionSeeder --force
```

### 5. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows - ensure writable folders:
# - storage/
# - bootstrap/cache/
```

### 6. Configure Web Server

#### Apache (.htaccess already included)
```apache
<VirtualHost *:80>
    ServerName bkk.smkmutu.local
    DocumentRoot "D:/Mutu Career Center/public"
    
    <Directory "D:/Mutu Career Center/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name bkk.smkmutu.local;
    root /path/to/Mutu Career Center/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. Optimize for Production
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 8. Queue Worker & Scheduler — WAJIB (bukan opsional)

Broadcast email (`SendJobBroadcastChunk`, ±50/job, timeout 300 dtk),
notifikasi massal publish (`SendJobNotificationsChunk`, ±200/job, timeout
300 dtk), dan generate CV (`GenerateCvJob`, timeout 120 dtk) berjalan di
antrean database. Tanpa worker, dispatch menumpuk dan tidak terkirim.

Aturan timeout (jangan dilanggar):
- `config/queue.php` database `retry_after = 400` HARUS > timeout job
  terpanjang (300). Menurunkan retry_after = email/notifikasi GANDA.
- Worker `--timeout` HARUS > 300 (disarankan 330).
- Setiap deploy: `php artisan queue:restart` (worker lama memakai kode lama).

```bash
# Supervisor (Linux, direkomendasikan) — /etc/supervisor/conf.d/bkk-worker.conf
# [program:bkk-worker]
# command=php /path/to/app/artisan queue:work --sleep=3 --tries=3 --timeout=330
# autostart=true
# autorestart=true
# numprocs=1
# user=www-data

# Alternatif sementara (jangan untuk production tetap):
php artisan queue:work --tries=3 --timeout=330

# Scheduler — SATU cron entry (cleanup file CV >24 jam di Kernel):
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

Monitoring antrean:

```bash
php artisan queue:failed        # lihat job yang gagal
php artisan queue:retry all     # ulangi job yang gagal
php artisan queue:restart       # WAJIB setelah tiap deploy/update kode
```

## 👤 DEFAULT USER ACCOUNTS

### Create Admin Account (production-safe)
```bash
# Seeder resmi (idempotent, aman di production):
php artisan db:seed --class=RoleAndAdminSeeder --force
```
Kredensial default ada di `database/seeders/RoleAndAdminSeeder.php` —
SEGERA ganti password setelah login pertama. JANGAN membuat akun via
tinker dengan `role` manual tanpa `syncRoles` (menyebabkan desync
kolom role vs Spatie; middleware hanya membaca kolom `role`).

### DILARANG di production
- `php artisan db:seed` penuh (membuat perusahaan/lowongan/user/review dummy)
- `BulkUmumSeeder` / `DummyDataSeeder` (data testing 10.000+ akun)
- Role selain `admin/company/umum` (sudah dikonsolidasi; `student` tidak ada)

## 🔐 SECURITY CHECKLIST

### Before Going Live
- [ ] Change `APP_ENV` to `production` in `.env`
- [ ] Set `APP_DEBUG` to `false` in `.env`
- [ ] Change all default passwords
- [ ] Set strong `APP_KEY`
- [ ] Configure `APP_URL` correctly
- [ ] Enable HTTPS/SSL
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Enable log rotation
- [ ] Configure backup system
- [ ] Test file upload security
- [ ] Review database user permissions
- [ ] Set proper file permissions
- [ ] Queue worker berjalan (`queue:work --timeout=330` via Supervisor/systemd)
- [ ] Scheduler cron aktif (`schedule:run` tiap menit)
- [ ] `queue:restart` dijalankan setiap selesai deploy
- [ ] Tabel `queued_jobs` + `failed_jobs` termigrate; `retry_after(400) > timeout job(300)`
- [ ] `TRUSTED_PROXIES` diisi (atau kosong = fail-closed); JANGAN `*` di production
- [ ] `MAIL_TIMEOUT=10`; `APP_URL=https` (bukan localhost); timezone app = Asia/Jakarta
- [ ] `php artisan storage:link` OK + TIDAK ada PDF/dokumen sensitif di `public/storage`
  (hanya logo/avatar/poster/webp yang boleh public)
- [ ] Ekstensi PHP: gd, fileinfo, mbstring, dom, zip, pdo_mysql, openssl

### .env Production Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bkk.smkmutu.sch.id

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=bkk_production
DB_USERNAME=bkk_user
DB_PASSWORD=strong_password_here

# Mail (configure SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bkk.smkmutu.sch.id
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Proxy (kosong = jangan percaya proxy mana pun; isi IP LB/Cloudflare bila ada)
TRUSTED_PROXIES=

# SPA cookie (isi domain bila frontend pakai Sanctum cookie)
SANCTUM_STATEFUL_DOMAINS=

# SMTP: batas detik agar worker tidak gantung (jangan null)
MAIL_TIMEOUT=10
```

## 📝 MAINTENANCE MODE

### Enable Maintenance
```bash
php artisan down --message="System under maintenance" --retry=60
```

### Disable Maintenance
```bash
php artisan up
```

## 🔄 UPDATE PROCEDURE

### When Updating Application — P1-M04 DB SAFETY
```bash
# 0. PRE-MIGRATE BACKUP — WAJIB sebelum migrate production!
#    Jangan hardcode password di history; pakai .env atau --defaults-extra-file
#    Linux: mysqldump -u $DB_USERNAME -p"$DB_PASSWORD" $DB_DATABASE > backup_$(date +%Y%m%d_%H%M).sql
#    Windows: mysqldump -u %DB_USERNAME% -p%DB_PASSWORD% %DB_DATABASE% > backup_%date%.sql
#    Simpan backup di luar project, JANGAN commit, JANGAN auto-hapus backup lama.
# 1. Enable maintenance mode
php artisan down

# 2. Pull latest code (if using git)
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Run migrations — HANYA migrate --force, JANGAN migrate:fresh/refresh/db:wipe
php artisan migrate --force

# 5. Clear & rebuild cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6b. Restart queue worker agar memakai kode terbaru
php artisan queue:restart

# 7. Disable maintenance mode
php artisan up
```

## 🔙 BACKUP STRATEGY

### Database Backup
```bash
# Manual backup
mysqldump -u username -p bkk_smk_mutu > backup_$(date +%Y%m%d).sql

# Automated (add to crontab)
0 2 * * * mysqldump -u username -p password bkk_smk_mutu > /path/to/backups/backup_$(date +\%Y\%m\%d).sql
```

### File Backup
```bash
# Backup storage folder
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/

# Backup entire application
tar -czf app_backup_$(date +%Y%m%d).tar.gz --exclude='node_modules' --exclude='vendor' .
```

## 📊 MONITORING

### Log Files
```bash
# Application logs
tail -f storage/logs/laravel.log

# Web server logs
# Apache
tail -f /var/log/apache2/error.log
# Nginx
tail -f /var/log/nginx/error.log
```

### Health Check
Create route untuk health check:
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'timestamp' => now(),
    ]);
});
```

## 🐛 TROUBLESHOOTING

### Common Issues

#### 1. "500 Internal Server Error"
- Check storage permissions
- Check .env configuration
- Review error logs
- Clear cache: `php artisan cache:clear`

#### 2. "SQLSTATE[HY000] [1045] Access denied"
- Verify database credentials in `.env`
- Check MySQL user privileges
- Ensure database exists

#### 3. "Class not found"
- Run: `composer dump-autoload`
- Clear cache: `php artisan cache:clear`

#### 4. "Session store not set"
- Check SESSION_DRIVER in `.env`
- Run migrations for session table

#### 5. "The stream or file could not be opened"
- Fix permissions: `chmod -R 775 storage bootstrap/cache`
- Windows: Check folder write permissions

## 📱 MOBILE APP PREPARATION

Jika akan dibuat mobile app:
```bash
# Install Sanctum (sudah terinstall)
php artisan vendor:publish --provider="Laravel\Sanctum\ServiceProviderAlias"

# Configure CORS in config/cors.php
```

## 🎯 PERFORMANCE OPTIMIZATION

### Production Optimizations
```bash
# 1. Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000

# 2. Configure MySQL
# Add to my.cnf/my.ini
[mysqld]
innodb_buffer_pool_size=256M
query_cache_size=32M

# 3. Use Redis for cache (optional)
composer require predis/predis
# Update .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

## 📞 SUPPORT CONTACTS

### Development Team
- **Email**: dev@bkksmkmutu.com
- **Phone**: (0267) 123-4567
- **Website**: https://bkksmkmutu.sch.id

### Emergency Contacts
- **System Admin**: admin@bkksmkmutu.com
- **Database Admin**: dba@bkksmkmutu.com

---

## ✅ POST-DEPLOYMENT CHECKLIST

- [ ] Application is accessible via URL
- [ ] Database connection working
- [ ] File uploads working
- [ ] Email notifications working
- [ ] User registration working
- [ ] User login working
- [ ] All main features tested
- [ ] Admin panel accessible
- [ ] Security measures in place
- [ ] Backup system configured
- [ ] Monitoring tools setup
- [ ] SSL certificate installed
- [ ] Error pages customized
- [ ] Documentation updated
- [ ] Team training completed

---

**Deployment Date**: _______________
**Deployed By**: _______________
**Version**: 1.0.0
**Status**: ✅ Ready for Production
