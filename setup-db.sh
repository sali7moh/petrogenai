#!/bin/bash
cd ~/petrogenai

echo "=== Updating .env with database credentials ==="

# Update database settings
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=petrogen_ai/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=toorx/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD='Sal@688\$xz'/" .env
sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" .env
sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env

# If lines don't exist, add them
grep -q "^DB_DATABASE=" .env || echo "DB_DATABASE=petrogen_ai" >> .env
grep -q "^DB_USERNAME=" .env || echo "DB_USERNAME=toorx" >> .env
grep -q "^DB_PASSWORD=" .env || echo "DB_PASSWORD='Sal@688\$xz'" >> .env
grep -q "^DB_HOST=" .env || echo "DB_HOST=localhost" >> .env
grep -q "^DB_CONNECTION=" .env || echo "DB_CONNECTION=mysql" >> .env

echo "✓ Database credentials updated"
echo ""

echo "=== Clearing config cache ==="
php artisan config:clear
echo ""

echo "=== Testing database connection ==="
php artisan db:show
echo ""

echo "=== Checking migration status ==="
php artisan migrate:status
echo ""

echo "=== Running migrations ==="
php artisan migrate --force
echo ""

echo "=== Caching for production ==="
php artisan config:cache
php artisan route:cache
echo ""

echo "=== COMPLETE! ==="
echo "Visit https://petrogen.ai/register to test"
