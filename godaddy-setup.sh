#!/bin/bash

# PetrogenAI - GoDaddy Quick Setup Script
# Run this script after uploading files to your GoDaddy server via SSH

echo "============================================"
echo "  PetrogenAI - GoDaddy Setup Script"
echo "============================================"
echo ""

# Get current directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "📁 Setting up directory structure..."

# Create laravel_app directory if it doesn't exist
cd ~
if [ ! -d "laravel_app" ]; then
    mkdir -p laravel_app
fi

# Move Laravel files to laravel_app (if not already there)
if [ -f "$SCRIPT_DIR/artisan" ]; then
    echo "📦 Moving Laravel application files..."
    cp -r "$SCRIPT_DIR/"* ~/laravel_app/
    cp -r "$SCRIPT_DIR/".* ~/laravel_app/ 2>/dev/null || true
fi

cd ~/laravel_app

echo "✅ Files moved to ~/laravel_app/"

# Create .env from .env.example if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "⚠️  Please edit .env file with your database and API credentials!"
else
    echo "✅ .env file already exists"
fi

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    echo "✅ Composer dependencies installed"
else
    echo "⚠️  Composer not found. You'll need to install dependencies manually."
    echo "   Run: composer install --optimize-autoloader --no-dev"
fi

# Generate application key if not set
if grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
    echo "✅ Application key generated"
else
    echo "✅ Application key already set"
fi

# Create storage directories
echo "📂 Creating storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache

echo "✅ Storage directories created"

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

echo "✅ Permissions set"

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
echo "📝 Updating public_html/index.php..."
cat > ~/public_html/index.php << 'INDEXPHP'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Point to Laravel app outside public_html
require __DIR__.'/../laravel_app/vendor/autoload.php';

$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
INDEXPHP

echo "✅ public_html configured"

# Run migrations (ask first)
echo ""
echo "🗄️  Database Setup"
echo "=================="
echo "Before running migrations, make sure you've:"
echo "  1. Created MySQL database in cPanel"
echo "  2. Updated .env with database credentials"
echo ""
read -p "Do you want to run migrations now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🚀 Running migrations..."
    php artisan migrate --force
    echo "✅ Migrations completed"
    
    echo ""
    read -p "Create admin user? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --class=AdminUserSeeder
        echo "✅ Admin user created (admin@petrogen.sa / 123)"
    fi
else
    echo "⏭️  Skipped migrations. Run manually: php artisan migrate --force"
fi

# Clear and cache config
echo ""
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Application optimized"

echo ""
echo "============================================"
echo "  ✅ Setup Complete!"
echo "============================================"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Edit .env file with your credentials:"
echo "   nano ~/laravel_app/.env"
echo ""
echo "   Update these values:"
echo "   - DB_DATABASE=your_database_name"
echo "   - DB_USERNAME=your_database_user"
echo "   - DB_PASSWORD=your_database_password"
echo "   - OPENAI_API_KEY=your_openai_key"
echo ""
echo "2. Set up SSL certificate in cPanel:"
echo "   - Go to SSL/TLS Status"
echo "   - Run AutoSSL for petrogen.ai"
echo ""
echo "3. Visit your site:"
echo "   https://petrogen.ai"
echo ""
echo "4. Login with:"
echo "   Email: admin@petrogen.sa"
echo "   Password: 123"
echo "   (Change password after first login!)"
echo ""
echo "============================================"
echo ""
echo "📖 For detailed instructions, see:"
echo "   GODADDY-DEPLOYMENT.md"
echo ""
echo "🎉 Your PetrogenAI platform is ready!"
echo ""
