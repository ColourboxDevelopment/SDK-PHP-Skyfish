<?php

namespace Skyfish;

class Client extends \GuzzleHttp\Client
{

    public function __construct()
    {
        \GuzzleHttp\Client::__construct(['base_uri' => "https://api.colourbox.com"]);
    }
}
