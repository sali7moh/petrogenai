# 🧹 Remove Old Version & Deploy Fresh

## If you have an old version of PetrogenAI on your server, follow these steps:

### Your Credentials:
- **IP**: 148.72.62.132
- **Username**: f9x6j6g74lx9
- **Password**: Sal@6888

---

## 🗑️ STEP 1: Clean Up Old Installation

### Open cPanel Terminal and run:

```bash
cd ~ && curl -sS https://raw.githubusercontent.com/sali7moh/petrogenai/main/cleanup-old.sh | bash
```

**Or if you prefer manual cleanup:**

```bash
# Backup old installation
cd ~
mv petrogenai petrogenai_backup_old

# Clean public_html
rm -f ~/public_html/index.php
rm -rf ~/public_html/build
rm -f ~/public_html/logo.svg
rm -f ~/public_html/.htaccess

# Backup current public_html
cp -r public_html public_html_backup
```

**This will:**
- ✅ Backup your old petrogenai directory
- ✅ Clean public_html of old Laravel files
- ✅ Keep your backups safe
- ✅ Preserve any other files in public_html

---

## 🚀 STEP 2: Deploy Fresh Installation

After cleanup, run the deployment command:

```bash
cd ~ && git clone https://github.com/sali7moh/petrogenai.git && cd ~/petrogenai && bash auto-deploy.sh
```

This will install everything fresh!

---

## 🗄️ STEP 3: Database - Choose One:

### Option A: Use Existing Database (Preserve old data)
If you want to keep your old data:

```bash
# Just update .env with your existing database credentials
nano ~/petrogenai/.env
```

Update:
```env
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=your_existing_password
```

Then run migrations (won't delete existing data):
```bash
cd ~/petrogenai && php artisan migrate --force
```

### Option B: Fresh Database (Start clean)
If you want to start completely fresh:

1. **In cPanel → MySQL Databases:**
   - Delete old database (if exists)
   - Create new: `petrogen_ai`
   - Create new user: `petrogen_user`
   - Grant ALL PRIVILEGES

2. **Update .env:**
```bash
nano ~/petrogenai/.env
```

Update with new credentials:
```env
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=NEW_PASSWORD_HERE
OPENAI_API_KEY=your_openai_key
```

3. **Run migrations & seed:**
```bash
cd ~/petrogenai
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
php artisan config:cache
```

---

## ✅ STEP 4: Verify Deployment

1. **Visit:** https://petrogen.ai
2. **Login with:**
   - Email: `admin@petrogen.sa`
   - Password: `123`

---

## 🔧 Quick Commands Summary:

```bash
# 1. Clean old version
cd ~ && curl -sS https://raw.githubusercontent.com/sali7moh/petrogenai/main/cleanup-old.sh | bash

# 2. Deploy fresh
cd ~ && git clone https://github.com/sali7moh/petrogenai.git && cd ~/petrogenai && bash auto-deploy.sh

# 3. Configure database (edit .env)
nano ~/petrogenai/.env

# 4. Run migrations
cd ~/petrogenai && php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder

# 5. Cache config
php artisan config:cache && php artisan route:cache
```

---

## 📋 What Gets Backed Up:

- ✅ Old `petrogenai` folder → `petrogenai_backup_YYYYMMDD_HHMMSS`
- ✅ Old `public_html` → `public_html_backup`
- ✅ Old database (if using SQLite) → `database_backup_YYYYMMDD.sqlite`

**All backups are saved in your home directory (~)**

---

## 🆘 Rollback (If Needed):

If something goes wrong and you want to restore the old version:

```bash
# Restore old app
cd ~
rm -rf petrogenai
mv petrogenai_backup_old petrogenai

# Restore old public_html
rm -rf public_html
mv public_html_backup public_html
```

---

## 💡 Pro Tip:

You can also manually delete files via **cPanel File Manager**:
1. Go to cPanel → File Manager
2. Navigate to home directory
3. Delete `petrogenai` folder
4. Clean `public_html` folder
5. Then follow deployment steps

---

Your fresh PetrogenAI installation will be ready at **https://petrogen.ai**! 🎉
