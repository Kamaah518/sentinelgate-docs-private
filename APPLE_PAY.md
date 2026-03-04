# PIX — Instant Payment (Brazil)

Brazil's instant payment system by the Central Bank. Real-time 24/7 transfers using keys (CPF, email, phone, or random).

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `PIX` |
| Currency | BRL |
| Settlement | Real-time (seconds) |
| Min Amount | R$0.01 |
| Max Amount | No system limit (bank-defined) |
| Availability | 24/7 |

## How It Works

```
Customer → Scans PIX QR Code or Enters PIX Key → Confirms in Banking App → Instant Transfer → Callback
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
    "currency": "BRL",
    "merchant_id": "your-merchant-id",
    "rail": "PIX",
    "email": "customer@example.com",
    "reference": "pix_ref_001",
    "callback_url": "https://yoursite.com/webhooks/pix",
    "metadata": {
      "pix_key": "customer@email.com",
      "pix_key_type": "EMAIL",
      "customer_name": "Maria Silva",
      "customer_cpf": "12345678901"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_pix_001",
  "status": "PENDING",
  "next_action": {
    "type": "PIX_QR_GENERATED",
    "qr_code": "00020126580014br.gov.bcb.pix...",
    "qr_image_url": "https://sentinelgate.biz/pix/qr/sg_txn_pix_001.png",
    "expires_in": 600
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargePIX(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'BRL',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'PIX',
    email,
    reference: `pix_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/pix',
    metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargePIX(50.00, 'buyer@example.com', {pix_key: 'customer@email.com', pix_key_type: 'EMAIL', customer_cpf: '12345678901'}).then(console.log);
```

### PHP

```php
<?php
function chargePIX($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'BRL',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'PIX',
            'email' => $email, 'reference' => 'pix_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/pix',
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

def charge_pix(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'BRL',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'PIX',
        'email': email, 'reference': f'pix_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/pix',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Test PIX Key | Result |
|-------------|--------|
| `success@test.com` | Instant success |
| `failure@test.com` | Declined |
| `timeout@test.com` | Expires |
| `12345678901` (CPF) | Succeeds |

Use `sk_test_` prefix API key for sandbox mode. Sandbox transactions settle instantly.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_PIX_KEY` | PIX key format is invalid |
| `PIX_KEY_NOT_FOUND` | PIX key not registered |
| `QR_EXPIRED` | QR code has expired |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `PIX_REJECTED` | Transfer rejected by bank |

---

© 2026 SentinelGate — Whyte AG Group
