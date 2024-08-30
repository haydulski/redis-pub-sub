<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;

echo 'App Subscriber started' . PHP_EOL;

$channel = 'messages';

$client = new Client([
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'],
    'port' => $_ENV['REDIS_PORT'],
]);

$pubsub = $client->pubSubLoop(['subscribe' => [$channel]]);

foreach ($pubsub as $message) {
    switch ($message->kind) {
        case 'subscribe':
            echo "Subscribed to {$message->channel}\n";
            break;
        case 'message':
            echo "Received message from {$message->channel}: {$message->payload}\n";
            break;
        case 'unsubscribe':
            echo "Unsubscribed from {$message->channel}\n";
            break;
        case 'psubscribe':
            echo "Subscribed to pattern {$message->pattern}\n";
            break;
        case 'punsubscribe':
            echo "Unsubscribed from pattern {$message->pattern}\n";
            break;
        case 'pmessage':
            echo "Received message from pattern {$message->pattern}: {$message->payload}\n";
            break;
    }

    // Optionally break the loop or perform other actions
    if ($message->kind === 'message' && $message->payload === 'quit') {
        $pubsub->unsubscribe(); // This will stop the loop
        echo 'Listening stopped' . PHP_EOL;
    }
}

echo 'Exiting pubsub loop' . PHP_EOL;