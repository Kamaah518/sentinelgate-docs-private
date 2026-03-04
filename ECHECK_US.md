# MMI — Mobile Money Interoperability (Ghana)

Ghana's cross-network mobile money interoperability system. Allows transfers between different mobile money networks and between bank accounts and mobile wallets.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `MMI_GH` |
| Currency | GHS |
| Settlement | Real-time |
| Min Amount | GHS 0.10 |
| Max Amount | GHS 10,000/txn |
| Availability | 24/7 |

## How It Works

```
Customer → Provides Mobile Wallet Number → MMI Routes Cross-Network → Customer Confirms on Phone → Instant Transfer → Callback
```


**Supported networks:** MTN, Vodafone (VOD), AirtelTigo (AIR). MMI enables seamless transfers between all networks without the customer needing to worry about which network the recipient is on.

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
    "rail": "MMI_GH",
    "email": "customer@example.com",
    "reference": "mmi_gh_ref_001",
    "callback_url": "https://yoursite.com/webhooks/mmi_gh",
    "metadata": {
      "wallet_number": "0241234567",
      "network": "MTN",
      "customer_name": "Kwesi Adu"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_mmi_gh_001",
  "status": "PENDING",
  "next_action": {
    "type": "MMI_REQUEST_SENT",
    "wallet": "0241234567",
    "network": "MTN",
    "expires_in": 120
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function chargeMMIGH(amount, email, metadata) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amount * 100), currency: 'GHS',
    merchant_id: process.env.MERCHANT_ID, rail: 'MMI_GH', email,
    reference: `mmi_gh_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/mmi_gh', metadata
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Status:', response.data.status);
  return response.data;
}

// Test
chargeMMIGH(50.00, 'buyer@example.com', {wallet_number: '0241234567', network: 'MTN', customer_name: 'Kwesi Adu'}).then(console.log);
```

### PHP

```php
<?php
function chargeMMIGH($amount, $email, $metadata) {
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'amount_cents' => round($amount * 100), 'currency' => 'GHS',
            'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'MMI_GH',
            'email' => $email, 'reference' => 'mmi_gh_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/mmi_gh',
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

def charge_mmi_gh(amount, email, metadata):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount * 100), 'currency': 'GHS',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'MMI_GH',
        'email': email, 'reference': f'mmi_gh_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/mmi_gh',
        'metadata': metadata
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()
```

---

## Testing

| Wallet | Network | Result |
|--------|---------|--------|
| `0241234567` | `MTN` | Succeeds |
| `0201234567` | `VOD` | Succeeds |
| `0261234567` | `AIR` | Succeeds |
| `0240000001` | `MTN` | Insufficient funds |

Use `sk_test_` prefix API key for sandbox mode.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INVALID_WALLET` | Wallet number format invalid |
| `WALLET_NOT_FOUND` | Wallet not registered on network |
| `NETWORK_UNAVAILABLE` | Mobile network temporarily down |
| `CUSTOMER_DECLINED` | Customer declined the prompt |
| `INSUFFICIENT_BALANCE` | Insufficient wallet balance |

---

© 2026 SentinelGate — Whyte AG Group
