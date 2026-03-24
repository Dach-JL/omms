@echo off
echo ========================================
echo Laravel Membership Management System
echo Setup and Run Script
echo ========================================
echo.

REM Check if PHP is available
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHP is not installed or not in PATH!
    echo Please install XAMPP, WAMP, or PHP from https://windows.php.net/download/
    pause
    exit /b 1
)

echo [1/8] Checking PHP version...
php -v | findstr /C:"PHP"
echo.

REM Step 1: Install Composer dependencies
echo [2/8] Installing Composer dependencies...
if exist composer.phar (
    php composer.phar install --no-interaction
) else (
    composer install --no-interaction
)
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo.

REM Step 2: Check .env file
echo [3/8] Checking environment configuration...
if not exist .env (
    echo Creating .env file from .env.example...
    copy .env.example .env
)
echo.

REM Step 3: Generate app key if missing
echo [4/8] Generating application key...
findstr /C:"APP_KEY=" .env | findstr /C:"base64:" >nul
if %ERRORLEVEL% NEQ 0 (
    echo Generating new APP_KEY...
    if exist composer.phar (
        php composer.phar artisan key:generate
    ) else (
        php artisan key:generate
    )
) else (
    echo APP_KEY already exists.
)
echo.

REM Step 4: Install npm dependencies
echo [5/8] Installing npm dependencies...
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo WARNING: npm install failed. Continuing anyway...
)
echo.

REM Step 5: Create SQLite database
echo [6/8] Setting up SQLite database...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo SQLite database created.
) else (
    echo SQLite database already exists.
)
echo.

REM Step 6: Run migrations
echo [7/8] Running database migrations...
if exist composer.phar (
    php composer.phar artisan migrate --force
) else (
    php artisan migrate --force
)
if %ERRORLEVEL% NEQ 0 (
    echo WARNING: Migration failed. You may need to configure database manually.
)
echo.

REM Step 7: Seed plans
echo Seeding plan data...
if exist composer.phar (
    php composer.phar artisan db:seed --class=PlanSeeder
) else (
    php artisan db:seed --class=PlanSeeder
)
echo.

REM Step 8: Create storage link
echo Creating storage symbolic link...
if exist composer.phar (
    php composer.phar artisan storage:link
) else (
    php artisan storage:link
)
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Starting Laravel development server...
echo.
echo Application will be available at:
echo http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start Laravel server
if exist composer.phar (
    php composer.phar artisan serve
) else (
    php artisan serve
)

pause
