@echo off
REM Klik 2x untuk testing publik via Cloudflare Tunnel
powershell -ExecutionPolicy Bypass -File "%~dp0scripts\testing-publik.ps1"
pause
