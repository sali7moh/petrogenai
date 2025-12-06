# 🗄️ Create Database for PetrogenAI - Step by Step

## The 500 error is because the database doesn't exist yet!

---

## 📋 Step 1: Create MySQL Database in cPanel

### A. Login to GoDaddy cPanel
- **URL**: Your GoDaddy cPanel
- **Username**: f9x6j6g74lx9
- **Password**: Sal@6888

### B. Find MySQL Databases
1. In cPanel dashboard, search for: **"MySQL® Databases"**
2. Click on it

### C. Create Database
1. Scroll to **"Create New Database"**
2. **Database Name**: Type `petrogen_ai`
3. Click **"Create Database"** button
4. You'll see success message

**📝 Note the full database name:**  
It will be: `f9x6j6g74lx9_petrogen_ai`

---

## 👤 Step 2: Create Database User

### A. Still in MySQL Databases page, scroll to "MySQL Users"

1. **Username**: Type `petrogen_user`
2. **Password**: Click **"Generate Password"** button
   - **⚠️ IMPORTANT**: Copy and save this password somewhere safe!
   - Example: `kJ9#mN2$pQ8@vL5`
3. Click **"Create User"** button

**📝 Note the full username:**  
It will be: `f9x6j6g74lx9_petrogen_user`

---

## 🔗 Step 3: Add User to Database

### A. Scroll to "Add User To Database"

1. **User**: Select `f9x6j6g74lx9_petrogen_user`
2. **Database**: Select `f9x6j6g74lx9_petrogen_ai`
3. Click **"Add"** button

### B. Grant Privileges

1. You'll see a page with checkboxes
2. Click **"ALL PRIVILEGES"** at the top (this checks all boxes)
3. Click **"Make Changes"** button

✅ Done! Database is ready!

---

## 🔧 Step 4: Configure .env File

Now we need to tell Laravel about the database.

### Open cPanel Terminal and run:

```bash
nano ~/petrogenai/.env
```

### Find these lines and UPDATE them:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=PUT_YOUR_GENERATED_PASSWORD_HERE
```

**Replace `PUT_YOUR_GENERATED_PASSWORD_HERE` with the password you copied in Step 2!**

### Also update:

```env
OPENAI_API_KEY=sk-proj-YOUR-ACTUAL-OPENAI-KEY
```

### Save and Exit:
- Press `Ctrl + O` (to save)
- Press `Enter` (to confirm)
- Press `Ctrl + X` (to exit)

---

## 🚀 Step 5: Run Database Migrations

This creates all the tables your app needs.

```bash
cd ~/petrogenai
php artisan migrate --force
```

You should see:
```
Migration table created successfully.
Migrating: 2024_01_01_000001_create_users_table
Migrated:  2024_01_01_000001_create_users_table
Migrating: 2024_01_01_000002_create_conversations_table
Migrated:  2024_01_01_000002_create_conversations_table
...
```

---

## 👤 Step 6: Create Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

This creates your admin account:
- **Email**: admin@petrogen.sa
- **Password**: 123

---

## ⚡ Step 7: Clear Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ Step 8: Test Your Site!

1. Go to: **https://petrogen.ai**
2. Click **Login**
3. Use:
   - **Email**: `admin@petrogen.sa`
   - **Password**: `123`

🎉 **You should be logged in!**

---

## 📝 Quick Reference Card

**Save these credentials:**

```
=== cPanel ===
URL: GoDaddy cPanel
Username: f9x6j6g74lx9
Password: Sal@6888

=== Database ===
Database: f9x6j6g74lx9_petrogen_ai
User: f9x6j6g74lx9_petrogen_user
Password: [the one you generated]
Host: localhost

=== Admin Login ===
URL: https://petrogen.ai
Email: admin@petrogen.sa
Password: 123
(⚠️ Change this after first login!)
```

---

## 🔄 All Commands in One Block

After creating database in cPanel, copy/paste this:

```bash
# Configure .env
nano ~/petrogenai/.env
# Update DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Run migrations
cd ~/petrogenai
php artisan migrate --force

# Create admin user
php artisan db:seed --class=AdminUserSeeder

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Done! Visit https://petrogen.ai"
```

---

## ❓ Troubleshooting

### If migration fails:
```bash
# Check database connection
cd ~/petrogenai
php artisan tinker
DB::connection()->getPdo();
exit
```

If you see an error, double-check:
1. Database name is correct
2. Username is correct
3. Password is correct (no extra spaces!)
4. Database user has ALL PRIVILEGES

### To reset and try again:
```bash
cd ~/petrogenai
php artisan migrate:fresh --force
php artisan db:seed --class=AdminUserSeeder
```

---

**Next: Login at https://petrogen.ai and start using your AI assistant!** 🚀
