# 🚀 Deploy PetrogenAI - Easy Copy/Paste Commands

## YOUR SSH/cPanel CREDENTIALS:
```
IP Address: 148.72.62.132
Port: 22
Username: f9x6j6g74lx9
Password: Sal@6888
```

---

## 📋 STEP-BY-STEP DEPLOYMENT

### Step 1: Open cPanel Terminal

1. **Login to GoDaddy cPanel** using your credentials above
2. **Search for "Terminal"** in cPanel
3. **Click "Terminal"** to open it
4. You'll see a command prompt

---

### Step 2: Copy & Paste These Commands

**Just copy each command block and paste into the Terminal, then press Enter:**

#### 🔹 Command 1: Clone the Repository
```bash
cd ~ && git clone https://github.com/sali7moh/petrogenai.git 2>/dev/null || (cd ~/petrogenai && git pull origin main)
```
*This downloads your code from GitHub*

---

#### 🔹 Command 2: Enter Project Directory
```bash
cd ~/petrogenai
```

---

#### 🔹 Command 3: Run Automated Setup
```bash
bash godaddy-setup.sh
```
*This will:*
- ✅ Set up directory structure
- ✅ Install dependencies
- ✅ Create .env file
- ✅ Configure permissions
- ✅ Ask if you want to run migrations

**When prompted:**
- **"Do you want to run migrations now?"** → Type `n` (we'll do this after database setup)
- Press Enter

---

### Step 3: Create MySQL Database

1. **Open a new tab** - Go back to cPanel main page
2. **Find "MySQL® Databases"**
3. **Create Database:**
   - Database Name: `petrogen_ai`
   - Click **Create Database**
   
4. **Create User:**
   - Username: `petrogen_user`
   - Password: Click **Generate Password** (save it!)
   - Click **Create User**
   
5. **Add User to Database:**
   - Database: Select `petrogen_ai`
   - User: Select `petrogen_user`
   - Click **Add**
   - Check **ALL PRIVILEGES**
   - Click **Make Changes**

**📝 SAVE THESE VALUES:**
```
Database Name: f9x6j6g74lx9_petrogen_ai
Database User: f9x6j6g74lx9_petrogen_user
Database Password: [the password you generated]
Database Host: localhost
```

---

### Step 4: Configure .env File

**Back in Terminal, run this command:**

```bash
nano ~/petrogenai/.env
```

**This will open a text editor. Update these lines:**

Find and change:
```env
DB_DATABASE=your_cpanel_username_petrogen_ai
DB_USERNAME=your_cpanel_username_petrogen_user
DB_PASSWORD=your_database_password_here
```

To (use YOUR actual values from Step 3):
```env
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=YOUR_GENERATED_PASSWORD_HERE
```

Also update:
```env
OPENAI_API_KEY=sk-proj-YOUR-ACTUAL-API-KEY
```

**To save:**
- Press `Ctrl + O` (to save)
- Press `Enter` (to confirm)
- Press `Ctrl + X` (to exit)

---

### Step 5: Run Database Migrations

```bash
cd ~/petrogenai && php artisan migrate --force
```

---

### Step 6: Create Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

---

### Step 7: Optimize Application

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

### Step 8: Set Up Web Directory

```bash
# Backup old public_html
mv ~/public_html ~/public_html_backup 2>/dev/null

# Copy public folder to public_html
cp -r ~/petrogenai/public ~/public_html

# Update index.php to point to Laravel app
cat > ~/public_html/index.php << 'EOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../petrogenai/vendor/autoload.php';

$app = require_once __DIR__.'/../petrogenai/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
EOF
```

---

### Step 9: Set Permissions

```bash
chmod -R 775 ~/petrogenai/storage ~/petrogenai/bootstrap/cache
```

---

### Step 10: Enable SSL (Back in cPanel)

1. **In cPanel**, search for **"SSL/TLS Status"**
2. Find your domain: **petrogen.ai**
3. Click **Run AutoSSL**
4. Wait for confirmation (2-3 minutes)

---

### Step 11: Force HTTPS

```bash
cat > ~/public_html/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    # Force HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
```

---

## ✅ DEPLOYMENT COMPLETE!

### Test Your Application:

1. **Visit:** https://petrogen.ai
2. **Login with:**
   - Email: `admin@petrogen.sa`
   - Password: `123`
3. **Change password** after first login!

---

## 🔧 Troubleshooting

### If you see a blank page:
```bash
cd ~/petrogenai && php artisan view:clear && php artisan cache:clear
```

### If database connection fails:
- Check .env database credentials match what you created
- Verify database name includes your username prefix

### Check application logs:
```bash
tail -50 ~/petrogenai/storage/logs/laravel.log
```

---

## 📞 Need Help?

All commands are ready to copy/paste. Just follow Step 1 → Step 11!

**You're deploying to:** https://petrogen.ai  
**With:** ChatGPT-like AI platform for Petrogen employees 🎉
