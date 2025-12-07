<?php
// Simple diagnostics page
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Production Diagnostics</h1>";
echo "<pre>";

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Laravel Loaded Successfully!\n\n";

echo "APP_ENV: " . env('APP_ENV') . "\n";
echo "APP_DEBUG: " . env('APP_DEBUG') . "\n";
echo "APP_KEY: " . (env('APP_KEY') ? 'SET' : 'NOT SET') . "\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";

// Test DB
try {
    DB::connection()->getPdo();
    echo "\nDatabase: CONNECTED ✓\n";
} catch (\Exception $e) {
    echo "\nDatabase: FAILED ✗\n";
    echo "Error: " . $e->getMessage() . "\n";
}

// Check migrations
try {
    $migrations = DB::table('migrations')->count();
    echo "Migrations: $migrations\n";
} catch (\Exception $e) {
    echo "Migrations check failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
