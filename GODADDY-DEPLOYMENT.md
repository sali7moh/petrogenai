# GoDaddy Hosting Deployment Guide for PetrogenAI

## 🎯 Overview

This guide will help you deploy your PetrogenAI Laravel application to GoDaddy hosting (cPanel/Shared Hosting).

---

## 📋 Prerequisites

### What You Need:
1. ✅ GoDaddy cPanel Hosting Account
2. ✅ Domain: petrogen.ai (already configured)
3. ✅ cPanel access credentials
4. ✅ GitHub repository: https://github.com/sali7moh/petrogenai
5. ✅ OpenAI API Key
6. ✅ SSH access (if available) OR File Manager access

### Check Your Hosting Plan:
- **Minimum PHP Version**: 8.1 (Your app uses PHP 8.5, compatible with 8.1+)
- **Database**: MySQL available
- **Storage**: At least 500MB free space
- **Composer**: Access to Composer (usually available via SSH)

---

## 🚀 Deployment Steps

### Step 1: Access cPanel

1. Go to your GoDaddy account
2. Navigate to **My Products** → **Web Hosting** → **Manage**
3. Click **cPanel Admin** button
4. You'll be redirected to your cPanel dashboard

---

### Step 2: Configure PHP Version

1. In cPanel, search for **"MultiPHP Manager"** or **"Select PHP Version"**
2. Select your domain: `petrogen.ai`
3. Choose **PHP 8.1** or higher (8.2 or 8.3 if available)
4. Click **Apply**

#### Enable Required PHP Extensions:
In the same PHP settings page, enable these extensions:
- ✅ `pdo_mysql`
- ✅ `mbstring`
- ✅ `openssl`
- ✅ `curl`
- ✅ `fileinfo`
- ✅ `json`
- ✅ `zip`

---

### Step 3: Create MySQL Database

1. In cPanel, find **"MySQL® Databases"**
2. Create a new database:
   - **Database Name**: `petrogen_ai` (cPanel will prefix it with your username)
   - Click **Create Database**
3. Create a database user:
   - **Username**: `petrogen_user`
   - **Password**: Generate a strong password (save it!)
   - Click **Create User**
4. Add user to database:
   - Select the database: `petrogen_ai`
   - Select the user: `petrogen_user`
   - Grant **ALL PRIVILEGES**
   - Click **Add**

**Save these credentials:**
```
Database Name: [your_cpanel_username]_petrogen_ai
Database User: [your_cpanel_username]_petrogen_user
Database Password: [the_password_you_generated]
Database Host: localhost
```

---

### Step 4: Upload Files to Server

#### Option A: Using Git (Recommended - If SSH is available)

1. In cPanel, open **"Terminal"** or connect via SSH client
2. Navigate to your home directory:
   ```bash
   cd ~
   ```

3. Clone your repository:
   ```bash
   git clone https://github.com/sali7moh/petrogenai.git
   ```

4. Move files to the correct location:
   ```bash
   # GoDaddy usually uses public_html for the web root
   # We'll move Laravel's public folder content there
   
   # First, backup default files
   mv public_html public_html_backup
   
   # Move Laravel public folder to public_html
   mv petrogenai/public public_html
   
   # Move the rest of Laravel to a safe location
   mkdir laravel_app
   mv petrogenai/* laravel_app/
   mv petrogenai/.* laravel_app/ 2>/dev/null
   
   # Update public_html/index.php to point to correct location
   ```

#### Option B: Using File Manager (No SSH required)

1. On your local computer, compress your project:
   - Open PowerShell in your project folder
   - Run: `Compress-Archive -Path * -DestinationPath petrogenai.zip`

2. In cPanel, open **"File Manager"**
3. Navigate to your home directory (usually `/home/yourusername/`)
4. Click **Upload** and upload `petrogenai.zip`
5. Right-click the zip file → **Extract**
6. Follow the file structure setup (see below)

---

### Step 5: Set Up File Structure

GoDaddy's web root is `public_html`. Laravel needs to be set up properly:

```
/home/yourusername/
├── laravel_app/              ← Laravel application files (NOT public)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
└── public_html/              ← Web accessible folder
    ├── build/
    ├── logo.svg
    ├── favicon.svg
    ├── index.php             ← Modified to point to ../laravel_app
    └── .htaccess
```

#### Update public_html/index.php:

Using File Manager or SSH, edit `/home/yourusername/public_html/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Point to Laravel app outside public_html
require __DIR__.'/../laravel_app/vendor/autoload.php';

$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

---

### Step 6: Configure Environment (.env)

1. Navigate to `/home/yourusername/laravel_app/`
2. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

3. Edit `.env` file with your production settings:

```env
APP_NAME="PetrogenAI"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://petrogen.ai

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_username_petrogen_ai
DB_USERNAME=your_cpanel_username_petrogen_user
DB_PASSWORD=your_database_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# OpenAI Configuration
OPENAI_API_KEY=sk-proj-YOUR-ACTUAL-API-KEY-HERE
OPENAI_ORGANIZATION=
OPENAI_REQUEST_TIMEOUT=30
OPENAI_MODEL=gpt-4-turbo-preview
```

**Important:** Replace:
- `your_cpanel_username_petrogen_ai` with actual database name
- `your_cpanel_username_petrogen_user` with actual database user
- `your_database_password_here` with actual database password
- `sk-proj-YOUR-ACTUAL-API-KEY-HERE` with your OpenAI API key

---

### Step 7: Install Dependencies & Configure

#### If SSH is available:

```bash
cd ~/laravel_app

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Set up storage permissions
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# Run database migrations
php artisan migrate --force

# Create admin user
php artisan db:seed --class=AdminUserSeeder

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### If NO SSH (Using cPanel only):

1. Download and install Composer locally if not available on server
2. Use cPanel's **PHP Composer** tool (if available)
3. Or, install dependencies locally and upload the `vendor` folder

**For application key:**
1. Generate locally: `php artisan key:generate`
2. Copy the key from `.env`
3. Paste it in your server's `.env` file

**For database migration:**
1. Export your local SQLite data or
2. Use cPanel's phpMyAdmin to manually create tables using SQL from migrations

---

### Step 8: Set Up Storage & Permissions

Using File Manager or SSH:

```bash
cd ~/laravel_app

# Create necessary directories
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public

# Set permissions (755 for directories, 644 for files)
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;
```

---

### Step 9: Configure .htaccess

Ensure `/home/yourusername/public_html/.htaccess` contains:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### Step 10: Enable SSL (HTTPS)

1. In cPanel, search for **"SSL/TLS Status"**
2. Find your domain: `petrogen.ai`
3. Click **Run AutoSSL**
4. GoDaddy will automatically install a free SSL certificate
5. Wait for confirmation (usually takes a few minutes)

**Or use Let's Encrypt:**
1. In cPanel, find **"Let's Encrypt™ SSL"**
2. Select `petrogen.ai`
3. Click **Issue**

**Force HTTPS:**
Add to the top of `public_html/.htaccess`:

```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

### Step 11: Create Admin User

#### Via SSH:
```bash
cd ~/laravel_app
php artisan db:seed --class=AdminUserSeeder
```

#### Via phpMyAdmin (Manual):
1. Open cPanel → **phpMyAdmin**
2. Select your database
3. Click **SQL** tab
4. Run this query:

```sql
INSERT INTO users (name, email, password, created_at, updated_at) 
VALUES (
    'Admin',
    'admin@petrogen.sa',
    '$2y$12$KIX5qE7xZ0qsA9fY7qYx3.J8vZ0qE7xZ0qsA9fY7qYx3.J8vZ0qE7',  -- password: 123
    NOW(),
    NOW()
);
```

---

## 🎨 Final Touches

### Verify Logo is Accessible:
Visit: `https://petrogen.ai/logo.svg` - You should see your Petrogen logo

### Test the Application:
1. Go to `https://petrogen.ai`
2. You should see the login page with Petrogen logo
3. Login with:
   - Email: `admin@petrogen.sa`
   - Password: `123`

---

## 🔧 Troubleshooting

### Issue: 500 Internal Server Error

**Solution:**
1. Check storage permissions:
   ```bash
   chmod -R 775 ~/laravel_app/storage
   chmod -R 775 ~/laravel_app/bootstrap/cache
   ```

2. Check `.env` file exists and has correct values

3. View error logs:
   - cPanel → **Errors** → View latest errors
   - Or check: `~/laravel_app/storage/logs/laravel.log`

### Issue: CSS/JS Not Loading

**Solution:**
1. Ensure `public_html/build/` folder exists with all assets
2. Clear browser cache
3. Check file permissions (should be 644)

### Issue: Database Connection Error

**Solution:**
1. Verify database credentials in `.env`
2. Ensure database user has all privileges
3. Check database host (usually `localhost`)

### Issue: OpenAI API Not Working

**Solution:**
1. Verify `OPENAI_API_KEY` in `.env` is correct
2. Check SSL certificate is installed (required for HTTPS to OpenAI)
3. Test API key independently

### Issue: White Screen / Blank Page

**Solution:**
1. Enable debugging temporarily:
   - Edit `.env`: `APP_DEBUG=true`
   - Visit site to see actual error
   - **Don't forget to set back to `false`!**

2. Check PHP error logs in cPanel

---

## 📊 Performance Optimization

After deployment, optimize your application:

```bash
cd ~/laravel_app

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer dump-autoload --optimize
```

---

## 🔄 Updating Your Application

When you push changes to GitHub:

```bash
cd ~/laravel_app

# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations if any
php artisan migrate --force

# Clear and recache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔐 Security Checklist

Before going live:

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] Strong database password
- [ ] SSL certificate installed and working
- [ ] Change default admin password after first login
- [ ] File permissions correct (755 for directories, 644 for files)
- [ ] `.env` file not publicly accessible
- [ ] Git folder not in `public_html`

---

## 📞 Need Help?

### GoDaddy Support:
- Phone: Check your GoDaddy account for support number
- Chat: Available in GoDaddy dashboard
- Help Center: https://www.godaddy.com/help

### Application Logs:
- Laravel logs: `~/laravel_app/storage/logs/laravel.log`
- cPanel Error logs: cPanel → Metrics → Errors

### Common GoDaddy cPanel URLs:
- Main cPanel: `https://yourdomain.com/cpanel`
- Or: `https://yourserver.godaddy.com:2083`

---

## ✅ Post-Deployment Checklist

- [ ] Application accessible at https://petrogen.ai
- [ ] SSL certificate working (HTTPS)
- [ ] Login page shows Petrogen logo
- [ ] Can login with admin credentials
- [ ] Can create new chat conversation
- [ ] OpenAI responses working
- [ ] File upload functional
- [ ] All CSS/JS loading correctly

---

## 🎉 Your PetrogenAI Platform is Live!

Congratulations! Your AI-powered chat platform is now live at **https://petrogen.ai**

Share the URL with your Petrogen employees and start using your custom ChatGPT-like assistant!
