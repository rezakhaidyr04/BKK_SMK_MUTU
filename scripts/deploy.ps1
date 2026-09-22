# Deploy script untuk BKK SMK MUTU - Windows PowerShell
# Usage: powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1
$ErrorActionPreference = "Stop"
Write-Host "=== BKK SMK MUTU Deploy (Windows) ===" -ForegroundColor Cyan

if (-not (Test-Path ".env")) {
  Write-Host "[!] .env tidak ditemukan. Copy dari .env.production.example" -ForegroundColor Red
  Write-Host "    Copy-Item .env.production.example .env"
  Write-Host "    lalu isi APP_KEY, DB_*, MAIL_*"
  exit 1
}

Write-Host "[1/7] Composer install..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction

Write-Host "[2/7] NPM build..." -ForegroundColor Yellow
npm ci
npm run build

Write-Host "[3/7] Laravel optimize..." -ForegroundColor Yellow
# P1-H07: jangan regenerate APP_KEY jika sudah ada
$envContent = Get-Content ".env" -Raw -ErrorAction SilentlyContinue
if (-not $envContent -or $envContent -notmatch "(?m)^APP_KEY=.+") {
  php artisan key:generate --force
  Write-Host "APP_KEY generated (baru)"
} else {
  Write-Host "APP_KEY sudah ada — skip (jangan overwrite)"
}
Write-Host "[!] Pre-migrate safety: backup database sebelum migrate!" -ForegroundColor Red
php artisan migrate --force
php artisan storage:link; if (-not $?) { Write-Host "storage:link skip" }

Write-Host "[4/7] Cache config/route/view..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "[5/7] Permissions..." -ForegroundColor Yellow
Write-Host "    Windows: pastikan storage/ writable (IIS_IUSRS / Authenticated Users)"

Write-Host "[6/7] Queue & Scheduler reminder..." -ForegroundColor Yellow
Write-Host "    - Task Scheduler: php artisan schedule:run tiap menit"
Write-Host "    - Queue worker: php artisan queue:work --sleep=3 --tries=3"

Write-Host "[7/7] Health check..." -ForegroundColor Yellow
php artisan about --only=environment

Write-Host ""
Write-Host "=== Deploy selesai ===" -ForegroundColor Green
Write-Host "Cek: APP_ENV=production APP_DEBUG=false APP_URL=https://yourdomain.com"
Write-Host "Jangan lupa: rotasi MAIL_PASSWORD dan set SESSION_SECURE_COOKIE=true"
