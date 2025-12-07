#!/bin/bash
# GoDaddy Production Fix Script
# This script fixes the document root issue on GoDaddy shared hosting

echo "==================================="
echo "GoDaddy Laravel Deployment Fix"
echo "==================================="

cd ~/

# Check if public_html exists (standard GoDaddy setup)
if [ -d "public_html" ]; then
    echo "✓ Found public_html directory"
    
    # Backup existing public_html
    if [ "$(ls -A public_html)" ]; then
        echo "Backing up existing public_html..."
        mv public_html public_html_backup_$(date +%Y%m%d_%H%M%S)
    fi
    
    # Create symlink from public_html to Laravel's public folder
    echo "Creating symlink: public_html -> petrogenai/public"
    ln -sfn ~/petrogenai/public ~/public_html
    
    echo "✓ Symlink created"
else
    echo "public_html not found. Checking alternative setup..."
fi

# Verify the setup
echo ""
echo "=== Verifying Setup ==="
ls -la ~/public_html
echo ""
ls -la ~/petrogenai/public/build/assets/

echo ""
echo "=== Setting Permissions ==="
chmod -R 755 ~/petrogenai/public
chmod -R 755 ~/petrogenai/storage
chmod -R 775 ~/petrogenai/bootstrap/cache

echo ""
echo "=== Clearing Caches ==="
cd ~/petrogenai
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "==================================="
echo "Setup Complete!"
echo "==================================="
echo "Visit https://petrogen.ai to test"
