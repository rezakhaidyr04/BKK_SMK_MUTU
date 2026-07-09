# ⚡ QUICK START GUIDE - Mutu Career Center

## 🎯 Cara Cepat Menjalankan Project

### 1️⃣ Setup Database (2 menit)
```bash
# Buka MySQL/phpMyAdmin dan buat database
CREATE DATABASE bkk_smk_mutu;

# Atau via command line
mysql -u root -p -e "CREATE DATABASE bkk_smk_mutu;"
```

### 2️⃣ Configure Environment (1 menit)
Edit file `.env`:
```env
DB_DATABASE=bkk_smk_mutu
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3️⃣ Install & Migrate (3 menit)
```bash
# Install dependencies
composer install

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 4️⃣ Create Admin User (1 menit)
```bash
php artisan tinker
```
Paste this code:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@bkk.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'is_active' => true,
]);
exit
```

### 5️⃣ Run Server (30 detik)
```bash
php artisan serve
```

### 6️⃣ Open Browser
```
http://localhost:8000
```

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bkk.com | admin123 |

**⚠️ PENTING: Ganti password default setelah login pertama!**

---

## 📍 Main Routes/URLs

### Public Pages
- **Home**: `http://localhost:8000/`
- **Job Listing**: `http://localhost:8000/jobs`
- **Events**: `http://localhost:8000/events`
- **News**: `http://localhost:8000/news`

### Auth Pages
- **Login**: `http://localhost:8000/login`
- **Register**: `http://localhost:8000/register`

### Dashboard (After Login)
- **Dashboard**: `http://localhost:8000/dashboard`
- **My Applications**: `http://localhost:8000/applications`
- **Saved Jobs**: `http://localhost:8000/bookmarks`
- **CV Builder**: `http://localhost:8000/cv/builder`
- **Certificates**: `http://localhost:8000/certificates`
- **Messages**: `http://localhost:8000/messages`

### Admin (After Login as Admin)
- **Admin Dashboard**: `http://localhost:8000/dashboard`
- **Manage Users**: `http://localhost:8000/admin/users`
- **Manage Companies**: `http://localhost:8000/admin/companies`
- **Manage Jobs**: `http://localhost:8000/admin/jobs`
- **Reports**: `http://localhost:8000/admin/reports`

---

## 🎨 Testing UI Components

### Create Test Data (Optional)

#### Create Student User
```bash
php artisan tinker
```
```php
$user = \App\Models\User::create([
    'name' => 'Budi Santoso',
    'email' => 'budi@student.com',
    'password' => bcrypt('student123'),
    'role' => 'student',
    'is_active' => true,
]);

\App\Models\Student::create([
    'user_id' => $user->id,
    'nisn' => '0012345678',
    'major' => 'Teknik Komputer dan Jaringan',
    'graduation_year' => 2024,
    'address' => 'Cikampek, Jawa Barat',
]);
exit
```

#### Create Company & Job
```php
$company = \App\Models\User::create([
    'name' => 'PT Tech Indonesia',
    'email' => 'hr@techindonesia.com',
    'password' => bcrypt('company123'),
    'role' => 'company',
    'is_active' => true,
]);

$comp = \App\Models\Company::create([
    'user_id' => $company->id,
    'name' => 'PT Tech Indonesia',
    'industry' => 'Technology',
    'description' => 'Leading technology company',
    'website' => 'https://techindonesia.com',
    'address' => 'Jakarta Selatan',
    'is_verified' => true,
]);

\App\Models\Job::create([
    'company_id' => $comp->id,
    'title' => 'Frontend Developer',
    'position' => 'Frontend Developer',
    'location' => 'Jakarta',
    'job_type' => 'full_time',
    'salary_min' => 5000000,
    'salary_max' => 8000000,
    'description' => 'We are looking for a talented Frontend Developer to join our team. You will be responsible for building modern web applications using React and Vue.js.',
    'qualifications' => 'Bachelor degree in Computer Science or related field. Minimum 1 year experience with React or Vue.js. Strong knowledge of HTML, CSS, JavaScript.',
    'benefits' => 'Health insurance, Annual bonus, Training budget, Work from home flexibility',
    'deadline' => now()->addDays(30),
    'status' => 'active',
]);
exit
```

---

## 🔧 Common Commands

### Development
```bash
# Start development server
php artisan serve

# Watch logs
php artisan tail

# Clear all cache
php artisan optimize:clear

# Run migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Fresh migration (reset database)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

### Debugging
```bash
# Check routes
php artisan route:list

# Check configuration
php artisan config:show

# Tinker (Laravel REPL)
php artisan tinker

# Check environment
php artisan about
```

---

## 📂 Important File Locations

### Controllers
```
app/Http/Controllers/
├── DashboardController.php
├── JobController.php
├── ApplicationController.php
├── BookmarkController.php
├── CvBuilderController.php
├── CertificateController.php
├── EventController.php
├── NewsController.php
└── MessageController.php
```

### Views
```
resources/views/
├── layouts/
│   ├── app.blade.php
│   └── navigation.blade.php
├── dashboard/
│   ├── student.blade.php
│   └── admin.blade.php
├── jobs/
│   ├── index.blade.php
│   └── show.blade.php
└── applications/
    └── index.blade.php
```

### Models
```
app/Models/
├── User.php
├── Job.php
├── Application.php
├── Company.php
├── Student.php
├── Alumni.php
└── ...
```

---

## 🎯 Feature Testing Checklist

### ✅ Test These Features
- [ ] **Registration**: Create new student account
- [ ] **Login**: Login with created account
- [ ] **Browse Jobs**: View job listings
- [ ] **Job Search**: Search and filter jobs
- [ ] **Job Detail**: View job details
- [ ] **Apply Job**: Submit application
- [ ] **My Applications**: View application status
- [ ] **Bookmark Job**: Save job for later
- [ ] **Profile**: Update profile information
- [ ] **CV Builder**: Generate CV (placeholder)
- [ ] **Certificates**: Upload certificate
- [ ] **Admin Dashboard**: Login as admin and view stats

---

## 🐛 Quick Troubleshooting

### Problem: "500 Error"
```bash
# Check logs
tail -n 50 storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Problem: "Database Connection Error"
1. Check `.env` file
2. Verify MySQL is running
3. Check database exists
4. Verify credentials

### Problem: "Class not found"
```bash
composer dump-autoload
php artisan cache:clear
```

### Problem: "Permission Denied"
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows: Check folder permissions in Properties
```

---

## 📞 Need Help?

### Documentation
- 📚 **Full Documentation**: `TRANSFORMATION_SUMMARY.md`
- 🚀 **Deployment Guide**: `DEPLOYMENT_GUIDE.md`
- 🎓 **AI Skills Reference**: `AI_SKILLS.md`

### Support
- 📧 **Email**: dev@bkksmkmutu.com
- 🌐 **Website**: https://bkksmkmutu.sch.id

---

## 🎉 You're Ready!

Selamat! Anda sekarang sudah siap untuk:
- ✅ Develop fitur baru
- ✅ Customize UI/UX
- ✅ Deploy ke production
- ✅ Test semua features

**Happy Coding! 🚀**

---

**Last Updated**: {{ date('Y-m-d') }}
**Version**: 1.0.0
**Status**: ✅ Production Ready
