#!/bin/bash

# PetrogenAI Deployment Script
# This script automates the deployment of the Laravel application

echo "====================================="
echo "PetrogenAI Deployment Script"
echo "====================================="

# Configuration
APP_DIR="/var/www/petrogenai"
DOMAIN="petrogen.ai"

# Update system
echo "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
echo "Installing required packages..."
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-curl php8.1-zip php8.1-gd unzip git

# Install Composer
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

# Install Node.js and npm
if ! command -v node &> /dev/null; then
    echo "Installing Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
fi

# Create application directory
echo "Setting up application directory..."
sudo mkdir -p $APP_DIR
sudo chown -R $USER:$USER $APP_DIR

# Copy application files
echo "Copying application files..."
cp -r * $APP_DIR/
cd $APP_DIR

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run build

# Set up environment file
echo "Setting up environment file..."
cp .env.example .env

# Generate application key
php artisan key:generate

# Set permissions
echo "Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Configure Nginx
echo "Configuring Nginx..."
sudo cp nginx.conf /etc/nginx/sites-available/$DOMAIN
sudo ln -sf /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Set up MySQL database
echo "Setting up database..."
echo "Please run the following MySQL commands manually:"
echo "CREATE DATABASE petrogenai;"
echo "CREATE USER 'petrogenai'@'localhost' IDENTIFIED BY 'your_secure_password';"
echo "GRANT ALL PRIVILEGES ON petrogenai.* TO 'petrogenai'@'localhost';"
echo "FLUSH PRIVILEGES;"
echo ""
echo "Then update the .env file with your database credentials and OpenAI API key"
echo ""

# Install SSL certificate
echo "To install SSL certificate, run:"
echo "sudo apt install certbot python3-certbot-nginx"
echo "sudo certbot --nginx -d petrogen.ai -d www.petrogen.ai"

echo ""
echo "====================================="
echo "Deployment preparation complete!"
echo "====================================="
echo ""
echo "Next steps:"
echo "1. Configure MySQL database using the commands above"
echo "2. Update .env file with database credentials and OpenAI API key"
echo "3. Run: php artisan migrate"
echo "4. Install SSL certificate using certbot"
echo "5. Test the application at https://petrogen.ai"
echo ""
