<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class Ocr
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Handwriting OCR
     */
    public function handwriting(array $params): array
    {
        if (!isset($params['file'])) {
            throw new \InvalidArgumentException('`file` must be provided.');
        }

        $multipart = [];
        $params['tool_type'] = $params['tool_type'] ?? 'hand_write';

        foreach ($params as $name => $contents) {
            if ($name === 'file' && (is_resource($contents) || (is_string($contents) && file_exists($contents)))) {
                $multipart[] = [
                    'name'     => 'file',
                    'contents' => is_resource($contents) ? $contents : fopen($contents, 'r'),
                    'filename' => is_resource($contents) ? (stream_get_meta_data($contents)['uri'] ?? 'file') : basename($contents)
                ];
            } else {
                $multipart[] = [
                    'name'     => $name,
                    'contents' => $contents
                ];
            }
        }

        return $this->client->post('/files/ocr', ['__multipart' => $multipart]);
    }

    /**
     * Layout Parsing
     */
    public function layoutParsing(array $params): array
    {
        if (!isset($params['file'])) {
            throw new \InvalidArgumentException('`file` must be provided.');
        }

        $multipart = [];
        foreach ($params as $name => $contents) {
            if ($name === 'file' && (is_resource($contents) || (is_string($contents) && file_exists($contents)))) {
                $multipart[] = [
                    'name'     => 'file',
                    'contents' => is_resource($contents) ? $contents : fopen($contents, 'r'),
                    'filename' => is_resource($contents) ? (stream_get_meta_data($contents)['uri'] ?? 'file') : basename($contents)
                ];
            } else {
                $multipart[] = [
                    'name'     => $name,
                    'contents' => $contents
                ];
            }
        }

        return $this->client->post('/files/layout-parsing', ['__multipart' => $multipart]);
    }
}
