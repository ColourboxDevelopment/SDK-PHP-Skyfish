<?php

namespace Skyfish;

use GuzzleHttp\Psr7\Response;

class Upload
{

    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function file(int $folderId, string $file): ?int
    {
        return $this->create($this->prepare($folderId), $file);
    }

    private function prepare(int $folderId): array
    {
        return $this->httpClient->post(
            "/upload/register",
            [
                "folder" => $folderId
            ]
        );
    }

    private function create(array $preparedResult, string $filePath): ?int
    {
        $fields = [];
        foreach ($preparedResult['fields'] as $field) {
            if ($field['name'] == 'file') {
                $fields['file'] = curl_file_create($filePath);
            } else {
                $fields[$field['name']] = $field['value'];
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $preparedResult['action']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);

        $response = new Response(curl_getinfo($ch, CURLINFO_HTTP_CODE), [], $result);

        curl_close($ch);
        return json_decode($response->getBody(), true)['media_id'];
    }
}
