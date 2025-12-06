#!/bin/bash
# Troubleshoot and Fix 500 Server Error on PetrogenAI

echo "============================================"
echo "🔧 TROUBLESHOOTING 500 SERVER ERROR"
echo "============================================"
echo ""

cd ~/petrogenai

echo "1️⃣ Checking Laravel Logs..."
echo "----------------------------------------"
if [ -f "storage/logs/laravel.log" ]; then
    echo "📋 Last 30 lines of error log:"
    tail -30 storage/logs/laravel.log
    echo ""
else
    echo "⚠️  No log file found"
    echo ""
fi

echo "2️⃣ Checking Permissions..."
echo "----------------------------------------"
ls -la storage/
ls -la bootstrap/cache/
echo ""

echo "3️⃣ Fixing Permissions..."
echo "----------------------------------------"
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):$(whoami) storage bootstrap/cache
echo "✅ Permissions fixed"
echo ""

echo "4️⃣ Checking .env File..."
echo "----------------------------------------"
if [ -f ".env" ]; then
    echo "✅ .env exists"
    echo "📋 Database config:"
    grep "DB_" .env | grep -v "PASSWORD"
    echo ""
    echo "📋 APP config:"
    grep "APP_" .env
    echo ""
else
    echo "❌ .env file NOT found!"
    echo "Creating from .env.example..."
    cp .env.example .env
    echo "⚠️  You need to configure .env!"
    echo ""
fi

echo "5️⃣ Checking APP_KEY..."
echo "----------------------------------------"
if grep -q "APP_KEY=$" .env; then
    echo "❌ APP_KEY is empty! Generating..."
    php artisan key:generate --force
    echo "✅ APP_KEY generated"
else
    echo "✅ APP_KEY exists"
fi
echo ""

echo "6️⃣ Clearing All Caches..."
echo "----------------------------------------"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Caches cleared"
echo ""

echo "7️⃣ Checking Database Connection..."
echo "----------------------------------------"
php artisan migrate:status 2>&1 | head -10
echo ""

echo "8️⃣ Rebuilding Caches..."
echo "----------------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Caches rebuilt"
echo ""

echo "9️⃣ Checking public_html/index.php..."
echo "----------------------------------------"
if [ -f ~/public_html/index.php ]; then
    echo "✅ index.php exists"
    echo "📋 First 10 lines:"
    head -10 ~/public_html/index.php
else
    echo "❌ index.php NOT found in public_html!"
    echo "Creating it now..."
    cat > ~/public_html/index.php << 'INDEXPHP'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../petrogenai/vendor/autoload.php';

$app = require_once __DIR__.'/../petrogenai/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
INDEXPHP
    echo "✅ index.php created"
fi
echo ""

echo "🔟 Checking Storage Link..."
echo "----------------------------------------"
php artisan storage:link 2>&1
echo ""

echo "============================================"
echo "✅ TROUBLESHOOTING COMPLETE"
echo "============================================"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Check the error log above for specific issues"
echo "2. Verify database credentials in .env"
echo "3. Make sure database exists and is accessible"
echo "4. Try accessing the site again"
echo ""
echo "🔍 To view full error log:"
echo "   tail -100 ~/petrogenai/storage/logs/laravel.log"
echo ""
echo "📝 To edit .env:"
echo "   nano ~/petrogenai/.env"
echo ""
echo "🔄 To restart (clear everything):"
echo "   cd ~/petrogenai"
echo "   php artisan config:clear"
echo "   php artisan cache:clear"
echo "   chmod -R 775 storage bootstrap/cache"
echo ""
