<?php

use Zai\Core\JwtToken;

test('it generates a valid jwt token from api key', function () {
    $apiKey = 'id.secret';
    $token = JwtToken::generate($apiKey);
    
    expect($token)->toBeValidJwt();
});

test('it caches tokens within expiry', function () {
    $apiKey = 'id.secret';
    $token1 = JwtToken::generate($apiKey);
    $token2 = JwtToken::generate($apiKey);
    
    expect($token1)->toBe($token2);
});

test('it throws for invalid key format', function () {
    Zai\Core\JwtToken::resetCache();
    try {
        Zai\Core\JwtToken::generate('invalidkey');
        $this->fail('Exception was not thrown');
    } catch (\InvalidArgumentException $e) {
        expect($e->getMessage())->toStartWith('Invalid API key format');
    }
});
