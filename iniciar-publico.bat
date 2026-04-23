@echo off
title KidsRoutine - Servidor publico
color 0A

echo.
echo  ==========================================
echo   KidsRoutine - Iniciando acceso publico
echo  ==========================================
echo.

set PHP=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe
set NODE=C:\laragon\bin\nodejs\node-v24.14.0-win-x64
set NGROK=C:\laragon\bin\ngrok\ngrok.exe
set APP_DIR=C:\laragon\www\kids_routine

cd /d %APP_DIR%

echo [1/2] Arrancando servidor Laravel en puerto 8000...
start "KidsRoutine Server" cmd /k "%PHP% artisan serve --host=0.0.0.0 --port=8000"

timeout /t 3 /nobreak > nul

echo [2/2] Abriendo tunel publico con ngrok...
echo.
echo  Si es la primera vez, necesitas una cuenta gratuita en ngrok.com
echo  Una vez registrado, ejecuta este comando UNA SOLA VEZ:
echo  %NGROK% config add-authtoken TU_TOKEN_AQUI
echo.
echo  Tu URL publica aparecera en la ventana de ngrok (linea Forwarding)
echo  Compartela con quien quieras. Ejemplo: https://xxxx.ngrok-free.app
echo.

%NGROK% http 8000

pause
