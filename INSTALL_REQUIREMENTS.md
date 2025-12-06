# 🚀 Quick Installation Guide for PetrogenAI

## Required Software Installation

You need to install 4 tools before running the app. Follow these steps:

---

## 📥 Step 1: Install PHP 8.2

1. **Download PHP:**
   - Go to: https://windows.php.net/download/
   - Download: **PHP 8.2.14 VS16 x64 Thread Safe** (ZIP file)
   - File: `php-8.2.14-Win32-vs16-x64.zip`

2. **Extract PHP:**
   - Extract the ZIP to `C:\php`
   - You should have `C:\php\php.exe`

3. **Configure PHP:**
   - Copy `C:\php\php.ini-development` to `C:\php\php.ini`
   - Open `C:\php\php.ini` in Notepad
   - Find and uncomment these lines (remove the `;` at the start):
     ```
     extension=mbstring
     extension=openssl
     extension=pdo_mysql
     extension=curl
     extension=fileinfo
     ```

4. **Add PHP to PATH:**
   - Press `Win + X` → System
   - Click "Advanced system settings"
   - Click "Environment Variables"
   - Under "System variables", find "Path"
   - Click "Edit" → "New"
   - Add: `C:\php`
   - Click OK on all dialogs

---

## 📥 Step 2: Install Composer

1. **Download Composer:**
   - Go to: https://getcomposer.org/Composer-Setup.exe
   - Run the downloaded `Composer-Setup.exe`
   - It will detect PHP automatically
   - Follow the installation wizard
   - Click "Install"

---

## 📥 Step 3: Install Node.js

1. **Download Node.js:**
   - Go to: https://nodejs.org/
   - Download the **LTS version** (20.x)
   - File: `node-v20.x.x-x64.msi`

2. **Install Node.js:**
   - Run the downloaded `.msi` file
   - Follow the installation wizard
   - Check "Automatically install necessary tools"
   - Click "Next" → "Install"

---

## 📥 Step 4: Install MySQL (Optional - can use SQLite)

### Option A: Install MySQL (Full database)

1. **Download MySQL:**
   - Go to: https://dev.mysql.com/downloads/installer/
   - Download: **MySQL Installer for Windows**
   - Choose "mysql-installer-community"

2. **Install MySQL:**
   - Run the installer
   - Choose "Custom" installation
   - Select "MySQL Server" only
   - Follow wizard, set root password
   - Remember your password!

### Option B: Use SQLite (Simpler, no installation)

- No installation needed
- We'll configure the app to use SQLite instead

---

## ✅ Verify Installation

After installing everything, **close and reopen PowerShell** and run:

```powershell
php --version
composer --version
node --version
npm --version
```

You should see version numbers for all commands.

---

## 🚀 Next Steps

Once everything is installed:

```powershell
# Navigate to project
cd "c:\Users\salih\OneDrive\Desktop\SYSMNT\AI\Ai Projects\PetrogenAI\PetrogenAi"

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
Copy-Item .env.example .env

# Generate app key
php artisan key:generate

# Build frontend assets
npm run build

# Edit .env file and add:
#   - Your OpenAI API key
#   - Database settings (or use SQLite)

# Run migrations
php artisan migrate

# Start the server
php artisan serve
```

Then visit: **http://localhost:8000**

---

## 🆘 Need Help?

If you encounter issues:

1. **PHP not found** → Make sure you added `C:\php` to PATH and restarted PowerShell
2. **Composer not found** → Reinstall Composer-Setup.exe
3. **Node not found** → Reinstall Node.js MSI
4. **MySQL issues** → Use SQLite instead (edit `.env` file)

---

## 📝 Quick SQLite Setup (No MySQL needed)

If you want to skip MySQL installation:

1. Open `.env` file
2. Change:
   ```
   DB_CONNECTION=mysql
   ```
   to:
   ```
   DB_CONNECTION=sqlite
   ```
3. Create database file:
   ```powershell
   New-Item database/database.sqlite
   ```
4. Run migrations:
   ```powershell
   php artisan migrate
   ```

---

## 🎯 Summary

**Download Links:**
- PHP: https://windows.php.net/download/
- Composer: https://getcomposer.org/Composer-Setup.exe
- Node.js: https://nodejs.org/
- MySQL: https://dev.mysql.com/downloads/installer/ (optional)

**After Installation:**
1. Close and reopen PowerShell
2. Run `composer install`
3. Run `npm install`
4. Configure `.env` file
5. Run `php artisan migrate`
6. Run `php artisan serve`

You're ready to go! 🎉
