<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \Skyfish\Client($username, $pass, $key, $secret);
$result = $client->get("/skyfish/resource/path");
$arrResult = $client->post("/skyfish/resource/path", ['fu' => 'bar']);
