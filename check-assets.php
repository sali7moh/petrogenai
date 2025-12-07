<?php
// Check if we're in the right directory
if (!file_exists('public/index.php')) {
    echo "Error: This script must be run from the Laravel root directory\n";
    exit(1);
}

echo "Checking public/build directory...\n";
if (file_exists('public/build/manifest.json')) {
    echo "✓ Build directory exists\n";
    echo "✓ Manifest found\n";
    
    $manifest = json_decode(file_get_contents('public/build/manifest.json'), true);
    print_r($manifest);
    
    // Check if assets exist
    foreach ($manifest as $item) {
        $file = 'public/' . $item['file'];
        if (file_exists($file)) {
            echo "✓ $file exists (" . filesize($file) . " bytes)\n";
        } else {
            echo "✗ $file MISSING\n";
        }
    }
} else {
    echo "✗ Manifest not found\n";
}

echo "\nChecking if files are web-accessible...\n";
echo "Document root should point to: " . realpath('public') . "\n";
echo "Current script location: " . __DIR__ . "\n";
