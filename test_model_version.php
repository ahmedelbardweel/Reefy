<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('services.gemini.key');

$modelsToTest = [
    'v1/models/gemini-flash-latest',
    'v1beta/models/gemini-flash-latest',
    'v1beta/models/gemini-2.0-flash',
    'v1beta/models/gemini-2.0-flash-exp',
];

foreach ($modelsToTest as $m) {
    echo "Testing $m... ";
    $url = "https://generativelanguage.googleapis.com/{$m}:generateContent?key={$apiKey}";
    $payload = [
        'contents' => [['parts' => [['text' => 'Hi']]]]
    ];
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(60)->post($url, $payload);
        if ($response->successful()) {
            echo "SUCCESS!\n";
        } else {
            echo "FAILED: " . $response->status() . " " . $response->body() . "\n";
        }
    } catch (\Exception $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
}
