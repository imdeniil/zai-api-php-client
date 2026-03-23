<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class Embeddings
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create embeddings for the given input
     *
     * @param array{
     *   input: string|array,
     *   model: string,
     *   dimensions?: int,
     *   encoding_format?: string,
     *   user?: string,
     *   request_id?: string,
     *   sensitive_word_check?: array
     * } $params
     * @return array
     */
    public function create(array $params): array
    {
        if (!isset($params['input'])) {
            throw new \InvalidArgumentException("Missing required parameter: 'input'");
        }
        if (!isset($params['model'])) {
            throw new \InvalidArgumentException("Missing required parameter: 'model'");
        }

        return $this->client->post('/embeddings', $params);
    }
}
