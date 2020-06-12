<?php

namespace Skyfish;

use GuzzleHttp\Client;

class Authenticator
{

    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function authenticate(string $username, string $password, string $key, string $secret)
    {
        return new Credential($this->getToken($username, $password, $key, $secret));
    }

    private function getToken(string $username, string $password, string $key, string $secret): string
    {
        /*
        $time = time();
        $hmac = hash_hmac('sha1', $this->key . ":" . $time, $this->secret);
        $result = $this->httpClient->post($this->url . '/authenticate/userpasshmac', [
            \GuzzleHttp\RequestOptions::JSON => [
                'username' => $this->username,
                'password' => $this->password,
                'key' => $this->key,
                'ts' => $time,
                'hmac' => $hmac
            ]
        ]);
        return json_decode($result->getBody(), true)["token"];
         */

        $time = time();
        $hmac = hash_hmac('sha1', "$key:$time", $secret);
        $result = $this->httpClient->post('/authenticate/userpasshmac', [
            \GuzzleHttp\RequestOptions::JSON => [
                'username' => $username,
                'password' => $password,
                'key' => $key,
                'ts' => $time,
                'hmac' => $hmac
            ]
        ]);
        return json_decode($result->getBody(), true)["token"];
    }

}
