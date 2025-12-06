# ⚡ SUPER QUICK DEPLOYMENT - 3 COMMANDS ONLY!

## Your GoDaddy cPanel Login:
- **IP**: 148.72.62.132
- **Username**: f9x6j6g74lx9  
- **Password**: Sal@6888

---

## 🚀 DEPLOY IN 3 STEPS:

### 1️⃣ Login to cPanel Terminal
1. Go to your GoDaddy cPanel
2. Search for **"Terminal"**
3. Click to open Terminal

---

### 2️⃣ Copy & Paste This ONE Command:

```bash
cd ~ && git clone https://github.com/sali7moh/petrogenai.git 2>/dev/null || (cd ~/petrogenai && git pull) && cd ~/petrogenai && bash auto-deploy.sh
```

**This single command will:**
- ✅ Clone your repository
- ✅ Install all dependencies
- ✅ Set up directory structure  
- ✅ Configure permissions
- ✅ Set up public_html
- ✅ Generate app key

**Just wait 2-3 minutes for it to complete!**

---

### 3️⃣ Configure Database (5 minutes)

#### A. Create Database in cPanel:
1. Go back to cPanel main page
2. Find **"MySQL® Databases"**
3. Create database: `petrogen_ai`
4. Create user: `petrogen_user` (generate password & save it!)
5. Add user to database with **ALL PRIVILEGES**

#### B. Update .env File:

Back in Terminal, run:
```bash
nano ~/petrogenai/.env
```

Find and update these lines:
```env
DB_DATABASE=f9x6j6g74lx9_petrogen_ai
DB_USERNAME=f9x6j6g74lx9_petrogen_user
DB_PASSWORD=YOUR_GENERATED_DB_PASSWORD_HERE
OPENAI_API_KEY=sk-proj-YOUR-OPENAI-KEY-HERE
```

**Save:** Press `Ctrl+O`, `Enter`, then `Ctrl+X`

#### C. Run Migrations:
```bash
cd ~/petrogenai && php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder && php artisan config:cache
```

---

## 4️⃣ Enable SSL (2 minutes)

1. In cPanel, search **"SSL/TLS Status"**
2. Find **petrogen.ai**
3. Click **"Run AutoSSL"**
4. Wait for confirmation

---

## 🎉 DONE!

Visit: **https://petrogen.ai**

Login with:
- Email: `admin@petrogen.sa`
- Password: `123`

**Change the password immediately after first login!**

---

## 📊 Summary:

| Step | Time | Status |
|------|------|--------|
| 1. Open Terminal | 30s | ⏳ |
| 2. Run deploy command | 3min | ⏳ |
| 3. Configure database & .env | 5min | ⏳ |
| 4. Enable SSL | 2min | ⏳ |
| **Total** | **~10 minutes** | 🚀 |

---

## ❓ Troubleshooting:

**If you see "command not found" for git:**
```bash
which git
```
If empty, contact GoDaddy support to enable Git.

**To check deployment status:**
```bash
cd ~/petrogenai && ls -la
```

**To view logs:**
```bash
tail -50 ~/petrogenai/storage/logs/laravel.log
```

---

## 💡 Need Help?

All files are ready in your GitHub repo:
- DEPLOY-NOW.md (detailed guide)
- auto-deploy.sh (automated script)
- GODADDY-DEPLOYMENT.md (complete manual)

**You're deploying a ChatGPT-like AI platform for Petrogen employees!** 🎯
