@echo off
title KidsRoutine - Servidor local
color 0B

echo.
echo  ==========================================
echo   KidsRoutine - Servidor local puerto 8000
echo  ==========================================
echo.
echo  Accede en: http://localhost:8000
echo  Demo: demo@kidsroutine.com / demo123
echo.

set PHP=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe
cd /d C:\laragon\www\kids_routine

%PHP% artisan serve --host=0.0.0.0 --port=8000
pause
