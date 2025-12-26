<?php

// Debug script to find the configuration issue
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo "✅ Configuration loaded successfully!\n";
} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo 'File: '.$e->getFile()."\n";
    echo 'Line: '.$e->getLine()."\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}
