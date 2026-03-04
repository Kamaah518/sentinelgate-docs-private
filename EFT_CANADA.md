# Crypto Transfer — Merchant to Anyone

Send cryptocurrency from your SentinelGate wallet to any external wallet address. Use for payouts, refunds, or vendor payments.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `CRYPTO_SEND` |
| Supported | BTC, ETH, USDT (ERC-20/TRC-20), USDC |
| Settlement | Blockchain speed (minutes to hours) |
| Min Amount | $1.00 equivalent |
| Max Amount | Limited by wallet balance |
| Availability | 24/7 |

## How It Works

```
Merchant → Submits Transfer Request → SentinelGate Signs Transaction
→ Broadcasts to Blockchain → Confirmations → Transfer Complete → Callback
```

---

## Integration

### Send Crypto

```bash
curl -X POST https://sentinelgate.biz/v1/crypto/send \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "coin": "USDT",
    "network": "TRC20",
    "to_address": "TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq",
    "amount": "250.00",
    "reference": "payout_001",
    "callback_url": "https://yoursite.com/webhooks/crypto-send"
  }'
```

### Response

```json
{
  "ok": true,
  "transfer_id": "sg_xfer_001",
  "status": "BROADCASTING",
  "coin": "USDT",
  "network": "TRC20",
  "amount": "250.00",
  "to_address": "TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq",
  "tx_hash": "pending",
  "fee_estimate": "1.00"
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function sendCrypto(coin, network, toAddress, amount, reference) {
  const response = await axios.post('https://sentinelgate.biz/v1/crypto/send', {
    merchant_id: process.env.MERCHANT_ID,
    coin, network, to_address: toAddress,
    amount: String(amount), reference,
    callback_url: 'https://yoursite.com/webhooks/crypto-send'
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });
  console.log('Transfer ID:', response.data.transfer_id);
  console.log('TX Hash:', response.data.tx_hash);
  return response.data;
}

// Test: Send 250 USDT via TRC-20
sendCrypto('USDT', 'TRC20', 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq', 250.00, 'payout_001').then(console.log);
```

### PHP

```php
<?php
function sendCrypto($coin, $network, $toAddress, $amount, $reference) {
    $ch = curl_init('https://sentinelgate.biz/v1/crypto/send');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'merchant_id' => getenv('MERCHANT_ID'),
            'coin' => $coin, 'network' => $network,
            'to_address' => $toAddress, 'amount' => (string)$amount,
            'reference' => $reference,
            'callback_url' => 'https://yoursite.com/webhooks/crypto-send'
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch); return $res;
}
print_r(sendCrypto('USDT', 'TRC20', 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq', 250.00, 'payout_001'));
```

### Python

```python
import requests, os

def send_crypto(coin, network, to_address, amount, reference):
    return requests.post('https://sentinelgate.biz/v1/crypto/send', json={
        'merchant_id': os.environ['MERCHANT_ID'],
        'coin': coin, 'network': network,
        'to_address': to_address, 'amount': str(amount),
        'reference': reference,
        'callback_url': 'https://yoursite.com/webhooks/crypto-send'
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()

print(send_crypto('USDT', 'TRC20', 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq', 250.00, 'payout_001'))
```

---

## Testing

| Coin | Test Address | Result |
|------|-------------|--------|
| BTC | `tb1qw508d6qejxtdg4y5r3zarvary0c5xw7kxpjzsx` | Succeeds (testnet) |
| ETH | `0x0000000000000000000000000000000000000001` | Succeeds (testnet) |
| USDT | `TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq` | Succeeds (testnet) |
| Any | `INVALID_ADDRESS` | Address validation error |

Sandbox simulates blockchain confirmation. Use `sk_test_` prefix.

---

## Error Codes

| Code | Description |
|------|-------------|
| `INSUFFICIENT_BALANCE` | Wallet balance too low |
| `INVALID_ADDRESS` | Destination address format invalid |
| `UNSUPPORTED_NETWORK` | Network not supported for this coin |
| `TRANSFER_FAILED` | Blockchain transaction failed |
| `DAILY_LIMIT_EXCEEDED` | Daily outbound transfer limit exceeded |

---

© 2026 SentinelGate — Whyte AG Group
