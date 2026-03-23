<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class Moderations
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Moderate content for safety and compliance
     */
    public function create(array $params): array
    {
        if (!isset($params['model'])) {
            throw new \InvalidArgumentException('`model` must be provided.');
        }
        if (!isset($params['input'])) {
            throw new \InvalidArgumentException('`input` must be provided.');
        }

        return $this->client->post('/moderations', $params);
    }
}
