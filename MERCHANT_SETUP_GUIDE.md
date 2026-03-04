# EFT — Electronic Funds Transfer (Canada)

Canadian bank-to-bank transfers via Payments Canada's EFT system. Used for direct debits and credits between Canadian bank accounts.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `EFT_CA` |
| Currency | CAD |
| Settlement | 1-2 business days |
| Min Amount | $0.01 CAD |
| Max Amount | $25,000,000/txn |
| Availability | Business days |

## How It Works

```
Customer → Provides Transit/Institution/Account → EFT Debit Submitted → Payments Canada → Bank Debits → Settlement → Callback
```

1. Customer provides transit number, institution number, and account number
2. SentinelGate submits EFT debit through Payments Canada
3. Clearing happens in the next batch cycle
4. Customer's bank debits the account
5. Funds settle in 1-2 business days

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 15000,
    "currency": "CAD",
    "merchant_id": "your-merchant-id",
    "rail": "EFT_CA",
    "email": "customer@example.com",
    "reference": "eft_ca_ref_001",
    "callback_url": "https://yoursite.com/webhooks/eft",
    "metadata": {
      "transit_number": "12345",
      "institution_number": "001",
      "account_number": "1234567",
      "account_holder": "Sarah Chen"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_eft_ca_001",
  "status": "PENDING",
  "next_action": {
    "type": "EFT_INITIATED",
    "estimated_settlement": "2026-03-06T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createEFTPayment(amount, email, bankDetails) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'CAD',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'EFT_CA',
    email,
    reference: `eft_ca_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/eft',
    metadata: bankDetails
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  return response.data;
}

// Test
createEFTPayment(150.00, 'buyer@example.com', {
  transit_number: '12345', institution_number: '001',
  account_number: '1234567', account_holder: 'Sarah Chen'
}).then(console.log);
```

### PHP

```php
<?php
function createEFTPayment($amount, $email, $bankDetails) {
    $payload = [
        'amount_cents' => round($amount * 100), 'currency' => 'CAD',
        'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'EFT_CA',
        'email' => $email, 'reference' => 'eft_ca_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/eft',
        'metadata' => $bankDetails
    ];
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch); return $res;
}
$result = createEFTPayment(150.00, 'buyer@example.com', [
    'transit_number' => '12345', 'institution_number' => '001',
    'account_number' => '1234567', 'account_holder' => 'Sarah Chen'
]);
print_r($result);
```

### Python

```python
import requests, os, time

def create_eft_ca_payment(amount, email, bank_details):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'CAD',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'EFT_CA',
        'email': email, 'reference': f'eft_ca_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/eft',
        'metadata': bank_details
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()

result = create_eft_ca_payment(150.00, 'buyer@example.com', {
    'transit_number': '12345', 'institution_number': '001',
    'account_number': '1234567', 'account_holder': 'Sarah Chen'
})
print(result)
```

---

## Testing

| Transit | Institution | Account | Result |
|---------|------------|---------|--------|
| `12345` | `001` | `1234567` | Succeeds |
| `12345` | `001` | `0000001` | NSF |
| `12345` | `001` | `9999999` | Account not found |

Use `sk_test_` prefix API key for sandbox.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_TRANSIT` | Transit number is invalid |
| `INVALID_INSTITUTION` | Institution number not recognized |
| `ACCOUNT_NOT_FOUND` | Account does not exist |
| `NSF` | Non-sufficient funds |
| `EFT_RETURNED` | Payment returned by bank |

---

© 2026 SentinelGate — Whyte AG Group
