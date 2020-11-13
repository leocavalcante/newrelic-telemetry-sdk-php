<?php declare(strict_types=1);

use NewRelic\Event;
use NewRelic\Adapter;

require_once __DIR__ . '/../vendor/autoload.php';

$account_id = getenv('NR_ACCOUNT_ID');
$adapter = new Adapter\Curl(getenv('NR_API_KEY'));

$event_api = new Event\API($account_id, $adapter);
$event_api->send('Purchase', ['account' => 3, 'amount' => 259.54]);
$response = $event_api->commit();

if ($response->isOk()) {
    echo sprintf("Event ID: %s\n", $response->getId());
} else {
    echo sprintf("Event send error: %s\n", $response->getMessage());
}