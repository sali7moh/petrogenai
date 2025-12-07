# Fix Production Assets Script
# Run this with: .\fix-production.ps1

$password = "Sal@6888"
$host = "f9x6j6g74lx9@148.72.62.132"

Write-Host "Connecting to production server..." -ForegroundColor Cyan

# Commands to run on server
$commands = @"
cd ~/petrogenai
echo '=== Caching views and config ==='
php artisan view:cache
php artisan config:cache
chmod -R 755 public/build
chmod -R 755 storage
echo ''
echo '=== Checking manifest ==='
cat public/build/manifest.json
echo ''
echo '=== Testing asset URL ==='
php artisan tinker --execute="echo url('build/assets/app-CGicS93N.css');"
echo ''
echo 'Done! Visit https://petrogen.ai/login to test'
"@

# Execute via SSH
echo $password | ssh $host -p 22 $commands
