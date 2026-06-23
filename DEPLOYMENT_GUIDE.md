# 🚀 BKK SMK MUTU - DEPLOYMENT GUIDE

## 📋 PREREQUISITES

### System Requirements
- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi
- Composer 2.x
- Node.js 18.x atau lebih tinggi (opsional, untuk build assets)
- Web Server (Apache/Nginx)

## 🔧 INSTALLATION STEPS

### 1. Clone & Setup
```bash
# Clone repository (jika dari git)
git clone [repository-url]
cd "BKK SMK MUTU"

# Copy environment file
copy .env.example .env
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
php artisan migrate

# Run seeders (untuk data awal)
php artisan db:seed
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
    DocumentRoot "D:/BKK SMK MUTU/public"
    
    <Directory "D:/BKK SMK MUTU/public">
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
    root /path/to/BKK SMK MUTU/public;

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

## 👤 DEFAULT USER ACCOUNTS

### Create Admin Account
```bash
php artisan tinker
```
```php
$user = \App\Models\User::create([
    'name' => 'Admin BKK',
    'email' => 'admin@bkk.smk.id',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'is_active' => true,
]);
```

### Create Test Accounts
```php
// Student
$student = \App\Models\User::create([
    'name' => 'Budi Santoso',
    'email' => 'budi@student.smk.id',
    'password' => bcrypt('password123'),
    'role' => 'student',
    'is_active' => true,
]);

\App\Models\Student::create([
    'user_id' => $student->id,
    'nisn' => '0012345678',
    'major' => 'Teknik Komputer dan Jaringan',
    'graduation_year' => 2024,
]);

// Company
$company = \App\Models\User::create([
    'name' => 'PT Teknologi Maju',
    'email' => 'hr@teknologimaju.com',
    'password' => bcrypt('password123'),
    'role' => 'company',
    'is_active' => true,
]);

\App\Models\Company::create([
    'user_id' => $company->id,
    'name' => 'PT Teknologi Maju',
    'industry' => 'Technology',
    'description' => 'Leading technology company in Indonesia',
    'is_verified' => true,
]);
```

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

### When Updating Application
```bash
# 1. Enable maintenance mode
php artisan down

# 2. Pull latest code (if using git)
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Run migrations
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
