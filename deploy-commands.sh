#!/bin/bash
# Deployment commands for petrogen.ai production server

echo "==================================="
echo "Starting Deployment Process"
echo "==================================="

# Navigate to project directory
cd ~/petrogenai

echo ""
echo "Step 1: Checking current git status..."
git status

echo ""
echo "Step 2: Stashing any local changes..."
git stash

echo ""
echo "Step 3: Resetting to HEAD (clean slate)..."
git reset --hard HEAD

echo ""
echo "Step 4: Pulling latest code from GitHub..."
git pull origin main

echo ""
echo "Step 5: Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/framework storage/logs

echo ""
echo "Step 6: Ensuring bootstrap/cache directory exists..."
mkdir -p bootstrap/cache
chmod -R 775 bootstrap/cache

echo ""
echo "Step 7: Verifying public/build assets..."
ls -la public/build/

echo ""
echo "Step 8: Running database migration..."
php artisan migrate --force

echo ""
echo "Step 9: Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo "Step 10: Caching configuration for production..."
php artisan config:cache
php artisan route:cache

echo ""
echo "==================================="
echo "Deployment Complete!"
echo "==================================="
echo ""
echo "Testing the application..."
echo "Visit: https://petrogen.ai/login"
echo ""
