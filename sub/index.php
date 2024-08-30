<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;

echo 'App Subscriber started' . PHP_EOL;

$options = getopt('', ['start:', 'group:']);
// get messages from specific time
$start = isset($options['start']) ? $options['start'] : floor(microtime(true) * 1000) - 3600;
$group = isset($options['group']) ? $options['group'] : null;
$channel = 'messages';

$client = new Client([
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'],
    'port' => $_ENV['REDIS_PORT'],
]);

if ($group) {
    // messages will be read by group provided in param and after processing still available to other groups
    $consumer = 'consumer1';
    $client->executeRaw(['XGROUP', 'CREATE', $channel, $group, 0, 'MKSTREAM']); // mkstrem: make stream if not exist
    $messages = $client->executeRaw(['XREADGROUP', 'GROUP', $group, $consumer, 'STREAMS', $channel, '>']);

    if ($messages === null) {
        echo 'No new messages' . PHP_EOL;
        exit(1);
    }

    foreach ($messages[0][1] as $entry) {
        $id = $entry[0];
        echo "Processing message ID: $id\n";
        [$key, $value] = $entry[1];
        echo "$key: $value\n";
        echo PHP_EOL;
        echo PHP_EOL;

        // Acknowledge the message for only this particular group by changing status from pending to read without deleting
        $client->executeRaw(['XACK', $channel, $group, $id]);
    }
} else {
    // simple reading with deleting read messages
    $entries = $client->xrange($channel, $start, '+');

    foreach ($entries as $id => $entry) {
        echo "ID: $id\n";
        foreach ($entry as $field => $value) {
            echo "$field: $value\n";
            sleep(1);
        }
        // xdel will delete message definitely so no other group will consume it
        $client->xdel($channel, $id);
        echo PHP_EOL;
    }
}

echo 'Exiting pubsub stream loop' . PHP_EOL;