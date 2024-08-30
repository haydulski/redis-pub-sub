<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;

echo 'App Publisher started' . PHP_EOL;


$client = new Client([
    'scheme' => 'tcp',
    'host'   => $_ENV['REDIS_HOST'],
    'port'   => $_ENV['REDIS_PORT'],
]);

$client->flushdb();

$messages = json_decode(
    file_get_contents('./messages.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);

foreach ($messages['data'] as $message) {
    $client->xadd('messages', ['msg' => $message]);
    echo "Message published ($message)" . PHP_EOL;
}

echo 'Publishing finished.' . PHP_EOL;
