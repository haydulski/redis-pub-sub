<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;

echo '<h1>App Publisher</h1>';

if (!isset($_GET['publish'])) {
    echo 'No new messages';
    return null;
}

$client = new Client([
    'scheme' => 'tcp',
    'host'   => $_ENV['REDIS_HOST'],
    'port'   => $_ENV['REDIS_PORT'],
]);

$message = htmlentities($_GET['publish']);

$client->publish('messages', $message);

echo "Message published ($message)";