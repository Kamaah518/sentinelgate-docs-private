# China UnionPay (CUP)

China's largest card network with over 9 billion cards issued. Supports credit, debit, and prepaid cards for both domestic and cross-border transactions.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `CUP` |
| Currency | CNY, USD |
| Settlement | 1-3 business days |
| Min Amount | ¥0.01 / $0.01 |
| Max Amount | ¥500,000 / $50,000 per txn |
| Availability | 24/7 |

## How It Works

```
Customer → Selects UnionPay → Redirected to Secure Form → Enters Card Details → SMS OTP → Payment Confirmed → Callback
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
    "currency": "CNY, USD",
    "merchant_id": "your-merchant-id",
    "rail": "CUP",
    "email": "customer@example.com",
    "reference": "cup_ref_001",
    "callback_url": "https://yoursite.com/webhooks/cup",
    "metadata": {
      "card_type": "DEBIT",
      "customer_name": "Wei Zhang"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_cup_001",
  "status": "PENDING",
  "next_action": {
    "type": "REDIRECT_URL",
    "redirect_url": "https://checkout.sentinelgate.biz/cup/pay?session=abc123"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeCUP(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100),
    currency: 'CNY, USD',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'CUP',
    email,
    reference: `cup_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/cup',
    metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeCUP(50.00, 'buyer@example.com', {card_type: 'DEBIT', customer_name: 'Wei Zhang'}).then(console.log);
```

### PHP

```php
<?php
function chargeCUP($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'CNY, USD',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'CUP',
            'email' => $email, 'reference' => 'cup_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/cup',
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

def charge_cup(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'CNY, USD',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'CUP',
        'email': email, 'reference': f'cup_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/cup',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Test Card | Result |
|----------|--------|
| `6250 9470 0000 0014` | Succeeds |
| `6250 9470 0000 0022` | Declined |
| `6250 9470 0000 0030` | 3DS required |

Use `sk_test_` prefix API key for sandbox mode. Sandbox transactions settle instantly.

---

## Error Codes

| Code | Description |
|------|-------------|
| `CARD_DECLINED` | Card was declined |
| `INVALID_CARD` | Card number invalid |
| `OTP_FAILED` | SMS verification failed |
| `CROSS_BORDER_BLOCKED` | Cross-border not enabled on card |
| `DAILY_LIMIT` | Daily transaction limit exceeded |

---

© 2026 SentinelGate — Whyte AG Group
