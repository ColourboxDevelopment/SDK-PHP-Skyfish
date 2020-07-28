<?php

namespace Skyfish;

class Client
{

    public const API = "https://api.colourbox.com";
    public const VERSION = "v1.0.0";
    private $client;

    public function __construct(string $username, string $password, string $key, string $secret, array $config = [])
    {
        $cred = new Credential($this->getToken($username, $password, $key, $secret));
        $config['base_uri'] = self::API;
        $config['headers']['Authorization'] = $cred->getHeader();
        $config['headers']['User-Agent'] = self::getAgent();
        $this->client = new \GuzzleHttp\Client($config);
    }

    private static function getToken(string $username, string $password, string $key, string $secret): string
    {
        $time = time();
        $hmac = hash_hmac('sha1', "$key:$time", $secret);
        $result = (new \GuzzleHttp\Client)->post(self::API . '/authenticate/userpasshmac', [
            \GuzzleHttp\RequestOptions::JSON => [
                'username' => $username,
                'password' => $password,
                'key' => $key,
                'ts' => $time,
                'hmac' => $hmac
            ],
            'headers' => [
                'User-Agent' => self::getAgent()
            ]
        ]);
        return json_decode($result->getBody(), true)["token"];
    }

    public function upload(int $userId, int $folderId, string $file): ?int
    {
        return (new Upload($this))->file($userId, $folderId, $file);
    }

    private static function getAgent(): string
    {
        return 'Skyfish PHP SDK version: ' . self::VERSION;
    }

    public function get(string $url): array
    {
        return json_decode($this->client->get($url), true);
    }

    public function delete(string $url): void
    {
        $this->client->delete($url);
    }

    public function post(string $url, array $body)
    {
        return json_decode($this->client->post($url, [
                \GuzzleHttp\RequestOptions::JSON => $body
            ]
        )->getBody(), true);
    }

    public function put(string $url, array $body): array
    {
        return json_decode($this->client->put($url, [
                \GuzzleHttp\RequestOptions::JSON => $body
            ]
        )->getBody(), true);
    }
}
