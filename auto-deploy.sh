#!/bin/bash
# PetrogenAI Automated Deployment Script

echo "============================================"
echo "🚀 DEPLOYING PETROGENAI"
echo "============================================"
echo ""

# Navigate to home directory
cd ~

# Clone repository
echo "📦 Cloning repository from GitHub..."
if [ -d "petrogenai" ]; then
    echo "   Directory exists, pulling latest changes..."
    cd petrogenai
    git pull origin main
else
    git clone https://github.com/sali7moh/petrogenai.git
    cd petrogenai
fi

echo "✅ Repository ready"
echo ""

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev --no-interaction
    echo "✅ Dependencies installed"
else
    echo "⚠️  Composer not found, skipping..."
fi

echo ""

# Create .env if not exists
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env created (needs configuration)"
else
    echo "✅ .env already exists"
fi

echo ""

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force --no-interaction
echo "✅ Key generated"

echo ""

# Create storage directories
echo "📂 Creating storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
echo "✅ Directories created"

echo ""

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
echo "✅ Permissions set"

echo ""

# Set up public_html
echo "🌐 Setting up public_html..."

# Backup existing public_html
if [ -d ~/public_html ] && [ ! -d ~/public_html_backup ]; then
    echo "💾 Backing up existing public_html..."
    mv ~/public_html ~/public_html_backup
fi

# Copy public folder to public_html
cp -r public ~/public_html

# Update index.php
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

echo "✅ public_html configured"

echo ""
echo "============================================"
echo "✅ DEPLOYMENT COMPLETE!"
echo "============================================"
echo ""
echo "📋 IMPORTANT: Configure these manually:"
echo ""
echo "1. Create MySQL database in cPanel:"
echo "   - Database: petrogen_ai"
echo "   - User: petrogen_user"
echo "   - Grant ALL PRIVILEGES"
echo ""
echo "2. Edit .env file:"
echo "   nano ~/petrogenai/.env"
echo ""
echo "   Update:"
echo "   - DB_DATABASE=f9x6j6g74lx9_petrogen_ai"
echo "   - DB_USERNAME=f9x6j6g74lx9_petrogen_user"
echo "   - DB_PASSWORD=your_db_password"
echo "   - OPENAI_API_KEY=your_openai_key"
echo ""
echo "3. Run migrations:"
echo "   cd ~/petrogenai"
echo "   php artisan migrate --force"
echo "   php artisan db:seed --class=AdminUserSeeder"
echo ""
echo "4. Enable SSL in cPanel (SSL/TLS Status)"
echo ""
echo "5. Visit: https://petrogen.ai"
echo ""
