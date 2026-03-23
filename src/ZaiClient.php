<?php

namespace Zai;

use Zai\Core\HttpClient;
use Zai\Core\JwtToken;
use Zai\Exceptions\ZaiException;
use Zai\Resources\Chat;
use Zai\Resources\Embeddings;
use Zai\Resources\Files;
use Zai\Resources\Ocr;
use Zai\Resources\Assistant;
use Zai\Resources\WebSearch;
use Zai\Resources\WebReader;
use Zai\Resources\Moderations;

class ZaiClient
{
    private string $apiKey;
    private string $baseUrl;
    private bool $disableTokenCache;
    private string $sourceChannel;
    private HttpClient $httpClient;

    public readonly Chat $chat;
    public readonly Embeddings $embeddings;
    public readonly Files $files;
    public readonly Ocr $ocr;
    public readonly Assistant $assistant;
    public readonly WebSearch $webSearch;
    public readonly WebReader $webReader;
    public readonly Moderations $moderations;

    public function __construct(
        ?string $apiKey = null,
        ?string $baseUrl = null,
        int $timeout = 300,
        int $maxRetries = 3,
        $customHttpClient = null,
        array $customHeaders = [],
        bool $disableTokenCache = true,
        ?string $sourceChannel = null
    ) {
        $this->apiKey = $apiKey ?? getenv('ZAI_API_KEY') ?: '';
        if (empty($this->apiKey)) {
            throw new ZaiException('API key not provided, please provide it through parameters or environment variables');
        }

        $this->baseUrl = rtrim($baseUrl ?? getenv('ZAI_BASE_URL') ?: 'https://api.z.ai/api/paas/v4', '/');
        $this->disableTokenCache = $disableTokenCache;
        $this->sourceChannel = $sourceChannel ?? 'php-sdk';

        $this->httpClient = new HttpClient($timeout, $maxRetries, $customHttpClient, $customHeaders);

        $this->chat = new Chat($this);
        $this->embeddings = new Embeddings($this);
        $this->files = new Files($this);
        $this->ocr = new Ocr($this);
        $this->assistant = new Assistant($this);
        $this->webSearch = new WebSearch($this);
        $this->webReader = new WebReader($this);
        $this->moderations = new Moderations($this);
    }

    public function getAuthHeaders(): array
    {
        $headers = [
            'x-source-channel' => $this->sourceChannel,
            'Accept-Language' => 'en-US,en'
        ];

        if ($this->disableTokenCache) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        } else {
            $headers['Authorization'] = 'Bearer ' . JwtToken::generate($this->apiKey);
        }

        return $headers;
    }

    public function request(string $method, string $path, array $options = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        
        $headers = array_merge(
            $this->getAuthHeaders(),
            ['Accept' => 'application/json'],
            $options['headers'] ?? []
        );

        if (!isset($headers['Content-Type']) && !isset($options['multipart'])) {
            $headers['Content-Type'] = 'application/json; charset=UTF-8';
        }

        $options['headers'] = $headers;

        return $this->httpClient->request($method, $url, $options);
    }

    public function get(string $path, array $query = [], array $headers = []): array
    {
        return $this->request('GET', $path, [
            'query' => $query,
            'headers' => $headers,
        ]);
    }

    public function post(string $path, array $data = [], array $headers = []): array
    {
        $options = ['headers' => $headers];
        
        if (isset($data['__multipart'])) {
            $options['multipart'] = $data['__multipart'];
        } else {
            $options['json'] = array_filter($data, fn($value) => $value !== null);
        }
        
        return $this->request('POST', $path, $options);
    }

    public function delete(string $path, array $query = [], array $headers = []): array
    {
        return $this->request('DELETE', $path, [
            'query' => $query,
            'headers' => $headers,
        ]);
    }
}
