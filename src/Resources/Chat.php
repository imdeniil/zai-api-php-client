<?php

namespace Zai\Resources;

use Zai\ZaiClient;
use Zai\Resources\Chat\Completions;

class Chat
{
    private ZaiClient $client;
    public readonly Completions $completions;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
        $this->completions = new Completions($client);
    }
}
