#!/usr/bin/env pwsh

# PetrogenAI - Automated GoDaddy Deployment Script
# This script will connect to your GoDaddy server and deploy the application

$SSHHost = "148.72.62.132"
$SSHPort = "22"
$SSHUser = "f9x6j6g74lx9"
$SSHPass = "SM@54adxbI$Y"

Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "🚀 DEPLOYING PETROGENAI TO GODADDY" -ForegroundColor Green
Write-Host "============================================`n" -ForegroundColor Cyan

# Test if plink (PuTTY) is available, otherwise use ssh
$useSSH = $true
if (Get-Command plink -ErrorAction SilentlyContinue) {
    $useSSH = $false
}

# Deployment commands to run on the server
$deploymentCommands = @"
cd ~
echo '📦 Cloning repository...'
git clone https://github.com/sali7moh/petrogenai.git 2>/dev/null || (cd petrogenai && git pull origin main)
cd petrogenai

echo '✅ Repository cloned/updated'
echo ''
echo '🔧 Running setup script...'
bash godaddy-setup.sh

echo ''
echo '============================================'
echo '  ✅ DEPLOYMENT COMPLETE!'
echo '============================================'
echo ''
echo 'Next: Configure .env with database credentials'
"@

# Save commands to a temporary file
$tempScript = "$env:TEMP\deploy-petrogen.sh"
Set-Content -Path $tempScript -Value $deploymentCommands

Write-Host "📡 Connecting to GoDaddy server..." -ForegroundColor Yellow
Write-Host "   Host: $SSHHost" -ForegroundColor Gray
Write-Host "   User: $SSHUser`n" -ForegroundColor Gray

# Connect and execute
if ($useSSH) {
    # Use Windows OpenSSH client
    Write-Host "Using SSH client...`n" -ForegroundColor Cyan
    
    # Create SSH command
    $sshCommand = "ssh -o StrictHostKeyChecking=no -p $SSHPort $SSHUser@$SSHHost 'bash -s' < `"$tempScript`""
    
    Write-Host "⚠️  You'll be prompted for password: SM@54adxbI$Y" -ForegroundColor Yellow
    Write-Host ""
    
    # Execute
    Invoke-Expression $sshCommand
} else {
    # Use plink (PuTTY)
    Write-Host "Using PuTTY plink...`n" -ForegroundColor Cyan
    echo y | plink -ssh -P $SSHPort -l $SSHUser -pw $SSHPass $SSHHost -m $tempScript
}

# Clean up
Remove-Item $tempScript -ErrorAction SilentlyContinue

Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "📋 NEXT STEPS" -ForegroundColor Yellow
Write-Host "============================================`n" -ForegroundColor Cyan
Write-Host "1. Create MySQL database in cPanel" -ForegroundColor White
Write-Host "2. Edit .env file with database credentials" -ForegroundColor White
Write-Host "3. Enable SSL certificate" -ForegroundColor White
Write-Host "4. Visit https://petrogen.ai`n" -ForegroundColor White
