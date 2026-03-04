# GIP — GhIPSS Instant Pay (Ghana)

Ghana's real-time gross settlement system for instant interbank transfers. Operated by Ghana Interbank Payment and Settlement Systems (GhIPSS).

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `GIP_GH` |
| Currency | GHS |
| Settlement | Real-time (seconds) |
| Min Amount | GHS 1 |
| Max Amount | GHS 100,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Account Number + Bank → GIP Transfer Initiated → Instant Debit → Confirmation → Callback
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
    "currency": "GHS",
    "merchant_id": "your-merchant-id",
    "rail": "GIP_GH",
    "email": "customer@example.com",
    "reference": "gip_gh_ref_001",
    "callback_url": "https://yoursite.com/webhooks/gip_gh",
    "metadata": {
      "account_number": "1234567890123",
      "bank_code": "GCB",
      "account_name": "Ama Mensah"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_gip_gh_001",
  "status": "PENDING",
  "next_action": {
    "type": "GIP_INITIATED",
    "ghipss_ref": "GIP20260304001234"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeGIPGH(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'GHS',
    merchant_id: process.env.MERCHANT_ID, rail: 'GIP_GH', email,
    reference: `gip_gh_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/gip_gh', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeGIPGH(50.00, 'buyer@example.com', {account_number: '1234567890123', bank_code: 'GCB', account_name: 'Ama Mensah'}).then(console.log);
```

### PHP

```php
<?php
function chargeGIPGH($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'GHS',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'GIP_GH',
            'email' => $email, 'reference' => 'gip_gh_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/gip_gh',
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

def charge_gip_gh(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'GHS',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'GIP_GH',
        'email': email, 'reference': f'gip_gh_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/gip_gh',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Account | Bank | Result |
|---------|------|--------|
| `1234567890123` | `GCB` | Instant success |
| `0000000000001` | `GCB` | Insufficient funds |
| `9999999999999` | `GCB` | Account not found |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ACCOUNT` | Account number invalid |
| `ACCOUNT_NOT_FOUND` | Account not found |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `GIP_TIMEOUT` | GhIPSS timeout |
| `BANK_UNAVAILABLE` | Bank temporarily unavailable |

---

© 2026 SentinelGate — Whyte AG Group
