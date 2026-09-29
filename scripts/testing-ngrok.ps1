# Testing publik dari laptop via ngrok - BKK SMK MUTU
# Hasil: dapat link https://xxx.ngrok-free.app bisa dibuka dari mana aja
# Syarat: daftar gratis di https://dashboard.ngrok.com lalu ambil Authtoken
$ErrorActionPreference = "Stop"
$root = Split-Path $PSScriptRoot -Parent
Set-Location $root

Write-Host "=== BKK SMK MUTU - Testing Publik via ngrok ===" -ForegroundColor Cyan

# 1. Cek PHP
try { php -v | Select-Object -First 1 } catch { Write-Host "[!] PHP tidak ditemukan" -ForegroundColor Red; exit 1 }

# 2. Clear cache (wajib karena domain ngrok random)
Write-Host "[1/4] Clear Laravel cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear | Out-Null
php artisan storage:link 2>$null

# 3. Cek ngrok
Write-Host "[2/4] Cek ngrok..." -ForegroundColor Yellow
$ngrokExe = $null
$ng = Get-Command ngrok -ErrorAction SilentlyContinue
if ($ng) { $ngrokExe = $ng.Source }
if (-not $ngrokExe) {
  $fb = "$env:LOCALAPPDATA\Microsoft\WinGet\Links\ngrok.exe"
  if (Test-Path $fb) { $ngrokExe = $fb }
}
if (-not $ngrokExe) {
  Write-Host "    ngrok belum ada, install via winget..." -ForegroundColor Yellow
  winget install --id Ngrok.Ngrok -e --accept-source-agreements --accept-package-agreements
  $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
  $ng = Get-Command ngrok -ErrorAction SilentlyContinue
  if ($ng) { $ngrokExe = $ng.Source }
  elseif (Test-Path "$env:LOCALAPPDATA\Microsoft\WinGet\Links\ngrok.exe") { $ngrokExe = "$env:LOCALAPPDATA\Microsoft\WinGet\Links\ngrok.exe" }
  else { Write-Host "[!] ngrok masih tidak ketemu. Tutup-buka terminal lalu ulangi." -ForegroundColor Red; exit 1 }
}
Write-Host "    Pakai: $ngrokExe" -ForegroundColor DarkGray
& $ngrokExe version

# 4. Cek authtoken (ngrok wajib login, beda dengan cloudflared)
Write-Host "[3/4] Cek authtoken ngrok..." -ForegroundColor Yellow
$cfg1 = "$env:USERPROFILE\.ngrok2\ngrok.yml"
$cfg2 = "$env:LOCALAPPDATA\ngrok\ngrok.yml"
$hasToken = $false
foreach ($c in @($cfg1,$cfg2)) {
  if (Test-Path $c -ErrorAction SilentlyContinue) {
    $txt = Get-Content $c -Raw -ErrorAction SilentlyContinue
    if ($txt -match "authtoken\s*:\s*[A-Za-z0-9_\-]{10,}") { $hasToken = $true }
  }
}
if (-not $hasToken) {
  Write-Host ""
  Write-Host "ngrok butuh Authtoken gratis (sekali saja):" -ForegroundColor White
  Write-Host "  1. Daftar: https://dashboard.ngrok.com/signup" -ForegroundColor Gray
  Write-Host "  2. Ambil token: https://dashboard.ngrok.com/get-started/your-authtoken" -ForegroundColor Gray
  Write-Host "  3. Paste token di bawah" -ForegroundColor Gray
  Write-Host ""
  $token = Read-Host "Paste Authtoken ngrok"
  if (-not $token) { Write-Host "[!] Token kosong, batal." -ForegroundColor Red; exit 1 }
  & $ngrokExe config add-authtoken $token
}

# 5. Jalankan Laravel window baru, ngrok di window ini
Write-Host "[4/4] Menyalakan Laravel..." -ForegroundColor Yellow
$old = Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue | Where-Object { $_.State -eq "Listen" } | Select-Object -ExpandProperty OwningProcess -Unique
if ($old) { Write-Host "    Port 8000 dipakai PID $old, matikan..."; Stop-Process -Id $old -Force -ErrorAction SilentlyContinue; Start-Sleep 2 }

Start-Process powershell -ArgumentList "-NoExit","-Command","cd '$root'; Write-Host '=== Laravel Serve (jangan ditutup) ===' -ForegroundColor Cyan; php artisan serve --host=127.0.0.1 --port=8000"
Start-Sleep 4

Write-Host ""
Write-Host "Link ngrok muncul di bawah seperti https://xxx.ngrok-free.app" -ForegroundColor Green
Write-Host "Bagikan link itu. Laptop harus tetap nyala. Stop: CTRL+C." -ForegroundColor White
Write-Host ""
& $ngrokExe http 8000
