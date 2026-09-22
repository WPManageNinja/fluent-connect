<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/Services/ThriveCart/ThriveCart.php';

use FluentConnect\App\Services\ThriveCart\ThriveCart;

function assertSameValue($expected, $actual, $message)
{
    if ($expected !== $actual) {
        throw new RuntimeException($message);
    }
}

if (!method_exists(ThriveCart::class, 'normalizeWebhookPayload')) {
    throw new RuntimeException('ThriveCart must normalize form webhook payloads.');
}

$payload = ThriveCart::normalizeWebhookPayload([
    'event' => 'order.success',
    'customer' => [
        'email' => 'fresh@example.test',
        'first_name' => 'Fresh'
    ],
    'accessible_purchase_map' => 'product-81'
]);

assertSameValue('order.success', $payload['event'], 'Event was not preserved.');
assertSameValue('fresh@example.test', $payload['customer']['email'], 'Nested customer data was not preserved.');
assertSameValue(['product-81'], $payload['purchase_map'], 'Legacy product map was not normalized.');

$payload = ThriveCart::normalizeWebhookPayload([
    'purchase_map' => ['product-81']
]);

assertSameValue(['product-81'], $payload['purchase_map'], 'Native purchase map was changed.');

echo "ThriveCart webhook payload tests passed.\n";
