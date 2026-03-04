# TISS — Tanzania Interbank Settlement System

Tanzania's real-time interbank settlement system operated by the Bank of Tanzania. Supports both large-value (RTGS) and retail instant payments.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `TISS_TZ` |
| Currency | TZS |
| Settlement | Real-time |
| Min Amount | TZS 1,000 |
| Max Amount | TZS 100,000,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Account + Bank → TISS Transfer → Bank Debits → Instant Settlement → Callback
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
    "currency": "TZS",
    "merchant_id": "your-merchant-id",
    "rail": "TISS_TZ",
    "email": "customer@example.com",
    "reference": "tiss_tz_ref_001",
    "callback_url": "https://yoursite.com/webhooks/tiss_tz",
    "metadata": {
      "account_number": "0123456789012",
      "bank_code": "CRDB",
      "account_name": "Joseph Mwamba"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_tiss_tz_001",
  "status": "PENDING",
  "next_action": {
    "type": "TISS_INITIATED",
    "tiss_ref": "TZ20260304001234"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeTISSTZ(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'TZS',
    merchant_id: process.env.MERCHANT_ID, rail: 'TISS_TZ', email,
    reference: `tiss_tz_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/tiss_tz', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeTISSTZ(50.00, 'buyer@example.com', {account_number: '0123456789012', bank_code: 'CRDB', account_name: 'Joseph Mwamba'}).then(console.log);
```

### PHP

```php
<?php
function chargeTISSTZ($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'TZS',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'TISS_TZ',
            'email' => $email, 'reference' => 'tiss_tz_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/tiss_tz',
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

def charge_tiss_tz(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'TZS',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'TISS_TZ',
        'email': email, 'reference': f'tiss_tz_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/tiss_tz',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `0123456789012` | `CRDB` | Instant success |
| `0000000000001` | `CRDB` | Insufficient funds |
| `9999999999999` | `CRDB` | Account not found |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `ACCOUNT_NOT_FOUND` | Account not found |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `TISS_TIMEOUT` | System timeout |
| `BANK_UNAVAILABLE` | Bank offline |

---

© 2026 SentinelGate — Whyte AG Group
