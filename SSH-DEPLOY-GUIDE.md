# SSH Deployment Guide - Step by Step

## 🔐 Getting SSH Access on GoDaddy

### Step 1: Check if SSH is Available
1. Login to your GoDaddy account
2. Go to: **My Products** → **Web Hosting** → Click **Manage**
3. Click **cPanel Admin**
4. In cPanel search bar, type: **"SSH"**
5. Look for **"SSH Access"** or **"Terminal"**

**Note:** If you don't see SSH Access:
- Your hosting plan might not support SSH
- Contact GoDaddy support to enable it, OR
- Use the **File Manager method** (I'll provide instructions)

---

## 🚀 Option 1: Deploy Using SSH (Recommended)

### What You'll Need:
- SSH hostname (usually: `petrogen.ai` or your server IP)
- SSH username (usually your cPanel username)
- SSH password (your cPanel password)
- Port: 22

### From Your Windows PC:

**I can help you connect and deploy directly! Just provide:**

1. **SSH Host**: _____________ (e.g., petrogen.ai or 123.45.67.89)
2. **SSH Username**: _____________ (your cPanel username)
3. **SSH Password**: _____________ (I'll handle securely)
4. **SSH Port**: 22 (default)

**Then I'll run these commands for you:**

```bash
# Connect to your server
ssh username@petrogen.ai

# Clone the repository
cd ~
git clone https://github.com/sali7moh/petrogenai.git
cd petrogenai

# Run automated setup
bash godaddy-setup.sh

# The script will:
# - Set up directory structure
# - Install dependencies
# - Configure .env
# - Set permissions
# - Run migrations
# - Create admin user
```

---

## 🖥️ Option 2: Use cPanel Terminal (Easier!)

If SSH from external is blocked, you can use cPanel's built-in terminal:

### Steps:

1. **Login to cPanel**
2. **Search for "Terminal"**
3. **Click "Terminal"** icon
4. **You'll see a command prompt** - now run these commands:

```bash
# Navigate to home directory
cd ~

# Clone your GitHub repository
git clone https://github.com/sali7moh/petrogenai.git

# Enter the directory
cd petrogenai

# Run the automated setup script
bash godaddy-setup.sh
```

**The script will guide you through:**
- ✅ Setting up directories
- ✅ Installing Composer dependencies
- ✅ Configuring .env
- ✅ Running migrations
- ✅ Creating admin user

---

## 📝 What You'll Need to Configure

### 1. Database Credentials
Before running the script, create a database in cPanel:

**cPanel → MySQL Databases**
- Database name: `petrogen_ai`
- Database user: `petrogen_user`
- Password: (generate strong password)
- Grant ALL PRIVILEGES

**Save these values!**

### 2. OpenAI API Key
- Get from: https://platform.openai.com/api-keys
- Copy the key (starts with `sk-proj-...`)

---

## 🎬 I Can Do It For You!

**Tell me which option you prefer:**

### Option A: "I have SSH credentials"
Provide me:
- SSH Host
- SSH Username
- SSH Password (or tell me to ask privately)
- I'll connect and deploy everything

### Option B: "I'll use cPanel Terminal"
Tell me when you're in the cPanel Terminal, and I'll give you the exact commands to copy/paste

### Option C: "SSH is not available"
I'll guide you through the File Manager method (manual but works!)

---

## 📞 Which Option Do You Want?

Reply with:
- **"A"** - You have SSH access, I'll deploy remotely
- **"B"** - You'll use cPanel Terminal (easiest!)
- **"C"** - No SSH, use File Manager

Then I'll proceed with the deployment! 🚀
