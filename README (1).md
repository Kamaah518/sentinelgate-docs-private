# RNDPS — Rwanda National Digital Payment System

Rwanda's national digital payment infrastructure enabling real-time transfers between banks, mobile wallets, and government systems. Operated by the National Bank of Rwanda.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `RNDPS_RW` |
| Currency | RWF |
| Settlement | Real-time |
| Min Amount | RWF 100 |
| Max Amount | RWF 50,000,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Account/Wallet → RNDPS Routes → Instant Transfer → Confirmation → Callback
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
    "currency": "RWF",
    "merchant_id": "your-merchant-id",
    "rail": "RNDPS_RW",
    "email": "customer@example.com",
    "reference": "rndps_rw_ref_001",
    "callback_url": "https://yoursite.com/webhooks/rndps_rw",
    "metadata": {
      "account_number": "100012345678",
      "bank_code": "BK",
      "account_name": "Jean Uwimana",
      "payment_type": "BANK"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_rndps_rw_001",
  "status": "PENDING",
  "next_action": {
    "type": "RNDPS_INITIATED",
    "rndps_ref": "RW20260304001234"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeRNDPSRW(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'RWF',
    merchant_id: process.env.MERCHANT_ID, rail: 'RNDPS_RW', email,
    reference: `rndps_rw_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/rndps_rw', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeRNDPSRW(50.00, 'buyer@example.com', {account_number: '100012345678', bank_code: 'BK', account_name: 'Jean Uwimana'}).then(console.log);
```

### PHP

```php
<?php
function chargeRNDPSRW($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'RWF',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'RNDPS_RW',
            'email' => $email, 'reference' => 'rndps_rw_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/rndps_rw',
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

def charge_rndps_rw(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'RWF',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'RNDPS_RW',
        'email': email, 'reference': f'rndps_rw_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/rndps_rw',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `100012345678` | `BK` | Instant success |
| `100000000001` | `BK` | Insufficient funds |
| `100099999999` | `BK` | Account not found |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account format invalid |
| `ACCOUNT_NOT_FOUND` | Account not found |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `BANK_UNAVAILABLE` | Bank temporarily unavailable |
| `RNDPS_TIMEOUT` | System timeout |

---

© 2026 SentinelGate — Whyte AG Group
