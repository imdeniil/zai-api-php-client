<?php

namespace Zai\Core;

class JwtToken
{
    private static ?string $tokenCache = null;
    private static int $cacheExpiry = 0;

    public static function generate(string $apiKey): string
    {
        $now = time();
        if (self::$tokenCache && $now < self::$cacheExpiry) {
            return self::$tokenCache;
        }

        $parts = explode('.', $apiKey);
        if (count($parts) !== 2) {
            throw new \InvalidArgumentException('Invalid API key format. Expected "id.secret".');
        }

        [$id, $secret] = $parts;

        $header = json_encode(['alg' => 'HS256', 'sign_type' => 'SIGN']);
        
        // Cache time 3 minutes
        $ttlSeconds = 3 * 60;
        // Token validity period is 30 seconds longer than cache time
        $apiTokenTtlSeconds = $ttlSeconds + 30;

        $payload = json_encode([
            'api_key' => $id,
            'exp' => ($now + $apiTokenTtlSeconds) * 1000,
            'timestamp' => $now * 1000,
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        self::$tokenCache = $token;
        self::$cacheExpiry = $now + $ttlSeconds;

        return $token;
    }

    public static function resetCache(): void
    {
        self::$tokenCache = null;
        self::$cacheExpiry = 0;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
