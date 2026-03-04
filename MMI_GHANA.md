# ACH — Automated Clearing House (USA)

US bank-to-bank transfers via the ACH network. Supports both one-time and recurring debits. Processed in batches by the Federal Reserve.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ACH_US` |
| Currency | USD |
| Settlement | 1-3 business days |
| Min Amount | $0.01 |
| Max Amount | $1,000,000/txn |
| Availability | Business days |

## How It Works

```
Customer → Provides Bank Details → ACH Debit Submitted → Bank Verifies → Funds Settle → Callback
```

1. Customer provides bank account number and routing number
2. SentinelGate submits ACH debit to the network
3. Originating bank sends request to customer's bank
4. Customer's bank verifies and debits the account
5. Funds settle in 1-3 business days

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 5000,
    "currency": "USD",
    "merchant_id": "your-merchant-id",
    "rail": "ACH_US",
    "email": "customer@example.com",
    "reference": "ach_us_ref_001",
    "callback_url": "https://yoursite.com/webhooks/ach_us",
    "metadata": {
      "account_number": "1234567890",
      "routing_number": "021000021",
      "account_type": "checking",
      "account_holder": "John Doe"}
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_ach_us_001",
  "status": "PENDING",
  "next_action": {
    "type": "ACH_INITIATED",
    "estimated_settlement": "2026-03-07T00:00:00Z"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createACHUSPayment(amount, email, metadata) {
  try {
    const response = await axios.post('https://sentinelgate.biz/v1/charge', {
      amount_cents: Math.round(amount * 100),
      currency: 'USD',
      merchant_id: process.env.MERCHANT_ID,
      rail: 'ACH_US',
      email,
      reference: `ach_us_${Date.now()}`,
      callback_url: 'https://yoursite.com/webhooks/ach_us',
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
createACHUSPayment(250.00, 'buyer@example.com', {
  account_number: '1234567890',
  routing_number: '021000021',
  account_type: 'checking',
  account_holder: 'John Doe'
});
```

### PHP

```php
<?php
function createACHUSPayment($amount, $email, $metadata) {
    $payload = [
        'amount_cents' => round($amount * 100),
        'currency' => 'USD',
        'merchant_id' => getenv('MERCHANT_ID'),
        'rail' => 'ACH_US',
        'email' => $email,
        'reference' => 'ach_us_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/ach_us',
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
$result = createACHUSPayment(250.00, 'buyer@example.com', [
    'account_number' => '1234567890',
    'routing_number' => '021000021',
    'account_type' => 'checking',
    'account_holder' => 'John Doe'
]);
print_r($result);
```

### Python

```python
import requests, os, time

def create_ach_us_payment(amount, email, metadata):
    response = requests.post(
        'https://sentinelgate.biz/v1/charge',
        json={
            'amount_cents': round(amount * 100),
            'currency': 'USD',
            'merchant_id': os.environ['MERCHANT_ID'],
            'rail': 'ACH_US',
            'email': email,
            'reference': f'ach_us_{int(time.time())}',
            'callback_url': 'https://yoursite.com/webhooks/ach_us',
            'metadata': metadata
        },
        headers={
            'x-api-key': os.environ['SG_API_KEY'],
            'Content-Type': 'application/json'
        }
    )
    return response.json()

# Test
result = create_ach_us_payment(250.00, 'buyer@example.com', {
    'account_number': '1234567890',
    'routing_number': '021000021',
    'account_type': 'checking',
    'account_holder': 'John Doe'
})
print(result)
```

---

## Testing

| Test Account | Routing | Result |
|-------------|---------|--------|
| `1234567890` | `021000021` | Succeeds in 1 day |
| `0000000001` | `021000021` | Insufficient funds |
| `9999999999` | `021000021` | Account not found |
| `5555555555` | `021000021` | Returns after settlement |

ACH sandbox settles instantly. Use `sk_test_` prefix API key.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_ach_us_001",
  "status": "CAPTURED",
  "amount_cents": 5000,
  "currency": "USD",
  "rail": "ACH_US",
  "metadata": {
    "trace_number": "021000021234567",
    "settlement_date": "2026-03-07"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_ROUTING` | Routing number is invalid |
| `ACCOUNT_NOT_FOUND` | Bank account does not exist |
| `INSUFFICIENT_FUNDS` | Account has insufficient funds |
| `ACH_RETURN` | Payment returned by receiving bank |
| `UNAUTHORIZED_DEBIT` | Account holder disputed the debit |

---

© 2026 SentinelGate — Whyte AG Group
