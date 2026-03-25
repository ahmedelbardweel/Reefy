<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('services.gemini.key');
echo "API Key Length: " . strlen($apiKey) . "\n";

try {
    $response = \Illuminate\Support\Facades\Http::timeout(60)->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    if ($response->successful()) {
        $models = $response->json('models');
        foreach ($models as $model) {
            echo $model['name'] . " - " . implode(',', $model['supportedGenerationMethods']) . "\n";
        }
    } else {
        echo "Error: " . $response->status() . " " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
