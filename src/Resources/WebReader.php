<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class WebReader
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Read content from given URL
     */
    public function read(array $params): array
    {
        if (!isset($params['url'])) {
            throw new \InvalidArgumentException('`url` must be provided.');
        }

        return $this->client->post('/reader', $params);
    }
}
