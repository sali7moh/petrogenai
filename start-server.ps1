# PetrogenAI Development Server Startup Script
Write-Host "Starting PetrogenAI Development Server..." -ForegroundColor Cyan

# Add PHP and Node.js to PATH
$env:PATH = "$env:USERPROFILE\scoop\shims;$env:USERPROFILE\scoop\apps\php\current;$env:USERPROFILE\scoop\apps\nodejs-lts\current;$env:PATH"

# Navigate to project directory
Set-Location "c:\Users\salih\OneDrive\Desktop\SYSMNT\AI\Ai Projects\PetrogenAI\PetrogenAi"

# Display versions
Write-Host ""
Write-Host "PHP Version:" -ForegroundColor Green
php -v | Select-Object -First 1

Write-Host ""
Write-Host "Node.js Version:" -ForegroundColor Green
node --version

Write-Host ""
Write-Host "NPM Version:" -ForegroundColor Green
npm --version

Write-Host ""
Write-Host "Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear

Write-Host ""
Write-Host "Starting Laravel server on http://localhost:8080..." -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

# Start the server
php artisan serve --host=localhost --port=8080
