# ACH — Automated Clearing House (Dominican Republic)

Dominican Republic's ACH system operated by CEVALDOM. Batch-processed bank-to-bank transfers for lower-value payments with T+1 settlement.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ACH_DO` |
| Currency | DOP |
| Settlement | T+1 (next business day) |
| Min Amount | DOP 1 |
| Max Amount | DOP 10,000,000/txn |
| Availability | Business days |

## How It Works

```
Customer → Provides Account Details → ACH Debit Submitted → Next-Day Processing → Settlement → Callback
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
    "rail": "ACH_DO",
    "email": "customer@example.com",
    "reference": "ach_do_ref_001",
    "callback_url": "https://yoursite.com/webhooks/ach_do",
    "metadata": {
      "account_number": "1234567890",
      "bank_code": "BPD",
      "account_holder": "Carlos Perez"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_ach_do_001",
  "status": "PENDING",
  "next_action": {
    "type": "ACH_DO_INITIATED",
    "estimated_settlement": "2026-03-05T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeACHDO(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'DOP',
    merchant_id: process.env.MERCHANT_ID, rail: 'ACH_DO', email,
    reference: `ach_do_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/ach_do', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  return response.data;
}

// Test
chargeACHDO(50.00, 'buyer@example.com', {account_number: '1234567890', bank_code: 'BPD', account_holder: 'Carlos Perez'}).then(console.log);
```

### PHP

```php
<?php
function chargeACHDO($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'DOP',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'ACH_DO',
            'email' => $email, 'reference' => 'ach_do_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/ach_do',
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

def charge_ach_do(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'DOP',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'ACH_DO',
        'email': email, 'reference': f'ach_do_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/ach_do',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `1234567890` | `BPD` | Settles T+1 |
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
| `ACH_RETURN` | Payment returned by bank |
| `INVALID_BANK_CODE` | Bank code not recognized |

---

© 2026 SentinelGate — Whyte AG Group
