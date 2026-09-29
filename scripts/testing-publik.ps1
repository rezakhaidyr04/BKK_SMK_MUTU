# Testing publik dari laptop via Cloudflare Tunnel - BKK SMK MUTU
# Usage: powershell -ExecutionPolicy Bypass -File scripts/testing-publik.ps1
# Hasil: dapat link https://xxx.trycloudflare.com bisa dibuka dari mana aja (selama laptop nyala)
$ErrorActionPreference = "Stop"
$root = Split-Path $PSScriptRoot -Parent
Set-Location $root

Write-Host "=== BKK SMK MUTU - Testing Publik (Cloudflare Tunnel) ===" -ForegroundColor Cyan

# 1. Cek PHP
try { php -v | Select-Object -First 1 } catch { Write-Host "[!] PHP tidak ditemukan" -ForegroundColor Red; exit 1 }

# 2. Clear cache (wajib, karena domain tunnel random tiap run)
Write-Host "[1/4] Clear Laravel cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear | Out-Null
php artisan storage:link 2>$null; if (-not $?) { Write-Host "    storage:link sudah ada, skip" }

# 3. Cek / install cloudflared
Write-Host "[2/4] Cek cloudflared..." -ForegroundColor Yellow
$cfExe = $null
$cf = Get-Command cloudflared -ErrorAction SilentlyContinue
if ($cf) { $cfExe = $cf.Source }
# Fallback path installer MSI (winget) - PATH kadang belum refresh
if (-not $cfExe) {
  $fallback = "C:\Program Files (x86)\cloudflared\cloudflared.exe"
  if (Test-Path $fallback) { $cfExe = $fallback }
}
if (-not $cfExe) {
  $fallback2 = "C:\Program Files\cloudflared\cloudflared.exe"
  if (Test-Path $fallback2) { $cfExe = $fallback2 }
}
if (-not $cfExe) {
  Write-Host "    cloudflared belum ada, install via winget..." -ForegroundColor Yellow
  try {
    winget install --id Cloudflare.cloudflared -e --accept-source-agreements --accept-package-agreements
    # Refresh PATH untuk sesi ini
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
  } catch {
    Write-Host "[!] winget gagal. Download manual:" -ForegroundColor Red
    Write-Host "    https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/downloads/"
    Write-Host "    Taruh cloudflared.exe di folder ini lalu ulangi."
    exit 1
  }
  $cf = Get-Command cloudflared -ErrorAction SilentlyContinue
  if ($cf) { $cfExe = $cf.Source }
  elseif (Test-Path "C:\Program Files (x86)\cloudflared\cloudflared.exe") { $cfExe = "C:\Program Files (x86)\cloudflared\cloudflared.exe" }
  elseif (Test-Path "C:\Program Files\cloudflared\cloudflared.exe") { $cfExe = "C:\Program Files\cloudflared\cloudflared.exe" }
  else { Write-Host "[!] cloudflared masih tidak ketemu setelah install. Tutup-buka terminal lalu ulangi." -ForegroundColor Red; exit 1 }
}
Write-Host "    Pakai: $cfExe" -ForegroundColor DarkGray
& $cfExe --version

# 4. Jalankan Laravel di background (window baru), tunnel di window ini
Write-Host "[3/4] Menyalakan Laravel di http://localhost:8000 ..." -ForegroundColor Yellow
# Tutup proses lama di port 8000 kalau ada (biar tidak bentrok)
$old = Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue | Select-Object -ExpandProperty OwningProcess -Unique
if ($old) { Write-Host "    Port 8000 dipakai, matikan PID: $old"; Stop-Process -Id $old -Force -ErrorAction SilentlyContinue; Start-Sleep 2 }

Start-Process powershell -ArgumentList "-NoExit","-Command","cd '$root'; Write-Host '=== Laravel Serve (jangan ditutup) ===' -ForegroundColor Cyan; php artisan serve --host=127.0.0.1 --port=8000"
Write-Host "    Tunggu 4 detik..." -ForegroundColor DarkGray
Start-Sleep 4

Write-Host "[4/4] Membuka tunnel publik..." -ForegroundColor Yellow
Write-Host ""
Write-Host "Nanti muncul link seperti:" -ForegroundColor Green
Write-Host "   https://random-nama.trycloudflare.com" -ForegroundColor Green
Write-Host "Bagikan link itu untuk testing. Laptop harus tetap nyala." -ForegroundColor White
Write-Host "Stop: CTRL+C di kedua window." -ForegroundColor DarkGray
Write-Host ""

# Quick tunnel, tanpa login Cloudflare, cocok untuk testing doang
& $cfExe tunnel --url http://localhost:8000
