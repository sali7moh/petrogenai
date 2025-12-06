#!/bin/bash

# PetrogenAI - Quick Start Script
# This script helps you get started quickly with the local development setup

echo "╔════════════════════════════════════════════════╗"
echo "║                                                ║"
echo "║           PetrogenAI Quick Start               ║"
echo "║                                                ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠ .env file not found. Creating from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file exists${NC}"
fi

# Check for composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}✗ Composer not found. Please install Composer first.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Composer found${NC}"

# Check for npm
if ! command -v npm &> /dev/null; then
    echo -e "${RED}✗ npm not found. Please install Node.js first.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ npm found${NC}"

# Check for PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP not found. Please install PHP 8.1 or higher.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ PHP found ($(php -r 'echo PHP_VERSION;'))${NC}"

echo ""
echo "Starting installation..."
echo ""

# Install composer dependencies
echo "📦 Installing PHP dependencies..."
composer install --quiet
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PHP dependencies installed${NC}"
else
    echo -e "${RED}✗ Failed to install PHP dependencies${NC}"
    exit 1
fi

# Install npm dependencies
echo "📦 Installing Node.js dependencies..."
npm install --silent
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Node.js dependencies installed${NC}"
else
    echo -e "${RED}✗ Failed to install Node.js dependencies${NC}"
    exit 1
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --quiet
    echo -e "${GREEN}✓ Application key generated${NC}"
else
    echo -e "${GREEN}✓ Application key already set${NC}"
fi

# Build assets
echo "🎨 Building frontend assets..."
npm run build > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Assets built successfully${NC}"
else
    echo -e "${YELLOW}⚠ Asset build had warnings (this is usually okay)${NC}"
fi

# Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/attachments
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
echo -e "${GREEN}✓ Storage directories created${NC}"

echo ""
echo "╔════════════════════════════════════════════════╗"
echo "║          Installation Complete! 🎉             ║"
echo "╚════════════════════════════════════════════════╝"
echo ""
echo "Next steps:"
echo ""
echo "1. Configure your database in .env file:"
echo "   ${YELLOW}nano .env${NC}"
echo "   Update: DB_DATABASE, DB_USERNAME, DB_PASSWORD"
echo ""
echo "2. Add your OpenAI API key in .env:"
echo "   ${YELLOW}OPENAI_API_KEY=sk-your-key-here${NC}"
echo ""
echo "3. Create the database:"
echo "   ${YELLOW}mysql -u root -p -e \"CREATE DATABASE petrogenai;\"${NC}"
echo ""
echo "4. Run migrations:"
echo "   ${YELLOW}php artisan migrate${NC}"
echo ""
echo "5. Start the development server:"
echo "   ${YELLOW}php artisan serve${NC}"
echo ""
echo "6. Visit: ${GREEN}http://localhost:8000${NC}"
echo ""
echo "For detailed instructions, see: ${YELLOW}SETUP.md${NC}"
echo ""
