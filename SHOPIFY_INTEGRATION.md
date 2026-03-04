# SINPE Móvil (Costa Rica)

Costa Rica's instant mobile payment system operated by the Central Bank (BCCR). Links phone numbers to bank accounts for real-time transfers.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `SINPE_CR` |
| Currency | CRC |
| Settlement | Real-time (seconds) |
| Min Amount | ₡1 |
| Max Amount | ₡2,000,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Phone Number → SINPE Request Sent → Customer Confirms via Banking App → Instant Transfer → Callback
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
    "currency": "CRC",
    "merchant_id": "your-merchant-id",
    "rail": "SINPE_CR",
    "email": "customer@example.com",
    "reference": "sinpe_cr_ref_001",
    "callback_url": "https://yoursite.com/webhooks/sinpe_cr",
    "metadata": {
      "phone": "+50612345678",
      "customer_name": "Ana Ramirez"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_sinpe_cr_001",
  "status": "PENDING",
  "next_action": {
    "type": "SINPE_REQUEST_SENT",
    "phone": "+50612345678",
    "expires_in": 300
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeSINPECR(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'CRC',
    merchant_id: process.env.MERCHANT_ID, rail: 'SINPE_CR', email,
    reference: `sinpe_cr_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/sinpe_cr', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  return response.data;
}

// Test
chargeSINPECR(50.00, 'buyer@example.com', {phone: '+50612345678', customer_name: 'Ana Ramirez'}).then(console.log);
```

### PHP

```php
<?php
function chargeSINPECR($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'CRC',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'SINPE_CR',
            'email' => $email, 'reference' => 'sinpe_cr_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/sinpe_cr',
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

def charge_sinpe_cr(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'CRC',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'SINPE_CR',
        'email': email, 'reference': f'sinpe_cr_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/sinpe_cr',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Test Phone | Result |
|-----------|--------|
| `+50600000001` | Instant success |
| `+50600000002` | Declined |
| `+50600000003` | Timeout |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_PHONE` | Phone number format invalid |
| `PHONE_NOT_LINKED` | Phone not linked to SINPE |
| `REQUEST_DECLINED` | Customer declined |
| `REQUEST_EXPIRED` | Request expired |
| `INSUFFICIENT_FUNDS` | Insufficient funds |

---

© 2026 SentinelGate — Whyte AG Group
