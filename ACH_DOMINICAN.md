<?php
/**
 * SentinelGate Webhook Handler (PHP)
 * Handles callbacks for all 25 payment methods
 *
 * Deploy to: https://yoursite.com/webhooks/handler.php
 */

$WEBHOOK_SECRET = getenv('SG_WEBHOOK_SECRET') ?: 'your_webhook_secret';

// Read raw body
$rawBody = file_get_contents('php://input');
$body = json_decode($rawBody, true);

if (!$body) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid JSON']));
}

// Verify signature
$signature = $_SERVER['HTTP_X_SENTINEL_SIGNATURE'] ?? '';
if ($signature) {
    $expected = 'sha512=' . hash_hmac('sha512', $rawBody, $WEBHOOK_SECRET);
    if (!hash_equals($expected, $signature)) {
        http_response_code(401);
        exit(json_encode(['error' => 'Invalid signature']));
    }
}

// Extract fields
$event          = $body['event'] ?? 'unknown';
$transactionId  = $body['transaction_id'] ?? '';
$status         = $body['status'] ?? '';
$rail           = $body['rail'] ?? '';
$amountCents    = $body['amount_cents'] ?? 0;
$currency       = $body['currency'] ?? '';
$metadata       = $body['metadata'] ?? [];

// Log
$logLine = sprintf(
    "[%s] %s | %s | %s | %d %s | %s\n",
    date('Y-m-d H:i:s'), $rail, $event, $transactionId, $amountCents, $currency,
    json_encode($metadata)
);
file_put_contents(__DIR__ . '/webhook.log', $logLine, FILE_APPEND);

// Handle by event type
switch ($event) {
    case 'payment.captured':
        // Payment successful — fulfill the order
        // Example: updateOrderStatus($transactionId, 'PAID');
        break;

    case 'payment.failed':
        // Payment failed
        // Example: updateOrderStatus($transactionId, 'FAILED');
        break;

    case 'payment.pending':
        // Still processing
        break;

    case 'payment.refunded':
        // Refund processed
        // Example: processRefund($transactionId);
        break;

    default:
        error_log("Unknown webhook event: {$event}");
}

// Always respond 200 quickly
http_response_code(200);
echo json_encode(['received' => true]);
