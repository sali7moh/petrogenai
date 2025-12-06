@echo off
echo =====================================
echo PetrogenAI - Windows Setup Script
echo =====================================
echo.

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed!
    echo Please install Composer from: https://getcomposer.org/
    echo.
    pause
    exit /b 1
)

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Node.js is not installed!
    echo Please install Node.js from: https://nodejs.org/
    echo.
    pause
    exit /b 1
)

echo [1/8] Installing PHP dependencies...
call composer install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install PHP dependencies
    pause
    exit /b 1
)

echo.
echo [2/8] Installing Node.js dependencies...
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install Node.js dependencies
    pause
    exit /b 1
)

echo.
echo [3/8] Creating environment file...
if not exist .env (
    copy .env.example .env
    echo .env file created successfully
) else (
    echo .env file already exists, skipping...
)

echo.
echo [4/8] Generating application key...
call php artisan key:generate

echo.
echo [5/8] Creating storage directories...
if not exist "storage\app\attachments" mkdir "storage\app\attachments"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

echo.
echo [6/8] Building frontend assets...
call npm run build
if %ERRORLEVEL% NEQ 0 (
    echo [WARNING] Failed to build assets, you may need to run 'npm run build' manually
)

echo.
echo =====================================
echo Setup Complete!
echo =====================================
echo.
echo NEXT STEPS:
echo.
echo 1. Configure your database in .env file:
echo    - DB_DATABASE=petrogenai
echo    - DB_USERNAME=your_username
echo    - DB_PASSWORD=your_password
echo.
echo 2. Add your OpenAI API key in .env file:
echo    - OPENAI_API_KEY=sk-your-api-key-here
echo.
echo 3. Create the database:
echo    - CREATE DATABASE petrogenai;
echo.
echo 4. Run migrations:
echo    - php artisan migrate
echo.
echo 5. Start the development server:
echo    - php artisan serve
echo.
echo 6. Visit: http://localhost:8000
echo.
echo =====================================
pause
