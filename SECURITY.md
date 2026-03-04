# Crypto Processing — Collect Crypto Payments

Accept cryptocurrency payments for goods and services. Customers pay in BTC, ETH, USDT, or USDC and you receive fiat settlement or hold crypto.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `CRYPTO_PAY` |
| Accepted | BTC, ETH, USDT (ERC-20/TRC-20), USDC |
| Settlement | Instant (crypto) / T+1 (fiat conversion) |
| Min Amount | $1.00 equivalent |
| Max Amount | No fixed limit |
| Availability | 24/7 |

## How It Works

```
Customer → Selects Crypto at Checkout → Receives Wallet Address + Amount
→ Sends Crypto from Wallet → Blockchain Confirmation → Callback → Order Confirmed
```

1. Merchant creates a charge with `rail: "CRYPTO_PAY"`
2. SentinelGate generates a unique deposit address and exact crypto amount
3. Customer sends crypto from their wallet
4. SentinelGate monitors the blockchain for confirmations
5. After required confirmations, payment is marked as captured
6. Merchant receives settlement in crypto or auto-converted fiat

---

## Integration

### Create Crypto Charge

```bash
curl -X POST https://sentinelgate.biz/v1/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 10000,
    "currency": "USD",
    "merchant_id": "your-merchant-id",
    "rail": "CRYPTO_PAY",
    "email": "customer@example.com",
    "reference": "crypto_pay_ref_001",
    "callback_url": "https://yoursite.com/webhooks/crypto",
    "metadata": {
      "accepted_coins": ["BTC", "ETH", "USDT", "USDC"],
      "settlement_currency": "USD",
      "expiry_minutes": 30
    }
  }'
```

### Response

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_crypto_001",
  "status": "AWAITING_DEPOSIT",
  "next_action": {
    "type": "CRYPTO_DEPOSIT",
    "addresses": {
      "BTC": "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh",
      "ETH": "0x742d35Cc6634C0532925a3b844Bc9e7595f2bD18",
      "USDT_ERC20": "0x742d35Cc6634C0532925a3b844Bc9e7595f2bD18",
      "USDT_TRC20": "TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq",
      "USDC": "0x742d35Cc6634C0532925a3b844Bc9e7595f2bD18"
    },
    "amounts": {
      "BTC": "0.00148200",
      "ETH": "0.02941200",
      "USDT": "100.00",
      "USDC": "100.00"
    },
    "rate_locked_until": "2026-03-04T02:00:00Z",
    "qr_url": "https://sentinelgate.biz/crypto/qr/sg_txn_crypto_001"
  }
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');

async function createCryptoCharge(amountUSD, email, coins = ['BTC', 'ETH', 'USDT', 'USDC']) {
  const response = await axios.post('https://sentinelgate.biz/v1/charge', {
    amount_cents: Math.round(amountUSD * 100),
    currency: 'USD',
    merchant_id: process.env.MERCHANT_ID,
    rail: 'CRYPTO_PAY',
    email,
    reference: `crypto_${Date.now()}`,
    callback_url: 'https://yoursite.com/webhooks/crypto',
    metadata: {
      accepted_coins: coins,
      settlement_currency: 'USD',
      expiry_minutes: 30
    }
  }, {
    headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
  });

  const { addresses, amounts } = response.data.next_action;
  console.log('Deposit addresses:', addresses);
  console.log('Amounts:', amounts);
  return response.data;
}

// Test
createCryptoCharge(100.00, 'buyer@example.com').then(console.log);
```

### PHP

```php
<?php
function createCryptoCharge($amountUSD, $email, $coins = ['BTC','ETH','USDT','USDC']) {
    $payload = [
        'amount_cents' => round($amountUSD * 100), 'currency' => 'USD',
        'merchant_id' => getenv('MERCHANT_ID'), 'rail' => 'CRYPTO_PAY',
        'email' => $email, 'reference' => 'crypto_' . time(),
        'callback_url' => 'https://yoursite.com/webhooks/crypto',
        'metadata' => [
            'accepted_coins' => $coins,
            'settlement_currency' => 'USD',
            'expiry_minutes' => 30
        ]
    ];
    $ch = curl_init('https://sentinelgate.biz/v1/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch); return $res;
}
print_r(createCryptoCharge(100.00, 'buyer@example.com'));
```

### Python

```python
import requests, os, time

def create_crypto_charge(amount_usd, email, coins=['BTC','ETH','USDT','USDC']):
    return requests.post('https://sentinelgate.biz/v1/charge', json={
        'amount_cents': round(amount_usd * 100), 'currency': 'USD',
        'merchant_id': os.environ['MERCHANT_ID'], 'rail': 'CRYPTO_PAY',
        'email': email, 'reference': f'crypto_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/crypto',
        'metadata': {
            'accepted_coins': coins,
            'settlement_currency': 'USD',
            'expiry_minutes': 30
        }
    }, headers={'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}).json()

result = create_crypto_charge(100.00, 'buyer@example.com')
print(result)
```

---

## Testing

| Test Amount | Coin | Result |
|------------|------|--------|
| Any | `BTC` | Simulated 1-conf in 10s |
| Any | `ETH` | Simulated 12-conf in 5s |
| Any | `USDT` | Instant |
| $0.01 | Any | Minimum amount error |

Sandbox auto-confirms deposits after 10 seconds. Use `sk_test_` prefix.

---

## Webhook

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_crypto_001",
  "status": "CAPTURED",
  "amount_cents": 10000,
  "currency": "USD",
  "rail": "CRYPTO_PAY",
  "metadata": {
    "coin": "BTC",
    "tx_hash": "a1b2c3d4e5f6...",
    "confirmations": 3,
    "amount_crypto": "0.00148200",
    "settlement_amount": 100.00,
    "settlement_currency": "USD"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `DEPOSIT_EXPIRED` | Customer did not send crypto in time |
| `UNDERPAYMENT` | Received less than the required amount |
| `OVERPAYMENT` | Received more than required (refund initiated) |
| `UNCONFIRMED` | Transaction broadcast but not yet confirmed |
| `UNSUPPORTED_COIN` | Requested coin is not supported |
| `RATE_EXPIRED` | Exchange rate lock has expired |

---

© 2026 SentinelGate — Whyte AG Group
