<?php

namespace Skyfish;

class Client extends \GuzzleHttp\Client
{

    public const API = "https://claus-api.cbx.xyz";
    public const VERSION = "v0.1";

    public function __construct(string $username, string $password, string $key, string $secret, array $config = [])
    {
        $cred = new Credential($this->getToken($username, $password, $key, $secret));
        $config['base_uri'] = self::API;
        $config['headers']['Authorization'] = $cred->getHeader();
        $config['headers']['User-Agent'] = self::getAgent();
        \GuzzleHttp\Client::__construct($config);

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

    private static function getAgent(): string
    {
        return 'Skyfish PHP SDK version: ' . self::VERSION;
    }
}
