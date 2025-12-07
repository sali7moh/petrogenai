#!/bin/bash
# Comprehensive Production Fix

cd ~/petrogenai

echo "=== PRODUCTION FIX SCRIPT ==="
echo ""

# 1. Check if .env exists
if [ ! -f .env ]; then
    echo "✗ .env file missing! Copying from .env.example..."
    cp .env.example .env
fi

# 2. Ensure APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# 3. Update critical .env settings
echo "Updating .env settings..."
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env

# 4. Check database settings exist
if ! grep -q "DB_DATABASE=" .env; then
    echo "Adding database configuration..."
    cat >> .env << EOL

# Database Configuration  
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=petrogen_db
DB_USERNAME=petrogen_user
DB_PASSWORD=your_db_password
EOL
fi

# 5. Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache public/build
chmod -R 777 storage/framework storage/logs
mkdir -p bootstrap/cache
chmod -R 775 bootstrap/cache

# 6. Clear and cache
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear  
php artisan view:clear
php artisan route:clear

# 7. Run migrations
echo "Running migrations..."
php artisan migrate --force

# 8. Cache for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

echo ""
echo "=== FIX COMPLETE ==="
echo ""
echo "Next steps:"
echo "1. Edit ~/petrogenai/.env and update database credentials"
echo "2. Run: php artisan migrate --force"
echo "3. Test at https://petrogen.ai/register"
