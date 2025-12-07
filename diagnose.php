<?php
// Production Diagnostics Script

echo "=== PetrogenAI Production Diagnostics ===\n\n";

// 1. Check .env file
echo "1. Environment Configuration:\n";
echo "   APP_ENV: " . env('APP_ENV') . "\n";
echo "   APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
echo "   APP_KEY: " . (env('APP_KEY') ? 'SET' : 'NOT SET') . "\n\n";

// 2. Check database connection
echo "2. Database Connection:\n";
echo "   Driver: " . env('DB_CONNECTION') . "\n";
echo "   Host: " . env('DB_HOST') . "\n";
echo "   Database: " . env('DB_DATABASE') . "\n";
try {
    DB::connection()->getPdo();
    echo "   Status: ✓ CONNECTED\n\n";
} catch (\Exception $e) {
    echo "   Status: ✗ FAILED - " . $e->getMessage() . "\n\n";
}

// 3. Check storage permissions
echo "3. Storage Permissions:\n";
$dirs = ['storage/logs', 'storage/framework/sessions', 'storage/framework/views', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    $writable = is_writable(base_path($dir));
    echo "   $dir: " . ($writable ? '✓ Writable' : '✗ NOT Writable') . "\n";
}
echo "\n";

// 4. Check mail configuration
echo "4. Mail Configuration:\n";
echo "   Mailer: " . env('MAIL_MAILER') . "\n";
echo "   Host: " . env('MAIL_HOST') . "\n";
echo "   Port: " . env('MAIL_PORT') . "\n";
echo "   Username: " . env('MAIL_USERNAME') . "\n\n";

// 5. Check recent errors
echo "5. Recent Errors (last 10 lines of log):\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -30);
    foreach ($lastLines as $line) {
        if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
            echo "   " . trim($line) . "\n";
        }
    }
} else {
    echo "   No log file found\n";
}

echo "\n=== End Diagnostics ===\n";
