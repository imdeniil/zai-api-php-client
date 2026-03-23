<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zai\ZaiClient;

// Замените на ваш реальный API ключ (формат: id.secret)
$apiKey = getenv('ZAI_API_KEY') ?: 'mock_id.mock_secret';
$client = new ZaiClient($apiKey);

try {
    $response = $client->chat->completions->create([
        'model' => 'glm-4',
        'messages' => [
            ['role' => 'user', 'content' => 'Привет, мир!']
        ],
    ]);

    print_r($response);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
