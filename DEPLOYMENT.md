# Deployment Guide for petrogen.ai

## Quick Deployment Steps

### 1. Push to GitHub

```bash
# Initialize git (if not done)
git init
git add .
git commit -m "Initial commit - PetrogenAI platform"

# Create repository on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/petrogenai.git
git branch -M main
git push -u origin main
```

### 2. Server Setup (Ubuntu/Debian)

```bash
# Install required packages
sudo apt update
sudo apt install -y nginx php8.1-fpm php8.1-cli php8.1-mysql php8.1-sqlite3 \
    php8.1-curl php8.1-mbstring php8.1-xml php8.1-zip php8.1-gd \
    composer nodejs npm certbot python3-certbot-nginx

# Clone repository
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/petrogenai.git
cd petrogenai

# Install dependencies
sudo composer install --optimize-autoloader --no-dev
sudo npm install --production
sudo npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/petrogenai
sudo chmod -R 755 /var/www/petrogenai
sudo chmod -R 775 /var/www/petrogenai/storage
sudo chmod -R 775 /var/www/petrogenai/bootstrap/cache

# Configure environment
sudo cp .env.example .env
sudo php artisan key:generate

# Edit .env file
sudo nano .env
```

### 3. Configure .env for Production

```env
APP_NAME="PetrogenAI"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://petrogen.ai

DB_CONNECTION=sqlite

OPENAI_API_KEY=your-real-openai-api-key
OPENAI_MODEL=gpt-4-turbo-preview
OPENAI_MAX_TOKENS=2000
```

### 4. Run Migrations

```bash
sudo php artisan migrate --force
sudo php artisan optimize
```

### 5. Configure Nginx

Create `/etc/nginx/sites-available/petrogen.ai`:

```nginx
server {
    listen 80;
    server_name petrogen.ai www.petrogen.ai;
    root /var/www/petrogenai/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/petrogen.ai /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 6. Install SSL Certificate

```bash
sudo certbot --nginx -d petrogen.ai -d www.petrogen.ai
```

### 7. Create Admin User

```bash
cd /var/www/petrogenai
sudo php artisan tinker
```

Then in tinker:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@petrogen.sa';
$user->password = Hash::make('your-secure-password');
$user->save();
exit
```

### 8. Set up Automatic Updates (Optional)

Create `/var/www/petrogenai/deploy.sh`:

```bash
#!/bin/bash
cd /var/www/petrogenai
git pull origin main
composer install --optimize-autoloader --no-dev
npm install --production
npm run build
php artisan migrate --force
php artisan optimize
sudo systemctl reload php8.1-fpm
```

Make executable:
```bash
sudo chmod +x /var/www/petrogenai/deploy.sh
```

## Accessing Your Platform

Visit: **https://petrogen.ai**

Login with the admin credentials you created.

## Troubleshooting

### Clear caches
```bash
sudo php artisan cache:clear
sudo php artisan config:clear
sudo php artisan view:clear
sudo php artisan route:clear
```

### Check logs
```bash
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

### Fix permissions
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Security Checklist

- ✅ SSL certificate installed
- ✅ APP_DEBUG=false in production
- ✅ Strong passwords
- ✅ Firewall configured (UFW)
- ✅ Regular backups scheduled
- ✅ OpenAI API key secured in .env
- ✅ File upload limits set
- ✅ CSRF protection enabled

## Backup

```bash
# Database backup
cp /var/www/petrogenai/database/database.sqlite ~/backups/database-$(date +%Y%m%d).sqlite

# Full backup
tar -czf ~/backups/petrogenai-$(date +%Y%m%d).tar.gz /var/www/petrogenai \
    --exclude=node_modules --exclude=vendor
```

## Support

For production issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Nginx logs: `/var/log/nginx/error.log`
3. PHP-FPM logs: `/var/log/php8.1-fpm.log`
