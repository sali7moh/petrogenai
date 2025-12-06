# PetrogenAI - Requirements Installation Script
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  PetrogenAI Requirements Installer  " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "This script will install:" -ForegroundColor Yellow
Write-Host "  1. PHP 8.2" -ForegroundColor White
Write-Host "  2. Composer" -ForegroundColor White
Write-Host "  3. Node.js 20" -ForegroundColor White
Write-Host ""

$downloadPath = "$env:TEMP\PetrogenAI"
New-Item -ItemType Directory -Force -Path $downloadPath | Out-Null

# 1. Install PHP
Write-Host "[1/3] Downloading PHP 8.2..." -ForegroundColor Yellow
$phpUrl = "https://windows.php.net/downloads/releases/php-8.2.13-Win32-vs16-x64.zip"
$phpZip = "$downloadPath\php.zip"
$phpPath = "C:\php"

Invoke-WebRequest -Uri $phpUrl -OutFile $phpZip -UseBasicParsing
Write-Host "Extracting PHP..." -ForegroundColor Yellow
Expand-Archive -Path $phpZip -DestinationPath $phpPath -Force

Copy-Item "$phpPath\php.ini-development" "$phpPath\php.ini" -Force
(Get-Content "$phpPath\php.ini") -replace ';extension=mbstring', 'extension=mbstring' -replace ';extension=openssl', 'extension=openssl' -replace ';extension=pdo_mysql', 'extension=pdo_mysql' -replace ';extension=curl', 'extension=curl' -replace ';extension=fileinfo', 'extension=fileinfo' | Set-Content "$phpPath\php.ini"

Write-Host "PHP installed successfully!" -ForegroundColor Green

# 2. Install Composer
Write-Host "[2/3] Downloading Composer..." -ForegroundColor Yellow
$composerUrl = "https://getcomposer.org/Composer-Setup.exe"
$composerExe = "$downloadPath\Composer-Setup.exe"

Invoke-WebRequest -Uri $composerUrl -OutFile $composerExe -UseBasicParsing
Write-Host "Installing Composer..." -ForegroundColor Yellow
Start-Process -FilePath $composerExe -ArgumentList "/VERYSILENT /NORESTART" -Wait
Write-Host "Composer installed successfully!" -ForegroundColor Green

# 3. Install Node.js
Write-Host "[3/3] Downloading Node.js..." -ForegroundColor Yellow
$nodeUrl = "https://nodejs.org/dist/v20.10.0/node-v20.10.0-x64.msi"
$nodeMsi = "$downloadPath\nodejs.msi"

Invoke-WebRequest -Uri $nodeUrl -OutFile $nodeMsi -UseBasicParsing
Write-Host "Installing Node.js..." -ForegroundColor Yellow
Start-Process -FilePath "msiexec.exe" -ArgumentList "/i ""$nodeMsi"" /qn /norestart" -Wait
Write-Host "Node.js installed successfully!" -ForegroundColor Green

# Add PHP to PATH
Write-Host ""
Write-Host "Adding PHP to system PATH..." -ForegroundColor Yellow
$oldPath = [Environment]::GetEnvironmentVariable('Path', 'Machine')
if ($oldPath -notlike "*C:\php*") {
    [Environment]::SetEnvironmentVariable('Path', "$oldPath;C:\php", 'Machine')
    Write-Host "PHP added to PATH!" -ForegroundColor Green
}

Write-Host ""
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Installation Complete!             " -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "NEXT: Close and reopen PowerShell, then run:" -ForegroundColor Yellow
Write-Host "  composer install" -ForegroundColor White
Write-Host "  npm install" -ForegroundColor White
Write-Host ""
