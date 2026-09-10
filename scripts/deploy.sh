#!/bin/bash
# Deploy script untuk BKK SMK MUTU - Production
# Usage: bash scripts/deploy.sh  atau  ./scripts/deploy.sh
set -e

echo "=== BKK SMK MUTU Deploy ==="

if [ ! -f .env ]; then
  echo "[!] .env tidak ditemukan. Copy dari .env.production.example"
  echo "    cp .env.production.example .env"
  echo "    lalu isi APP_KEY, DB_*, MAIL_*"
  exit 1
fi

echo "[1/7] Composer install (no-dev, optimized)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "[2/7] NPM build..."
npm ci
npm run build

echo "[3/7] Laravel optimize..."
php artisan key:generate --force 2>/dev/null || echo "APP_KEY sudah ada"
php artisan migrate --force
php artisan storage:link || true

echo "[4/7] Cache config/route/view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[5/7] Permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || echo "chmod skip (Windows)"

echo "[6/7] Queue & Scheduler reminder..."
echo "    - Pastikan cron: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1"
echo "    - Pastikan queue worker: php artisan queue:work --sleep=3 --tries=3 --max-time=3600 (via Supervisor/systemd)"

echo "[7/7] Health check..."
php artisan about --only=environment || true

echo ""
echo "=== Deploy selesai ==="
echo "Cek: APP_ENV=production APP_DEBUG=false APP_URL=https://yourdomain.com"
echo "Jangan lupa: rotasi MAIL_PASSWORD dan set SESSION_SECURE_COOKIE=true"
