#!/usr/bin/env pwsh
# Deploy PetrogenAI to GoDaddy

$host = "148.72.62.132"
$port = "22"
$user = "f9x6j6g74lx9"
$pass = "Sal@6888"

Write-Host "`n🚀 Deploying to GoDaddy Server..." -ForegroundColor Cyan
Write-Host "   Host: $host" -ForegroundColor Gray
Write-Host "   User: $user`n" -ForegroundColor Gray

# Commands to run on server
$commands = @"
cd ~ && \
echo '📦 Cloning repository...' && \
git clone https://github.com/sali7moh/petrogenai.git 2>/dev/null || (cd petrogenai && git pull origin main) && \
cd ~/petrogenai && \
echo '🔧 Running deployment script...' && \
bash auto-deploy.sh
"@

# Save to temp file
$tempScript = "$env:TEMP\deploy-commands.sh"
Set-Content -Path $tempScript -Value $commands

Write-Host "Connecting via SSH...`n" -ForegroundColor Yellow
Write-Host "Password: Sal@6888" -ForegroundColor Gray
Write-Host "(Enter the password when prompted)`n" -ForegroundColor Yellow

# Execute SSH
ssh -o StrictHostKeyChecking=no -p $port "$user@$host" "bash -s" < $tempScript

# Cleanup
Remove-Item $tempScript -ErrorAction SilentlyContinue

Write-Host "`n✅ Deployment command sent!" -ForegroundColor Green
