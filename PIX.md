# Bank-to-Bank Transfer Collections

Direct bank-to-bank transfer collection system. Initiate debits from customer bank accounts across multiple countries and currencies.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `B2B_COLLECT` |
| Currency | Multi-currency |
| Settlement | 1-5 business days (varies by corridor) |
| Min Amount | $1.00 |
| Max Amount | No fixed limit |
| Availability | Business days |

## How It Works

```
Merchant → Submits Collection Request → SentinelGate Routes to Local Rail → Customer Bank Debited → Settlement → Callback
```


**Supported corridors:** US, Canada, UK, EU (SEPA), Ghana, Kenya, Nigeria, Rwanda, Tanzania, Dominican Republic, Costa Rica, Mexico, Brazil.

SentinelGate automatically routes to the optimal local rail (ACH, SEPA, NIP, PIX, etc.) based on the destination country.

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 5000,
    "currency": "Multi-currency",
    "merchant_id": "your-merchant-id",
    "rail": "B2B_COLLECT",
    "email": "customer@example.com",
    "reference": "b2b_collect_ref_001",
    "callback_url": "https://yoursite.com/webhooks/b2b_collect",
    "metadata": {
      "source_country": "US",
      "source_currency": "USD",
      "source_bank_code": "021000021",
      "account_number": "1234567890",
      "account_holder": "John Doe",
      "purpose": "GOODS_PAYMENT"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_b2b_collect_001",
  "status": "PENDING",
  "next_action": {
    "type": "COLLECTION_INITIATED",
    "corridor": "US_DOMESTIC",
    "estimated_settlement": "2026-03-07T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeB2BCOLLECT(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'Multi-currency',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'B2B_COLLECT',
    email,
    reference: `b2b_collect_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/b2b_collect',
    metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeB2BCOLLECT(50.00, 'buyer@example.com', {source_country: 'US', source_bank_code: '021000021', account_number: '1234567890', account_holder: 'John Doe'}).then(console.log);
```

### PHP

```php
<?php
function chargeB2BCOLLECT($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'Multi-currency',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'B2B_COLLECT',
            'email' => $email, 'reference' => 'b2b_collect_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/b2b_collect',
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

def charge_b2b_collect(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'Multi-currency',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'B2B_COLLECT',
        'email': email, 'reference': f'b2b_collect_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/b2b_collect',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Country | Test Account | Result |
|---------|-------------|--------|
| US | `1234567890` | Succeeds |
| GH | `1234567890123` | Succeeds |
| KE | `1234567890` | Succeeds |
| NG | `0123456789` | Succeeds |
| `*` | `0000000001` | Insufficient funds |

Use `sk_test_` prefix API key for sandbox mode. Sandbox transactions settle instantly.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_CORRIDOR` | Country/currency combination not supported |
| `INVALID_ACCOUNT` | Account details invalid |
| `BANK_NOT_SUPPORTED` | Bank code not in network |
| `COLLECTION_FAILED` | Collection rejected by bank |
| `COMPLIANCE_HOLD` | Transaction held for compliance review |

---

© 2026 SentinelGate — Whyte AG Group
