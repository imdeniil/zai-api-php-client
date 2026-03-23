<?php

use Zai\ZaiClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;

test('zaiclient correctly initializes and makes a request', function () {
    // Create a mock response
    $mock = new MockHandler([
        new Response(200, [], json_encode(['id' => 'test_id', 'choices' => [['message' => ['content' => 'Hello!']]]]))
    ]);

    $handlerStack = HandlerStack::create($mock);
    $customGuzzleClient = new GuzzleClient(['handler' => $handlerStack]);

    // Initialize ZaiClient with mock guzzle client
    $client = new ZaiClient('id.secret', 'https://api.test.com', 30, 1, $customGuzzleClient);

    $response = $client->chat->completions->create([
        'model' => 'glm-4',
        'messages' => [['role' => 'user', 'content' => 'hi']]
    ]);

    expect($response['id'])->toBe('test_id')
        ->and($response['choices'][0]['message']['content'])->toBe('Hello!');
        
    // Check that we got the last request from mock and verify headers
    $lastRequest = $mock->getLastRequest();
    expect($lastRequest->getHeaderLine('Authorization'))->toStartWith('Bearer ');
});
