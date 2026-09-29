# Testing lokal dari laptop (LAN) - BKK SMK MUTU
# Usage: klik kanan > Run with PowerShell, atau: powershell -ExecutionPolicy Bypass -File scripts/testing-lokal.ps1
$ErrorActionPreference = "Stop"
$root = Split-Path $PSScriptRoot -Parent
Set-Location $root

Write-Host "=== BKK SMK MUTU - Testing Laptop (Lokal/LAN) ===" -ForegroundColor Cyan

# 1. Cek PHP
try { php -v | Select-Object -First 1 } catch { Write-Host "[!] PHP tidak ditemukan di PATH" -ForegroundColor Red; exit 1 }

# 2. Bersihkan cache agar APP_URL / config baru kebaca (penting untuk testing)
Write-Host "[1/3] Clear cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear | Out-Null

# 3. Info IP LAN
Write-Host "[2/3] IP laptop kamu:" -ForegroundColor Yellow
Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -notlike "127.*" -and $_.IPAddress -notlike "169.*" } | ForEach-Object {
  Write-Host ("   http://" + $_.IPAddress + ":8000  (" + $_.InterfaceAlias + ")") -ForegroundColor Green
}
Write-Host "   http://localhost:8000  (laptop ini)" -ForegroundColor Green

# 4. Cek DB cepat (tidak fatal kalau gagal)
Write-Host "[3/3] Cek database..." -ForegroundColor Yellow
php artisan about --only=environment

Write-Host ""
Write-Host "Jalankan server..." -ForegroundColor Cyan
Write-Host "Buka di HP/laptop lain satu WiFi: http://192.168.1.180:8000" -ForegroundColor White
Write-Host "Stop: tekan CTRL+C" -ForegroundColor DarkGray
Write-Host ""

# --host=0.0.0.0 = bisa diakses dari LAN, bukan cuma localhost
php artisan serve --host=0.0.0.0 --port=8000
