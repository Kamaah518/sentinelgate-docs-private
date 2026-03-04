# Interac — Interac e-Transfer (Canada)

Canada's most popular digital money transfer system. Enables real-time transfers between Canadian bank accounts using email or phone number.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `INTERAC` |
| Currency | CAD |
| Settlement | Real-time / minutes |
| Min Amount | $0.01 CAD |
| Max Amount | $25,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Receives Interac Request → Clicks Email/SMS Link → Logs into Bank → Approves → Funds Transfer → Callback
```

1. SentinelGate sends an Interac e-Transfer request to the customer
2. Customer receives notification via email or SMS
3. Customer clicks the link and logs into their online banking
4. Customer approves the payment
5. Funds transfer instantly

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 10000,
    "currency": "CAD",
    "merchant_id": "your-merchant-id",
    "rail": "INTERAC",
    "email": "customer@example.com",
    "reference": "interac_ref_001",
    "callback_url": "https://yoursite.com/webhooks/interac",
    "metadata": {
      "customer_name": "Alex Thompson",
      "message": "Payment for Order #1234"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_interac_001",
  "status": "PENDING",
  "next_action": {
    "type": "INTERAC_REQUEST_SENT",
    "sent_to": "customer@example.com",
    "expires_in": 1800
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createInteracPayment(amount, email, customerName, message) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'CAD',
    merchant_id: process.env.MERCHANT_ID, rail: 'INTERAC', email,
    reference: `interac_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/interac',
    metadata: { customer_name: customerName, message }
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  return response.data;
}

createInteracPayment(100.00, 'buyer@example.com', 'Alex Thompson', 'Order #1234').then(console.log);
```

### PHP

```php
<?php
function createInteracPayment($amount, $email, $name, $message) {
    $payload = [
        'amount_cents' => round($amount * 100), 'currency' => 'CAD',
        'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'INTERAC',
        'email' => $email, 'reference' => 'interac_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/interac',
        'metadata' => ['customer_name' => $name, 'message' => $message]
    ];
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch); return $res;
}
print_r(createInteracPayment(100.00, 'buyer@example.com', 'Alex Thompson', 'Order #1234'));
```

### Python

```python
import requests, os, time

def create_interac_payment(amount, email, customer_name, message):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'CAD',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'INTERAC',
        'email': email, 'reference': f'interac_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/interac',
        'metadata': {'customer_name': customer_name, 'message': message}
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()

print(create_interac_payment(100.00, 'buyer@example.com', 'Alex Thompson', 'Order #1234'))
```

---

## Testing

| Test Email | Result |
|-----------|--------|
| `success@test.com` | Approved instantly |
| `decline@test.com` | Customer declines |
| `timeout@test.com` | Expires after 30 min |
| `cancel@test.com` | Customer cancels |

Use `sk_test_` prefix API key for sandbox.

---

## Error Codes

| Code | Description |
|------|-------------|
| `REQUEST_DECLINED` | Customer declined the e-Transfer |
| `REQUEST_EXPIRED` | e-Transfer request expired |
| `INVALID_EMAIL` | Customer email is invalid |
| `DAILY_LIMIT_EXCEEDED` | Customer exceeded daily Interac limit |

---

© 2026 SentinelGate — Whyte AG Group
