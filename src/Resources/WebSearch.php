<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class WebSearch
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Web search for given query
     */
    public function search(array $params): array
    {
        if (!isset($params['search_query'])) {
            throw new \InvalidArgumentException('`search_query` must be provided.');
        }

        return $this->client->post('/web_search', $params);
    }
}
