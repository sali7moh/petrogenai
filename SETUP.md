# PetrogenAI - Quick Setup Guide

## Prerequisites

Before you begin, ensure you have:
- ✅ Composer installed
- ✅ PHP 8.1+ installed
- ✅ MySQL database
- ✅ Node.js 18+ and npm
- ✅ OpenAI API key

## Step-by-Step Installation

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petrogenai
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE petrogenai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Configure OpenAI

Edit `.env` file and add your OpenAI API key:

```env
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-4-turbo-preview
OPENAI_MAX_TOKENS=2000
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Production Deployment to petrogen.ai

### 1. Server Requirements

- Ubuntu 20.04+ or similar Linux distribution
- Nginx web server
- MySQL 5.7+
- PHP 8.1-FPM
- SSL certificate (Let's Encrypt)

### 2. Upload Files

Upload all files to `/var/www/petrogenai` on your server.

### 3. Run Deployment Script

```bash
chmod +x deploy.sh
sudo ./deploy.sh
```

### 4. Configure Database

```bash
# Login to MySQL
sudo mysql -u root -p

# Run these commands
CREATE DATABASE petrogenai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'petrogenai'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON petrogenai.* TO 'petrogenai'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Update Environment

```bash
cd /var/www/petrogenai
nano .env
```

Update these values:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://petrogen.ai

DB_DATABASE=petrogenai
DB_USERNAME=petrogenai
DB_PASSWORD=your_secure_password

OPENAI_API_KEY=your_api_key_here
```

### 6. Run Migrations

```bash
php artisan migrate --force
```

### 7. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/petrogenai
sudo chmod -R 755 /var/www/petrogenai
sudo chmod -R 775 /var/www/petrogenai/storage
sudo chmod -R 775 /var/www/petrogenai/bootstrap/cache
```

### 8. Install SSL Certificate

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d petrogen.ai -d www.petrogen.ai
```

### 9. Configure Nginx

The nginx.conf file is already included. Just link it:

```bash
sudo ln -s /var/www/petrogenai/nginx.conf /etc/nginx/sites-available/petrogen.ai
sudo ln -s /etc/nginx/sites-available/petrogen.ai /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 10. Optimize for Production

```bash
cd /var/www/petrogenai
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Testing

1. Visit `https://petrogen.ai`
2. Register a new account
3. Start a conversation
4. Upload a file
5. Test the AI responses

## Troubleshooting

### Issue: 500 Server Error

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log
```

### Issue: Permission Denied

```bash
sudo chown -R www-data:www-data /var/www/petrogenai
sudo chmod -R 775 storage bootstrap/cache
```

### Issue: Database Connection Failed

- Verify database credentials in `.env`
- Check MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u petrogenai -p petrogenai`

### Issue: OpenAI API Errors

- Verify API key is correct in `.env`
- Check OpenAI account has credits
- Test API key: `curl https://api.openai.com/v1/models -H "Authorization: Bearer YOUR_API_KEY"`

## Default Test Account

After running migrations, you can create an admin account:

```bash
php artisan tinker
```

Then run:
```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@petrogen.ai',
    'password' => bcrypt('SecurePassword123'),
    'role' => 'admin',
    'is_active' => true
]);
```

## Security Checklist

- [ ] Changed APP_KEY
- [ ] Set APP_DEBUG=false in production
- [ ] Strong database password
- [ ] HTTPS enabled with valid SSL
- [ ] OpenAI API key secured
- [ ] File upload limits configured
- [ ] Firewall configured (UFW)
- [ ] Regular backups enabled

## Maintenance

### Backup Database

```bash
mysqldump -u petrogenai -p petrogenai > backup_$(date +%Y%m%d).sql
```

### Update Application

```bash
cd /var/www/petrogenai
git pull origin main  # If using Git
composer install --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Monitor Logs

```bash
# Application logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

## Support

For technical support:
- Email: support@petrogen.ai
- Internal IT Help Desk

---

**Version:** 1.0.0  
**Last Updated:** December 2024  
**Status:** Production Ready ✅
