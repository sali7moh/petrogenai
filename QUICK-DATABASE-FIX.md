# Quick Fix - Database Already Created

## I see you already have `petrogen_db` database!

Now you just need to:

1. **Update .env file** with your database info
2. **Run migrations** to create tables

---

## Step 1: Update .env

Open cPanel Terminal and run:

```bash
nano ~/petrogenai/.env
```

Update these lines:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=petrogen_db
DB_USERNAME=petrogen_db
DB_PASSWORD=
```

**Note:** 
- If you have a database password, put it after `DB_PASSWORD=`
- If NO password, leave it empty: `DB_PASSWORD=`

Also update your OpenAI key:
```env
OPENAI_API_KEY=sk-proj-YOUR-KEY-HERE
```

**Save:** Press `Ctrl+O`, `Enter`, `Ctrl+X`

---

## Step 2: Run Migrations (Create Tables)

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
Migrating: 2024_01_01_000003_create_messages_table
Migrated:  2024_01_01_000003_create_messages_table
Migrating: 2024_01_01_000004_create_attachments_table
Migrated:  2024_01_01_000004_create_attachments_table
```

---

## Step 3: Create Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## Step 4: Clear Caches

```bash
php artisan config:cache
php artisan route:cache
```

---

## Step 5: Test!

Go to **https://petrogen.ai** and login:
- Email: `admin@petrogen.sa`
- Password: `123`

---

## If you get "Access denied" error:

Check database user permissions in cPanel → MySQL Databases:
- Make sure user `petrogen_db` exists
- Make sure it's added to database `petrogen_db`
- Make sure it has ALL PRIVILEGES

---

## ALL COMMANDS IN ONE:

```bash
# Update .env first with nano, then run:
cd ~/petrogenai
php artisan config:clear
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
php artisan config:cache
php artisan route:cache
echo "✅ Done! Visit https://petrogen.ai"
```

That's it! 🎉
