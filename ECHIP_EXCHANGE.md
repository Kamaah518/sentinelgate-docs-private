# NIP — NIBSS Instant Payment (Nigeria)

Nigeria's real-time interbank transfer system operated by NIBSS (Nigeria Inter-Bank Settlement System). Processes over 90% of electronic fund transfers in Nigeria.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `NIP_NG` |
| Currency | NGN |
| Settlement | Real-time (seconds) |
| Min Amount | ₦1 |
| Max Amount | ₦10,000,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Account Number + Bank → NIP Transfer Initiated → Instant Debit → Confirmation → Callback
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
    "currency": "NGN",
    "merchant_id": "your-merchant-id",
    "rail": "NIP_NG",
    "email": "customer@example.com",
    "reference": "nip_ng_ref_001",
    "callback_url": "https://yoursite.com/webhooks/nip_ng",
    "metadata": {
      "account_number": "0123456789",
      "bank_code": "058",
      "account_name": "Emeka Obi"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_nip_ng_001",
  "status": "PENDING",
  "next_action": {
    "type": "NIP_INITIATED",
    "session_id": "000015260304120000000001",
    "estimated_completion": "seconds"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeNIPNG(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'NGN',
    merchant_id: process.env.MERCHANT_ID, rail: 'NIP_NG', email,
    reference: `nip_ng_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/nip_ng', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeNIPNG(50.00, 'buyer@example.com', {account_number: '0123456789', bank_code: '058', account_name: 'Emeka Obi'}).then(console.log);
```

### PHP

```php
<?php
function chargeNIPNG($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'NGN',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'NIP_NG',
            'email' => $email, 'reference' => 'nip_ng_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/nip_ng',
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

def charge_nip_ng(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'NGN',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'NIP_NG',
        'email': email, 'reference': f'nip_ng_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/nip_ng',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank Code | Result |
|---------|-----------|--------|
| `0123456789` | `058` | Instant success |
| `0000000001` | `058` | Insufficient funds |
| `9999999999` | `058` | Account not found |
| `5555555555` | `058` | Name mismatch |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `ACCOUNT_NOT_FOUND` | Account does not exist at bank |
| `NAME_MISMATCH` | Account name doesn't match |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `NIP_TIMEOUT` | NIBSS timeout — retry |
| `BANK_UNAVAILABLE` | Destination bank temporarily unavailable |

---

© 2026 SentinelGate — Whyte AG Group
