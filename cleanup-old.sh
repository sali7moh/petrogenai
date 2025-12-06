#!/bin/bash
# Clean up old PetrogenAI installation before fresh deployment

echo "============================================"
echo "🧹 CLEANING OLD PETROGENAI INSTALLATION"
echo "============================================"
echo ""

# Navigate to home directory
cd ~

echo "📋 Checking for existing installations..."
echo ""

# Backup old files if they exist
if [ -d "petrogenai" ]; then
    echo "⚠️  Found existing petrogenai directory"
    BACKUP_NAME="petrogenai_backup_$(date +%Y%m%d_%H%M%S)"
    echo "💾 Creating backup: ~/$BACKUP_NAME"
    mv petrogenai "$BACKUP_NAME"
    echo "✅ Old installation backed up"
    echo ""
fi

# Clean up public_html
if [ -d "public_html" ]; then
    echo "🌐 Cleaning public_html..."
    
    # Backup current public_html if not already backed up
    if [ ! -d "public_html_backup" ]; then
        echo "💾 Creating backup: ~/public_html_backup"
        cp -r public_html public_html_backup
    fi
    
    # Remove Laravel-specific files but keep other files
    echo "🗑️  Removing old Laravel files from public_html..."
    rm -f ~/public_html/index.php
    rm -rf ~/public_html/build
    rm -f ~/public_html/logo.svg
    rm -f ~/public_html/favicon.svg
    rm -f ~/public_html/.htaccess
    
    echo "✅ public_html cleaned"
    echo ""
fi

# Clean up any old database backups (optional)
if [ -f "database.sqlite" ]; then
    echo "💾 Found old SQLite database, backing up..."
    mv database.sqlite "database_backup_$(date +%Y%m%d_%H%M%S).sqlite"
    echo "✅ Database backed up"
    echo ""
fi

echo "============================================"
echo "✅ CLEANUP COMPLETE!"
echo "============================================"
echo ""
echo "📋 Summary:"
echo "   - Old petrogenai directory: BACKED UP"
echo "   - public_html: CLEANED"
echo "   - Backups saved in home directory"
echo ""
echo "🚀 Ready for fresh installation!"
echo ""
echo "Next: Run auto-deploy.sh to install fresh version"
echo ""
