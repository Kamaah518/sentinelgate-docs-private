# Webhook Guide

SentinelGate sends webhook callbacks to notify your server when payment status changes.

---

## Overview

When a payment is completed, fails, or changes status, SentinelGate sends an HTTP POST request to your configured `callback_url` with the transaction details.

---

## Webhook Payload

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_abc123",
  "merchant_id": "your-merchant-id",
  "amount_cents": 5000,
  "currency": "USD",
  "status": "CAPTURED",
  "reference": "ref_20260304_001",
  "provider_ref": "121676133",
  "metadata": {},
  "timestamp": "2026-03-04T01:19:45.123Z"
}
```

## Event Types

| Event | Description |
|-------|-------------|
| `payment.captured` | Payment completed successfully |
| `payment.failed` | Payment failed or was declined |
| `payment.pending` | Payment initiated, awaiting action |
| `payment.refunded` | Refund processed |
| `payment.disputed` | Dispute or chargeback filed |

---

## Signature Verification

Every webhook includes an HMAC-SHA512 signature in the header:

```
x-sentinel-signature: sha512=abc123def456...
```

### Node.js Verification

```javascript
const crypto = require('crypto');

function verifyWebhook(body, signature, secret) {
  const hash = crypto
    .createHmac('sha512', secret)
    .update(JSON.stringify(body))
    .digest('hex');
  return `sha512=${hash}` === signature;
}

// In your webhook handler
app.post('/webhooks/payment', (req, res) => {
  const sig = req.headers['x-sentinel-signature'];
  if (!verifyWebhook(req.body, sig, 'your_webhook_secret')) {
    return res.status(401).send('Invalid signature');
  }

  const { event, transaction_id, status } = req.body;

  switch (event) {
    case 'payment.captured':
      // Mark order as paid
      break;
    case 'payment.failed':
      // Mark order as failed
      break;
  }

  res.status(200).json({ received: true });
});
```

### PHP Verification

```php
function verifyWebhook($body, $signature, $secret) {
    $hash = 'sha512=' . hash_hmac('sha512', json_encode($body), $secret);
    return hash_equals($hash, $signature);
}

$body = json_decode(file_get_contents('php://input'), true);
$sig = $_SERVER['HTTP_X_SENTINEL_SIGNATURE'] ?? '';

if (!verifyWebhook($body, $sig, 'your_webhook_secret')) {
    http_response_code(401);
    exit('Invalid signature');
}

// Process the webhook
switch ($body['event']) {
    case 'payment.captured':
        // Mark order as paid
        break;
    case 'payment.failed':
        // Mark order as failed
        break;
}

http_response_code(200);
echo json_encode(['received' => true]);
```

### Python Verification

```python
import hmac
import hashlib
import json

def verify_webhook(body, signature, secret):
    digest = hmac.new(
        secret.encode(),
        json.dumps(body).encode(),
        hashlib.sha512
    ).hexdigest()
    return f"sha512={digest}" == signature

# In your Flask handler
@app.route('/webhooks/payment', methods=['POST'])
def webhook():
    sig = request.headers.get('x-sentinel-signature', '')
    if not verify_webhook(request.json, sig, 'your_webhook_secret'):
        return 'Invalid signature', 401

    event = request.json.get('event')
    if event == 'payment.captured':
        # Mark order as paid
        pass

    return {'received': True}, 200
```

---

## Best Practices

1. **Always verify signatures** — Never process unverified webhooks
2. **Respond with 200 quickly** — Process asynchronously if needed; SentinelGate expects a 200 within 10 seconds
3. **Handle duplicates** — Use `transaction_id` as an idempotency key; SentinelGate may retry failed deliveries
4. **Use HTTPS** — Webhooks are only sent to HTTPS endpoints
5. **Log everything** — Store raw webhook payloads for debugging
6. **Don't rely solely on webhooks** — Poll `/v1/transaction/:id` as a fallback

---

## Retry Policy

If your endpoint returns a non-2xx response or times out, SentinelGate retries:

| Attempt | Delay |
|---------|-------|
| 1st retry | 1 minute |
| 2nd retry | 5 minutes |
| 3rd retry | 30 minutes |
| 4th retry | 2 hours |
| 5th retry | 12 hours |

After 5 failed attempts, the webhook is marked as failed. Check the admin console for failed deliveries.

---

© 2026 SentinelGate — Whyte AG Group
