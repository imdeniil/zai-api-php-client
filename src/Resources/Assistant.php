<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class Assistant
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create assistant conversation
     */
    public function conversation(array $params): array
    {
        if (!isset($params['assistant_id'])) {
            throw new \InvalidArgumentException('`assistant_id` must be provided.');
        }
        if (!isset($params['messages'])) {
            throw new \InvalidArgumentException('`messages` must be provided.');
        }

        return $this->client->post('/assistant', $params);
    }

    /**
     * Query assistant support list
     */
    public function querySupport(array $params = []): array
    {
        return $this->client->post('/assistant/list', $params);
    }

    /**
     * Query conversation usage list
     */
    public function queryConversationUsage(array $params): array
    {
        if (!isset($params['assistant_id'])) {
            throw new \InvalidArgumentException('`assistant_id` must be provided.');
        }

        return $this->client->post('/assistant/conversation/list', $params);
    }
}
