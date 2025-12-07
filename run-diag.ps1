$password = "Sal@6888"
$server = "f9x6j6g74lx9@148.72.62.132"

Write-Host "Uploading diagnostics..." -ForegroundColor Cyan
& scp public/diag.php ${server}:~/petrogenai/public/

Write-Host "`nRunning diagnostics on server..." -ForegroundColor Cyan
$commands = @"
cd ~/petrogenai
echo '=== Checking .env database settings ==='
grep 'DB_' .env
echo ''
echo '=== Testing database connection ==='
php artisan tinker --execute='try { DB::connection()->getPdo(); echo "DB Connected!\n"; } catch (Exception `$e) { echo "DB Error: " . `$e->getMessage() . "\n"; }'
echo ''
echo '=== Checking recent log errors ==='
tail -20 storage/logs/laravel.log | grep 'ERROR'
"@

echo $password | ssh $server -p 22 $commands

Write-Host "`n`nVisit https://petrogen.ai/diag.php for web diagnostics" -ForegroundColor Green
