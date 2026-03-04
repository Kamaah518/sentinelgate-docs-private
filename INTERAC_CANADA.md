# ACH — Automated Clearing House (Ghana)

Ghana Interbank Settlement (GIS) system for batch bank-to-bank transfers. Operated by Ghana Interbank Payment and Settlement Systems (GhIPSS).

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ACH_GH` |
| Currency | GHS |
| Settlement | Same day / T+1 |
| Min Amount | GHS 1 |
| Max Amount | GHS 500,000/txn |
| Availability | Business days |

## How It Works

```
Customer → Provides Bank Details → ACH Debit via GhIPSS → Bank Verifies → Settlement → Callback
```

1. Customer provides Ghana bank account number and bank code
2. SentinelGate submits ACH debit through GhIPSS
3. Debit is processed in next clearing cycle
4. Customer's bank verifies and debits the account
5. Funds settle same day or T+1

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
    "rail": "ACH_GH",
    "email": "customer@example.com",
    "reference": "ach_gh_ref_001",
    "callback_url": "https://yoursite.com/webhooks/ach_gh",
    "metadata": {
      "account_number": "1234567890123",
      "bank_code": "GCB",
      "account_name": "Kwame Asante"}
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_ach_gh_001",
  "status": "PENDING",
  "next_action": {
    "type": "ACH_GH_INITIATED",
    "estimated_settlement": "2026-03-05T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createACHGHPayment(amount, email, metadata) {
  try {
    const response = await axios.post('https://sentinelgate.biz/v1/charge', {
      amount_cents: Math.round(amount * 100),
      currency: 'GHS',
      merchant_id: process.env.MERCHANT_ID,
      rail: 'ACH_GH',
      email,
      reference: `ach_gh_${Date.now()}`,
      callback_url: 'https://yoursite.com/webhooks/ach_gh',
      metadata
    }, {
      headers: {
        'x-api-key': process.env.SG_API_KEY,
        'Content-Type': 'application/json'
      }
    });

    console.log('Transaction ID:', response.data.payment_intent_id);
    console.log('Status:', response.data.status);
    return response.data;
  } catch (error) {
    console.error('Payment failed:', error.response?.data || error.message);
    throw error;
  }
}

// Test
createACHGHPayment(500.00, 'buyer@example.com', {
  account_number: '1234567890123',
  bank_code: 'GCB',
  account_name: 'Kwame Asante'
});
```

### PHP

```php
<?php
function createACHGHPayment($amount, $email, $metadata) {
    $payload = [
        'amount_cents' => round($amount * 100),
        'currency' => 'GHS',
        'merchant_id' => getenv('MERCHANT_ID'),
        'rail' => 'ACH_GH',
        'email' => $email,
        'reference' => 'ach_gh_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/ach_gh',
        'metadata' => $metadata
    ];

    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . getenv('SG_API_KEY')
        ],
        CURLOPT_RETURNTRANSFER => true,
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response;
}

// Test
$result = createACHGHPayment(500.00, 'buyer@example.com', [
    'account_number' => '1234567890123',
    'bank_code' => 'GCB',
    'account_name' => 'Kwame Asante'
]);
print_r($result);
```

### Python

```python
import requests, os, time

def create_ach_gh_payment(amount, email, metadata):
    response = requests.post(
        'https://sentinelgate.biz/v1/charge',
        json={
            'amount_cents': round(amount * 100),
            'currency': 'GHS',
            'merchant_id': os.environ['MERCHANT_ID'],
            'rail': 'ACH_GH',
            'email': email,
            'reference': f'ach_gh_{int(time.time())}',
            'callback_url': 'https://yoursite.com/webhooks/ach_gh',
            'metadata': metadata
        },
        headers={
            'x-api-key': os.environ['SG_API_KEY'],
            'Content-Type': 'application/json'
        }
    )
    return response.json()

# Test
result = create_ach_gh_payment(500.00, 'buyer@example.com', {
    'account_number': '1234567890123',
    'bank_code': 'GCB',
    'account_name': 'Kwame Asante'
})
print(result)
```

---

## Testing

| Test Account | Bank Code | Result |
|-------------|-----------|--------|
| `1234567890123` | `GCB` | Succeeds next cycle |
| `0000000000001` | `GCB` | Insufficient funds |
| `9999999999999` | `GCB` | Account not found |

Sandbox settles instantly. Use `sk_test_` prefix.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_ach_gh_001",
  "status": "CAPTURED",
  "amount_cents": 5000,
  "currency": "GHS",
  "rail": "ACH_GH",
  "metadata": {
    "ghipss_ref": "GIS20260304001234",
    "settlement_date": "2026-03-05"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_BANK_CODE` | Bank code not recognized |
| `ACCOUNT_NOT_FOUND` | Account does not exist |
| `INSUFFICIENT_FUNDS` | Insufficient funds |
| `ACH_RETURN` | Payment returned |

---

© 2026 SentinelGate — Whyte AG Group
