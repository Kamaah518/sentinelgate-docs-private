# PesaLink (Kenya)

Kenya's real-time interbank transfer system linking all 42 commercial banks. Operated by Integrated Payment Services Limited (IPSL). Alternative to RTGS for smaller transfers.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `PESALINK` |
| Currency | KES |
| Settlement | Real-time (seconds) |
| Min Amount | KES 10 |
| Max Amount | KES 999,999/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Bank Account + Bank Code → PesaLink Transfer → Instant Debit → Confirmation → Callback
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
    "currency": "KES",
    "merchant_id": "your-merchant-id",
    "rail": "PESALINK",
    "email": "customer@example.com",
    "reference": "pesalink_ref_001",
    "callback_url": "https://yoursite.com/webhooks/pesalink",
    "metadata": {
      "account_number": "1234567890",
      "bank_code": "KCB",
      "account_name": "James Mwangi"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_pesalink_001",
  "status": "PENDING",
  "next_action": {
    "type": "PESALINK_INITIATED",
    "pesalink_ref": "PL20260304001234"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargePESALINK(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'KES',
    merchant_id: process.env.MERCHANT_ID, rail: 'PESALINK', email,
    reference: `pesalink_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/pesalink', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargePESALINK(50.00, 'buyer@example.com', {account_number: '1234567890', bank_code: 'KCB', account_name: 'James Mwangi'}).then(console.log);
```

### PHP

```php
<?php
function chargePESALINK($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'KES',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'PESALINK',
            'email' => $email, 'reference' => 'pesalink_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/pesalink',
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

def charge_pesalink(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'KES',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'PESALINK',
        'email': email, 'reference': f'pesalink_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/pesalink',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `1234567890` | `KCB` | Instant success |
| `0000000001` | `KCB` | Insufficient funds |
| `9999999999` | `KCB` | Account not found |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `ACCOUNT_NOT_FOUND` | Account not found at bank |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `BANK_UNAVAILABLE` | Bank temporarily offline |
| `DAILY_LIMIT` | PesaLink daily limit exceeded |

---

© 2026 SentinelGate — Whyte AG Group
