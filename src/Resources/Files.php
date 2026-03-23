<?php

namespace Zai\Resources;

use Zai\ZaiClient;

class Files
{
    private ZaiClient $client;

    public function __construct(ZaiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Upload a file
     */
    public function create(array $params): array
    {
        if (!isset($params['file']) && !isset($params['upload_detail'])) {
            throw new \InvalidArgumentException('At least one of `file` and `upload_detail` must be provided.');
        }

        if (!isset($params['purpose'])) {
            throw new \InvalidArgumentException('Missing required parameter: `purpose`');
        }

        $multipart = [];
        foreach ($params as $name => $contents) {
            if ($name === 'file' && is_resource($contents)) {
                $multipart[] = [
                    'name'     => 'file',
                    'contents' => $contents,
                    'filename' => stream_get_meta_data($contents)['uri'] ?? 'file'
                ];
            } elseif ($name === 'file' && is_string($contents) && file_exists($contents)) {
                $multipart[] = [
                    'name'     => 'file',
                    'contents' => fopen($contents, 'r'),
                    'filename' => basename($contents)
                ];
            } else {
                $multipart[] = [
                    'name'     => $name,
                    'contents' => is_array($contents) ? json_encode($contents) : $contents
                ];
            }
        }

        return $this->client->post('/files', ['__multipart' => $multipart]);
    }

    /**
     * List files
     */
    public function list(array $params = []): array
    {
        return $this->client->get('/files', $params);
    }

    /**
     * Delete a file
     */
    public function delete(string $fileId): array
    {
        return $this->client->delete("/files/{$fileId}");
    }

    /**
     * Retrieve file content
     */
    public function content(string $fileId): array
    {
        return $this->client->get("/files/{$fileId}/content", [], [
            'Accept' => 'application/binary'
        ]);
    }
}
