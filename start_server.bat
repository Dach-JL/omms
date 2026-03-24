@echo off
echo ========================================
echo Starting Laravel Membership System
echo ========================================
echo.

REM Check PHP
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHP not found! Please install XAMPP/WAMP or add PHP to PATH.
    pause
    exit /b 1
)

echo Starting Laravel development server...
echo.
echo Application will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.

REM Start server
if exist composer.phar (
    php composer.phar artisan serve
) else (
    php artisan serve
)

pause
