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

$client = new \Skyfish\Client();

$cred = (new \Skyfish\Authenticator($client))->authenticate($username, $pass, $key, $secret);

$mediaId = (new \Skyfish\Upload($client, $cred))->file($userId, $folder, $fileName);

echo "Successfully uploaded file, id: $mediaId\n";
