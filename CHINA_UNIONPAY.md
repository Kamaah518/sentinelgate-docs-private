# SEPA — Single Euro Payments Area

Pan-European bank transfer system covering 36 countries. Supports instant credit transfers (SCT Inst) and direct debits (SDD).

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `SEPA` |
| Currency | EUR |
| Settlement | Instant (SCT Inst) / 1-2 days (SDD) |
| Min Amount | €0.01 |
| Max Amount | €999,999,999.99 |
| Availability | 24/7 (SCT Inst) / Business days (SDD) |

## How It Works

```
Customer → Provides IBAN → SEPA Direct Debit Mandate → Bank Debits → Settlement → Callback
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
    "currency": "EUR",
    "merchant_id": "your-merchant-id",
    "rail": "SEPA",
    "email": "customer@example.com",
    "reference": "sepa_ref_001",
    "callback_url": "https://yoursite.com/webhooks/sepa",
    "metadata": {
      "iban": "DE89370400440532013000",
      "bic": "COBADEFFXXX",
      "account_holder": "Hans Mueller",
      "mandate_ref": "MNDT-2026-001"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_sepa_001",
  "status": "PENDING",
  "next_action": {
    "type": "SEPA_MANDATE_CREATED",
    "mandate_ref": "MNDT-2026-001",
    "estimated_settlement": "2026-03-06T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeSEPA(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'EUR',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'SEPA',
    email,
    reference: `sepa_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/sepa',
    metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeSEPA(50.00, 'buyer@example.com', {iban: 'DE89370400440532013000', bic: 'COBADEFFXXX', account_holder: 'Hans Mueller'}).then(console.log);
```

### PHP

```php
<?php
function chargeSEPA($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'EUR',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'SEPA',
            'email' => $email, 'reference' => 'sepa_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/sepa',
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

def charge_sepa(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'EUR',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'SEPA',
        'email': email, 'reference': f'sepa_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/sepa',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Test IBAN | Result |
|----------|--------|
| `DE89370400440532013000` | Succeeds |
| `DE00000000000000000001` | Insufficient funds |
| `DE99999999999999999999` | Account not found |
| `FR7630006000011234567890189` | French account succeeds |

Use `sk_test_` prefix API key for sandbox mode. Sandbox transactions settle instantly.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_IBAN` | IBAN format is invalid |
| `IBAN_NOT_FOUND` | Account does not exist |
| `MANDATE_REJECTED` | Customer rejected the mandate |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `SEPA_RETURN` | Payment returned by bank |

---

© 2026 SentinelGate — Whyte AG Group
