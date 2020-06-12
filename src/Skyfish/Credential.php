<?php

namespace Skyfish;

class Credential
{

    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getHeader(): string
    {
        return "CBX-SIMPLE-TOKEN Token={$this->token}";
    }

}
