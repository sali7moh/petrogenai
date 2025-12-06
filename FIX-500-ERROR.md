# 🔧 Fix 500 Server Error After Login

## You're seeing: `500 | SERVER ERROR`

This usually means one of these issues:

---

## 🚀 QUICK FIX - Run This Command:

Open **cPanel Terminal** and run:

```bash
cd ~/petrogenai && bash <(curl -sS https://raw.githubusercontent.com/sali7moh/petrogenai/main/fix-500-error.sh)
```

This will automatically check and fix:
- ✅ File permissions
- ✅ APP_KEY generation
- ✅ Cache issues
- ✅ Database connection
- ✅ Missing files

---

## 🔍 Manual Troubleshooting:

### Step 1: Check Error Logs

```bash
cd ~/petrogenai
tail -50 storage/logs/laravel.log
```

**Common errors you might see:**

#### ❌ "No application encryption key has been specified"
**Fix:**
```bash
cd ~/petrogenai
php artisan key:generate --force
php artisan config:cache
```

#### ❌ "Permission denied" or "Failed to open stream"
**Fix:**
```bash
cd ~/petrogenai
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):$(whoami) storage bootstrap/cache
```

#### ❌ "Database connection failed" or "SQLSTATE[HY000]"
**Fix:**
```bash
nano ~/petrogenai/.env
```
Verify these lines are correct:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=your_actual_password_here
```

Then run:
```bash
cd ~/petrogenai
php artisan migrate --force
```

#### ❌ "Class 'XXX' not found" or "Vendor not found"
**Fix:**
```bash
cd ~/petrogenai
composer install --no-dev --optimize-autoloader
```

---

### Step 2: Clear All Caches

```bash
cd ~/petrogenai
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Then rebuild:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Step 3: Verify File Structure

Check if these files exist:
```bash
ls -la ~/petrogenai/.env
ls -la ~/petrogenai/vendor/
ls -la ~/public_html/index.php
```

If `vendor/` is missing:
```bash
cd ~/petrogenai
composer install --no-dev
```

If `public_html/index.php` is missing:
```bash
cat > ~/public_html/index.php << 'INDEXPHP'
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
INDEXPHP
```

---

### Step 4: Check Database

Verify database exists in **cPanel → MySQL Databases**:
- Database: `f9x6j6g74lx9_petrogen_ai`
- User: `f9x6j6g74lx9_petrogen_user`
- User has ALL PRIVILEGES

Run migrations:
```bash
cd ~/petrogenai
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
```

---

## 🔄 NUCLEAR OPTION (If Nothing Works):

Complete reset with fresh deployment:

```bash
# 1. Backup old installation
cd ~
mv petrogenai petrogenai_old_broken

# 2. Deploy fresh
git clone https://github.com/sali7moh/petrogenai.git
cd petrogenai
bash auto-deploy.sh

# 3. Configure .env
nano .env
# Update DB credentials and OpenAI key

# 4. Run migrations
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder

# 5. Clear and cache
php artisan config:cache
php artisan route:cache
```

---

## 🆘 Still Not Working?

### Check PHP Error Logs:

In **cPanel → Errors** or:
```bash
tail -50 ~/public_html/error_log
```

### Enable Debug Mode (Temporarily):

```bash
nano ~/petrogenai/.env
```

Change:
```env
APP_DEBUG=true
```

Visit the site again - you'll see detailed error message.

**⚠️ IMPORTANT:** Set back to `false` after fixing:
```env
APP_DEBUG=false
```

---

## 📞 Common 500 Error Causes:

| Issue | Check | Fix |
|-------|-------|-----|
| No APP_KEY | `grep APP_KEY .env` | `php artisan key:generate --force` |
| Wrong permissions | `ls -la storage/` | `chmod -R 775 storage bootstrap/cache` |
| DB not configured | Check `.env` DB settings | Update credentials and run migrations |
| Missing vendor | `ls vendor/` | `composer install` |
| Cache issues | - | Clear all caches |
| Wrong index.php path | Check `require` paths | Update to `../petrogenai/` |

---

## ✅ After Fixing:

1. Clear browser cache
2. Try login again at: https://petrogen.ai/login
3. Login with:
   - Email: `admin@petrogen.sa`
   - Password: `123`

---

Your server credentials for troubleshooting:
- **SSH**: f9x6j6g74lx9@148.72.62.132
- **Password**: Sal@6888
