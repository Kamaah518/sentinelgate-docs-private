# eCheck — Electronic Check (USA)

Digital version of a paper check. Uses the ACH network but follows check processing rules with longer settlement windows and different dispute rights.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ECHECK_US` |
| Currency | USD |
| Settlement | 3-5 business days |
| Min Amount | $1.00 |
| Max Amount | $500,000/txn |
| Availability | Business days |

## How It Works

```
Customer → Provides Check Details → eCheck Created → ACH Clearing → Bank Debits → Settlement → Callback
```

1. Customer provides checking account number, routing number, and check number
2. SentinelGate creates an electronic check image
3. Check is submitted to the ACH network for clearing
4. Customer's bank processes the debit
5. Funds settle in 3-5 business days

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 5000,
    "currency": "USD",
    "merchant_id": "your-merchant-id",
    "rail": "ECHECK_US",
    "email": "customer@example.com",
    "reference": "echeck_us_ref_001",
    "callback_url": "https://yoursite.com/webhooks/echeck_us",
    "metadata": {
      "account_number": "1234567890",
      "routing_number": "021000021",
      "check_number": "1001",
      "account_holder": "Jane Doe",
      "account_type": "checking"}
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_echeck_us_001",
  "status": "PENDING",
  "next_action": {
    "type": "ECHECK_SUBMITTED",
    "check_number": "1001",
    "estimated_settlement": "2026-03-09T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createECHECKUSPayment(amount, email, metadata) {
  try {
    const response = await axios.post('https://sentinelgate.biz/v1/charge', {
      amount_cents: Math.round(amount * 100),
      currency: 'USD',
      merchant_id: process.env.MERCHANT_ID,
      rail: 'ECHECK_US',
      email,
      reference: `echeck_us_${Date.now()}`,
      callback_url: 'https://yoursite.com/webhooks/echeck_us',
      metadata
    }, {
      headers: {
        'x-api-key': process.env.SG_API_KEY,
        'Content-Type': 'application/json'
      }
    });

    console.log('Transaction ID:', response.data.payment_intent_id);
    console.log('Status:', response.data.status);
    return response.data;
  } catch (error) {
    console.error('Payment failed:', error.response?.data || error.message);
    throw error;
  }
}

// Test
createECHECKUPayment(750.00, 'buyer@example.com', {
  account_number: '1234567890',
  routing_number: '021000021',
  check_number: '1001',
  account_holder: 'Jane Doe',
  account_type: 'checking'
});
```

### PHP

```php
<?php
function createECHECKUSPayment($amount, $email, $metadata) {
    $payload = [
        'amount_cents' => round($amount * 100),
        'currency' => 'USD',
        'merchant_id' => getenv('MERCHANT_ID'),
        'rail' => 'ECHECK_US',
        'email' => $email,
        'reference' => 'echeck_us_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/echeck_us',
        'metadata' => $metadata
    ];

    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . getenv('SG_API_KEY')
        ],
        CURLOPT_RETURNTRANSFER => true,
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response;
}

// Test
$result = createECHECKUPayment(750.00, 'buyer@example.com', [
    'account_number' => '1234567890',
    'routing_number' => '021000021',
    'check_number' => '1001',
    'account_holder' => 'Jane Doe',
    'account_type' => 'checking'
]);
print_r($result);
```

### Python

```python
import requests, os, time

def create_echeck_us_payment(amount, email, metadata):
    response = requests.post(
        'https://sentinelgate.biz/v1/charge',
        json={
            'amount_cents': round(amount * 100),
            'currency': 'USD',
            'merchant_id': os.environ['MERCHANT_ID'],
            'rail': 'ECHECK_US',
            'email': email,
            'reference': f'echeck_us_{int(time.time())}',
            'callback_url': 'https://yoursite.com/webhooks/echeck_us',
            'metadata': metadata
        },
        headers={
            'x-api-key': os.environ['SG_API_KEY'],
            'Content-Type': 'application/json'
        }
    )
    return response.json()

# Test
result = create_echeck_us_payment(750.00, 'buyer@example.com', {
    'account_number': '1234567890',
    'routing_number': '021000021',
    'check_number': '1001',
    'account_holder': 'Jane Doe',
    'account_type': 'checking'
})
print(result)
```

---

## Testing

| Test Account | Result |
|-------------|--------|
| `1234567890` | Succeeds in 3 days |
| `0000000001` | NSF (Non-Sufficient Funds) |
| `8888888888` | Check returned (account closed) |

Sandbox settles instantly. Use `sk_test_` prefix.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_echeck_us_001",
  "status": "CAPTURED",
  "amount_cents": 5000,
  "currency": "USD",
  "rail": "ECHECK_US",
  "metadata": {
    "check_number": "1001",
    "trace": "ECK20260304001"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `INVALID_ROUTING` | Routing number invalid |
| `NSF` | Non-sufficient funds |
| `ACCOUNT_CLOSED` | Account is closed |
| `STOP_PAYMENT` | Stop payment order on check |

---

© 2026 SentinelGate — Whyte AG Group
