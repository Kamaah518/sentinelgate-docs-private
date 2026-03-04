# SIPARD / LBTR — Real-Time Gross Settlement (Dominican Republic)

Dominican Republic's RTGS system operated by the Central Bank (BCRD). SIPARD handles large-value real-time interbank transfers. LBTR is the underlying settlement mechanism.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `SIPARD_DO` |
| Currency | DOP |
| Settlement | Real-time |
| Min Amount | DOP 1 |
| Max Amount | DOP 50,000,000/txn |
| Availability | Business hours (8am-5pm AST) |

## How It Works

```
Customer → Provides Bank Account → SIPARD Transfer Initiated → Real-Time Settlement → Confirmation → Callback
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
    "currency": "DOP",
    "merchant_id": "your-merchant-id",
    "rail": "SIPARD_DO",
    "email": "customer@example.com",
    "reference": "sipard_do_ref_001",
    "callback_url": "https://yoursite.com/webhooks/sipard_do",
    "metadata": {
      "account_number": "1234567890",
      "bank_code": "BPD",
      "account_name": "Maria Gonzalez"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_sipard_do_001",
  "status": "PENDING",
  "next_action": {
    "type": "SIPARD_INITIATED",
    "sipard_ref": "LBTR20260304001234"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeSIPARDDO(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'DOP',
    merchant_id: process.env.MERCHANT_ID, rail: 'SIPARD_DO', email,
    reference: `sipard_do_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/sipard_do', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  return response.data;
}

// Test
chargeSIPARDDO(50.00, 'buyer@example.com', {account_number: '1234567890', bank_code: 'BPD', account_name: 'Maria Gonzalez'}).then(console.log);
```

### PHP

```php
<?php
function chargeSIPARDDO($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'DOP',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'SIPARD_DO',
            'email' => $email, 'reference' => 'sipard_do_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/sipard_do',
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

def charge_sipard_do(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'DOP',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'SIPARD_DO',
        'email': email, 'reference': f'sipard_do_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/sipard_do',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `1234567890` | `BPD` | Instant success |
| `0000000001` | `BPD` | Insufficient funds |
| `9999999999` | `BPD` | Account not found |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `ACCOUNT_NOT_FOUND` | Account not found |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `OUTSIDE_HOURS` | SIPARD only operates during business hours |
| `BANK_UNAVAILABLE` | Bank temporarily unavailable |

---

© 2026 SentinelGate — Whyte AG Group
