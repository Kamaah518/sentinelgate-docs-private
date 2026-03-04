# Snipe Móvil

Mobile payment collection system for quick peer-to-merchant transfers. Customers pay by entering their mobile number and confirming via SMS or app.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `SNIPE` |
| Currency | MXN |
| Settlement | Real-time |
| Min Amount | MXN 1 |
| Max Amount | MXN 100,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Enters Mobile Number → Receives SMS/App Notification → Confirms Payment → Instant Transfer → Callback
```


---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 5000,
    "currency": "MXN",
    "merchant_id": "your-merchant-id",
    "rail": "SNIPE",
    "email": "customer@example.com",
    "reference": "snipe_ref_001",
    "callback_url": "https://yoursite.com/webhooks/snipe",
    "metadata": {
      "phone": "+5215512345678",
      "customer_name": "Carlos Rodriguez"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_snipe_001",
  "status": "PENDING",
  "next_action": {
    "type": "SNIPE_REQUEST_SENT",
    "sent_to": "+5215512345678",
    "expires_in": 300
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeSNIPE(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'MXN',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'SNIPE',
    email,
    reference: `snipe_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/snipe',
    metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeSNIPE(50.00, 'buyer@example.com', {phone: '+5215512345678', customer_name: 'Carlos Rodriguez'}).then(console.log);
```

### PHP

```php
<?php
function chargeSNIPE($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'MXN',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'SNIPE',
            'email' => $email, 'reference' => 'snipe_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/snipe',
            'metadata' => $metadata
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch); return $res;
}
```

### Python

```python
import requests, os, time

def charge_snipe(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'MXN',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'SNIPE',
        'email': email, 'reference': f'snipe_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/snipe',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Test Phone | Result |
|-----------|--------|
| `+5215500000001` | Succeeds |
| `+5215500000002` | Declined |
| `+5215500000003` | Timeout |

Use `sk_test_` prefix API key for sandbox mode. Sandbox transactions settle instantly.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_PHONE` | Phone number format invalid |
| `PHONE_NOT_REGISTERED` | Phone not registered |
| `REQUEST_DECLINED` | Customer declined |
| `REQUEST_EXPIRED` | Request timed out |
| `DAILY_LIMIT` | Daily limit exceeded |

---

© 2026 SentinelGate — Whyte AG Group
