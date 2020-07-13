<?php

require __DIR__ . '/../vendor/autoload.php';

$username = $argv[1];
$pass = $argv[2];
$key = $argv[3];
$secret = $argv[4];
$userId = $argv[5];
$folder = $argv[6];
$fileName = $argv[7];


if(count($argv) < 8) {
    echo "Insufficient amount of args provided";
    die(1);
}

$client = new \Skyfish\Client($username, $pass, $key, $secret);
$mediaId = $client->upload($userId, $folder, $fileName);

echo "Successfully uploaded file, id: $mediaId\n";
