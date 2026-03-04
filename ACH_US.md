# eChip Token Processing — Collect eChips

Accept eChip tokens as payment for gaming, digital goods, and services. eChips are SentinelGate's native digital token for gaming ecosystems and micro-transactions.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ECHIP_PAY` |
| Token | eCHIP |
| Settlement | Instant |
| Min Amount | 1 eCHIP |
| Max Amount | 10,000,000 eCHIP/txn |
| Availability | 24/7 |

## How It Works

```
Gamer/Buyer → Selects "Pay with eChips" → Enters Gamer Tag or Wallet ID
→ Confirms Amount → eChips Debited → Merchant Credited → Callback
```

1. Customer selects eChip payment at checkout
2. Enters their SentinelGate Gamer Tag or eChip Wallet ID
3. Confirms the eChip amount on screen or via push notification
4. eChips are instantly debited from the customer's wallet
5. Merchant's eChip balance is credited immediately
6. Webhook fires with confirmation

---

## Integration

### Create eChip Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 50000,
    "currency": "ECHIP",
    "merchant_id": "your-merchant-id",
    "rail": "ECHIP_PAY",
    "email": "gamer@example.com",
    "reference": "echip_pay_ref_001",
    "callback_url": "https://yoursite.com/webhooks/echip",
    "metadata": {
      "gamer_tag": "ProGamer2026",
      "wallet_id": "ew_abc123def456",
      "item_type": "GAME_CREDIT",
      "item_name": "500 Gold Coins",
      "game_id": "clash-kingdoms"
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_echip_001",
  "status": "PENDING",
  "next_action": {
    "type": "ECHIP_CONFIRM",
    "gamer_tag": "ProGamer2026",
    "amount_echip": 500,
    "merchant_name": "Clash Kingdoms Store",
    "expires_in": 120
  }
}
```

### After Confirmation

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_echip_001",
  "status": "CAPTURED",
  "amount_echip": 500,
  "gamer_tag": "ProGamer2026",
  "merchant_balance_after": 125000
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

const sg = axios.create({
  baseURL: 'https://sentinelgate.biz',
  headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
});

async function collectEChips(amount, gamerTag, itemName, gameId) {
  const response = await sg.post('/v1/charge', {
    amount_cents: amount * 100,
    currency: 'ECHIP',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'ECHIP_PAY',
    email: 'gamer@example.com',
    reference: `echip_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/echip',
    metadata: {
      gamer_tag: gamerTag,
      item_type: 'GAME_CREDIT',
      item_name: itemName,
      game_id: gameId
    }
  });
  console.log('eChip charge status:', response.data.status);
  return response.data;
}

// Collect 500 eChips from ProGamer2026 for 500 Gold Coins
collectEChips(500, 'ProGamer2026', '500 Gold Coins', 'clash-kingdoms').then(console.log);

// Webhook handler for eChip payments
const express = require('express');
const app = express();
app.use(express.json());

app.post('/webhooks/echip', (req, res) => {
  const { event, transaction_id, status, metadata } = req.body;

  if (event === 'payment.captured') {
    console.log(`eChip payment ${transaction_id} captured!`);
    console.log(`Gamer: ${metadata.gamer_tag}, Item: ${metadata.item_name}`);
    // Grant the item to the gamer in your game
    // grantItem(metadata.gamer_tag, metadata.item_name, metadata.game_id);
  }

  res.status(200).json({ received: true });
});
```

### PHP

```php
<?php
function collectEChips($amount, $gamerTag, $itemName, $gameId) {
    $payload = [
        'amount_cents' => $amount * 100,
        'currency' => 'ECHIP',
        'merchant_id' => getenv('MERCHANT_ID'),
        'rail' => 'ECHIP_PAY',
        'email' => 'gamer@example.com',
        'reference' => 'echip_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/echip',
        'metadata' => [
            'gamer_tag' => $gamerTag,
            'item_type' => 'GAME_CREDIT',
            'item_name' => $itemName,
            'game_id' => $gameId
        ]
    ];

    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . getenv('SG_API_KEY')
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response;
}

// Collect 500 eChips
$result = collectEChips(500, 'ProGamer2026', '500 Gold Coins', 'clash-kingdoms');
print_r($result);

// Webhook handler
$body = json_decode(file_get_contents('php://input'), true);
if ($body['event'] === 'payment.captured') {
    $gamerTag = $body['metadata']['gamer_tag'];
    $itemName = $body['metadata']['item_name'];
    // grantItem($gamerTag, $itemName);
}
http_response_code(200);
echo json_encode(['received' => true]);
```

### Python

```python
import requests, os, time
from flask import Flask, request, jsonify

API = 'https://sentinelgate.biz'
HEADERS = {'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}

def collect_echips(amount, gamer_tag, item_name, game_id):
    return requests.post(f'{API}/v1/charge', json={
        'amount_cents': amount * 100,
        'currency': 'ECHIP',
        'merchant_id': os.environ['MERCHANT_ID'],
        'rail': 'ECHIP_PAY',
        'email': 'gamer@example.com',
        'reference': f'echip_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/echip',
        'metadata': {
            'gamer_tag': gamer_tag,
            'item_type': 'GAME_CREDIT',
            'item_name': item_name,
            'game_id': game_id
        }
    }, headers=HEADERS).json()

# Collect 500 eChips
result = collect_echips(500, 'ProGamer2026', '500 Gold Coins', 'clash-kingdoms')
print(result)

# Webhook handler
app = Flask(__name__)

@app.route('/webhooks/echip', methods=['POST'])
def echip_webhook():
    data = request.json
    if data['event'] == 'payment.captured':
        gamer = data['metadata']['gamer_tag']
        item = data['metadata']['item_name']
        print(f'eChip payment captured: {gamer} bought {item}')
        # grant_item(gamer, item)
    return jsonify(received=True), 200
```

---

## Testing

| Gamer Tag | Result |
|----------|--------|
| `ProGamer2026` | Instant success |
| `TestGamer_fail` | Insufficient eChip balance |
| `TestGamer_timeout` | Confirmation timeout |
| `UnknownGamer999` | Gamer tag not found |

**Test eChip amounts:** Any amount from 1 to 10,000,000. Sandbox wallets start with 1,000,000 eChips.

Use `sk_test_` prefix API key for sandbox mode.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_echip_001",
  "status": "CAPTURED",
  "amount_cents": 50000,
  "currency": "ECHIP",
  "rail": "ECHIP_PAY",
  "metadata": {
    "gamer_tag": "ProGamer2026",
    "wallet_id": "ew_abc123def456",
    "item_type": "GAME_CREDIT",
    "item_name": "500 Gold Coins",
    "game_id": "clash-kingdoms",
    "balance_after": 49500
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `GAMER_TAG_NOT_FOUND` | Gamer tag does not exist in the system |
| `WALLET_NOT_FOUND` | eChip wallet ID is invalid |
| `INSUFFICIENT_ECHIPS` | Customer does not have enough eChips |
| `CONFIRM_EXPIRED` | Customer did not confirm within time limit |
| `CONFIRM_DECLINED` | Customer declined the eChip charge |
| `WALLET_FROZEN` | Customer wallet is frozen or suspended |
| `AMOUNT_TOO_SMALL` | Amount below minimum (1 eCHIP) |

---

© 2026 SentinelGate — Whyte AG Group
