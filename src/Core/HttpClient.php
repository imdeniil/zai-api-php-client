<?php

namespace Zai\Core;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Zai\Exceptions\APIConnectionException;
use Zai\Exceptions\APIResponseException;

class HttpClient
{
    private GuzzleClient $client;
    private array $customHeaders;

    public function __construct(
        int $timeout = 300,
        int $maxRetries = 3,
        ?GuzzleClient $customClient = null,
        array $customHeaders = []
    ) {
        $this->client = $customClient ?? new GuzzleClient([
            'timeout' => $timeout,
        ]);
        $this->customHeaders = $customHeaders;
    }

    public function request(string $method, string $url, array $options = []): array
    {
        $options['headers'] = array_merge($this->customHeaders, $options['headers'] ?? []);

        try {
            $response = $this->client->request($method, $url, $options);
            $body = $response->getBody()->getContents();
            
            if (empty($body)) {
                return [];
            }
            
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['content' => $body];
            }

            return $decoded;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? $response->getBody()->getContents() : '';

            if ($statusCode >= 400) {
                $message = "API Request failed with status {$statusCode}: {$body}";
                throw new APIResponseException($message, $statusCode, $body, $e);
            }

            throw new APIConnectionException("Connection error: " . $e->getMessage(), 0, $e);
        } catch (GuzzleException $e) {
            throw new APIConnectionException("HTTP error: " . $e->getMessage(), 0, $e);
        }
    }
}
