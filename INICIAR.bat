@echo off
title Paroquia Online
cd /d "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0iniciar.ps1"
echo.
echo O servidor foi encerrado.
pause
