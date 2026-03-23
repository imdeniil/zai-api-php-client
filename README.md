# ZAI API PHP Client

A PHP SDK for interacting with the ZAI API, styled after the official Python SDK.

## Requirements

- PHP 8.1+
- Composer

## Installation

```bash
composer require imdeniil/zai-api-php-client
```

## Quick Start

```php
require_once 'vendor/autoload.php';

use Zai\ZaiClient;

// Инициализация клиента
// API-ключ можно передать параметром или задать через переменную окружения ZAI_API_KEY
$client = new ZaiClient('your_api_key_id.your_api_key_secret');

// Создание chat completion
try {
    $response = $client->chat->completions->create([
        'model' => 'glm-4',
        'messages' => [
            ['role' => 'user', 'content' => 'Расскажи шутку']
        ],
    ]);

    print_r($response);
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

## Architecture

This SDK follows modern PHP practices:
- **PSR-4** Autoloading
- **PHP 8.1** Features (Readonly properties, strong typing)
- Guzzle for HTTP requests
- Local JWT token generation with caching
