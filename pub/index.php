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

$messages = json_decode(
    file_get_contents('./messages.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);

foreach ($messages['data'] as $message) {
    $client->publish('messages', $message);
    echo "Message published ($message)" . PHP_EOL;

    sleep(random_int(1, 10));
}

echo 'Publishing finished.' . PHP_EOL;
