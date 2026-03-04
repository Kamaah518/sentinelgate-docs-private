# UPI — Unified Payments Interface (India)

Real-time bank-to-bank transfers via mobile UPI apps. Managed by NPCI, available 24/7 with instant settlement.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `UPI` |
| Currency | INR |
| Settlement | Real-time |
| Min Amount | ₹1 |
| Max Amount | ₹1,00,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Selects UPI → Enters VPA → Approves on App → Bank Debits → Callback → Order Confirmed
```

1. Customer selects UPI at checkout
2. Enters VPA (e.g., `user@upi`)
3. Receives collect request on UPI app
4. Approves with UPI PIN
5. Instant confirmation

---

## Integration

### Create Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 5000,
    "currency": "INR",
    "merchant_id": "your-merchant-id",
    "rail": "UPI",
    "email": "customer@example.com",
    "reference": "upi_ref_001",
    "callback_url": "https://yoursite.com/webhooks/upi",
    "metadata": {
      "vpa": "customer@upi",
      "customer_name": "Raj Sharma"}
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_upi_001",
  "status": "PENDING",
  "next_action": {
    "type": "UPI_COLLECT",
    "vpa": "customer@upi",
    "expires_in": 300
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createUPIPayment(amount, email, metadata) {
  try {
    const response = await axios.post('https://sentinelgate.biz/v1/charge', {
      amount_cents: Math.round(amount * 100),
      currency: 'INR',
      merchant_id: process.env.MERCHANT_ID,
      rail: 'UPI',
      email,
      reference: `upi_${Date.now()}`,
      callback_url: 'https://yoursite.com/webhooks/upi',
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
createUPIPayment(1000.00, 'buyer@example.com', { vpa: 'testuser@upi' });
```

### PHP

```php
<?php
function createUPIPayment($amount, $email, $metadata) {
    $payload = [
        'amount_cents' => round($amount * 100),
        'currency' => 'INR',
        'merchant_id' => getenv('MERCHANT_ID'),
        'rail' => 'UPI',
        'email' => $email,
        'reference' => 'upi_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/upi',
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
$result = createUPIPayment(1000.00, 'buyer@example.com', ['vpa' => 'testuser@upi']);
print_r($result);
```

### Python

```python
import requests, os, time

def create_upi_payment(amount, email, metadata):
    response = requests.post(
        'https://sentinelgate.biz/v1/charge',
        json={
            'amount_cents': round(amount * 100),
            'currency': 'INR',
            'merchant_id': os.environ['MERCHANT_ID'],
            'rail': 'UPI',
            'email': email,
            'reference': f'upi_{int(time.time())}',
            'callback_url': 'https://yoursite.com/webhooks/upi',
            'metadata': metadata
        },
        headers={
            'x-api-key': os.environ['SG_API_KEY'],
            'Content-Type': 'application/json'
        }
    )
    return response.json()

# Test
result = create_upi_payment(1000.00, 'buyer@example.com', {'vpa': 'testuser@upi'})
print(result)
```

---

## Testing

| Test VPA | Result |
|----------|--------|
| `success@upi` | Payment succeeds |
| `failure@upi` | Payment declined |
| `timeout@upi` | Times out after 5 min |
| `pending@upi` | Stays pending 30s then succeeds |

Use `sk_test_` prefix API key for sandbox mode.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_upi_001",
  "status": "CAPTURED",
  "amount_cents": 5000,
  "currency": "INR",
  "rail": "UPI",
  "metadata": {
    "vpa": "customer@upi",
    "rrn": "412345678901"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_VPA` | UPI ID format is incorrect |
| `VPA_NOT_FOUND` | UPI ID does not exist |
| `COLLECT_DECLINED` | Customer declined the collect request |
| `COLLECT_EXPIRED` | Customer did not respond in time |
| `INSUFFICIENT_BALANCE` | Insufficient funds in bank account |

---

© 2026 SentinelGate — Whyte AG Group
