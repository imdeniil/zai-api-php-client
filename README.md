# ZAI API PHP Client

A PHP SDK for interacting with the ZAI API, styled after the official Python SDK.

## Requirements

- PHP 8.1+
- Composer

## Feature Support

This SDK is modeled after the [ZAI Python SDK](https://github.com/zai-org/z-ai-sdk-python) and focuses on text-based interaction and document parsing.

### ✅ Implemented & Verified (Core & Text)
- **Chat (`$client->chat->completions`)**: Full support for GLM-4/5 models, tools, and streaming.
- **Authentication**: JWT token generation (HS256) with local caching.
- **Embeddings (`$client->embeddings`)**: Vector generation for text.
- **Pest Testing**: Unit & Feature tests included.

### 🛠 Implemented (Beta/Basic)
- **Assistant (`$client->assistant`)**: Conversations and usage tracking.
- **Web Search (`$client->webSearch`)**: Integrated internet search.
- **Web Reader (`$client->webReader`)**: Extracting text/markdown from URLs.
- **Moderations (`$client->moderations`)**: Content safety checking.
- **Files (`$client->files`)**: File uploads, listing, and management.
- **OCR (`$client->ocr`)**: Handwriting recognition and Layout parsing.

### ❌ Not Implemented (Multimedia)
- **Images**, **Audio**, **Videos**, **Voice**: Not planned for current release (focus is on text-only interaction).

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
