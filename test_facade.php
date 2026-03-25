<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Gemini\Laravel\Facades\Gemini;

try {
    echo "Testing Gemini Facade with gemini-1.5-flash...\n";
    $result = Gemini::gemini15Flash()->generateContent('Tell me a short joke about a farmer.');
    echo "SUCCESS: " . $result->text() . "\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

try {
    echo "Testing Gemini Facade with gemini-2.0-flash-exp...\n";
    $result = Gemini::gemini20FlashExp()->generateContent('Tell me a short joke about a farmer.');
    echo "SUCCESS: " . $result->text() . "\n";
} catch (\Exception $e) {
    // Some versions of the package might not have the 2.0-flash helper
    echo "FAILED (2.0-flash may not be in this package version): " . $e->getMessage() . "\n";
}
