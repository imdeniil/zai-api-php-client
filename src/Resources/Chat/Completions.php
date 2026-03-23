<?php

namespace Zai\Resources\Chat;

use Zai\ZaiClient;

class Completions
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a chat completion
     *
     * @param array{
     *   model: string,
     *   messages: array,
     *   request_id?: string,
     *   user_id?: string,
     *   do_sample?: bool,
     *   stream?: bool,
     *   temperature?: float,
     *   top_p?: float,
     *   max_tokens?: int,
     *   seed?: int,
     *   stop?: string|array,
     *   sensitive_word_check?: array,
     *   tools?: array,
     *   tool_choice?: string|array,
     *   meta?: array,
     *   extra?: array,
     *   response_format?: array,
     *   thinking?: array,
     *   watermark_enabled?: bool,
     *   tool_stream?: bool
     * } $params
     * @return array The API response
     */
    public function create(array $params): array
    {
        if (!isset($params['model'])) {
            throw new \InvalidArgumentException("Missing required parameter: 'model'");
        }
        if (!isset($params['messages'])) {
            throw new \InvalidArgumentException("Missing required parameter: 'messages'");
        }

        // Handle some default adjustments as in python SDK
        if (isset($params['temperature'])) {
            if ($params['temperature'] <= 0) {
                $params['do_sample'] = false;
                $params['temperature'] = 0.01;
            } elseif ($params['temperature'] >= 1) {
                $params['temperature'] = 0.99;
            }
        }
        
        if (isset($params['top_p'])) {
            if ($params['top_p'] >= 1) {
                $params['top_p'] = 0.99;
            } elseif ($params['top_p'] <= 0) {
                $params['top_p'] = 0.01;
            }
        }

        // Python SDK drops None values, ZaiClient::post already does array_filter
        return $this->client->post('/chat/completions', $params);
    }
}
