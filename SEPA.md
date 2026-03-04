# Buy Crypto — For Merchants

Purchase cryptocurrency using your fiat balance. Convert USD, EUR, or other fiat into BTC, ETH, USDT, or USDC directly from your SentinelGate account.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `CRYPTO_BUY` |
| Buy With | USD, EUR, GBP, KES, GHS |
| Receive | BTC, ETH, USDT, USDC |
| Settlement | Instant (after purchase confirmation) |
| Min Amount | $10.00 equivalent |
| Max Amount | $100,000/day |
| Availability | 24/7 |

## How It Works

```
Merchant → Requests Quote → Reviews Rate → Confirms Purchase
→ Fiat Debited → Crypto Credited to Wallet → Confirmation
```

---

## Integration

### Get Quote

```bash
curl -X POST https://sentinelgate.biz/v1/crypto/quote \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "action": "BUY",
    "fiat_currency": "USD",
    "fiat_amount": 1000.00,
    "crypto_coin": "BTC"
  }'
```

### Quote Response

```json
{
  "ok": true,
  "quote_id": "qt_abc123",
  "action": "BUY",
  "fiat_amount": 1000.00,
  "fiat_currency": "USD",
  "crypto_amount": "0.01482000",
  "crypto_coin": "BTC",
  "rate": "67476.34",
  "fee": "10.00",
  "net_crypto": "0.01467180",
  "valid_until": "2026-03-04T01:25:00Z"
}
```

### Confirm Purchase

```bash
curl -X POST https://sentinelgate.biz/v1/crypto/buy \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "quote_id": "qt_abc123",
    "reference": "buy_btc_001"
  }'
```

### Purchase Response

```json
{
  "ok": true,
  "purchase_id": "sg_buy_001",
  "status": "COMPLETED",
  "fiat_debited": 1000.00,
  "crypto_credited": "0.01467180",
  "coin": "BTC",
  "rate": "67476.34",
  "wallet_balance": "0.05234560"
}
```

---

## Sample Code

### Node.js

```javascript
const axios = require('axios');
const api = axios.create({
  baseURL: 'https://sentinelgate.biz',
  headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
});

async function buyCrypto(fiatAmount, fiatCurrency, coin) {
  // Step 1: Get quote
  const quote = await api.post('/v1/crypto/quote', {
    merchant_id: process.env.MERCHANT_ID,
    action: 'BUY', fiat_currency: fiatCurrency,
    fiat_amount: fiatAmount, crypto_coin: coin
  });

  console.log(`Quote: ${quote.data.fiat_amount} ${fiatCurrency} = ${quote.data.crypto_amount} ${coin}`);
  console.log(`Rate: ${quote.data.rate}, Fee: ${quote.data.fee}`);

  // Step 2: Confirm purchase
  const purchase = await api.post('/v1/crypto/buy', {
    merchant_id: process.env.MERCHANT_ID,
    quote_id: quote.data.quote_id,
    reference: `buy_${coin.toLowerCase()}_${Date.now()}`
  });

  console.log('Purchase complete:', purchase.data);
  return purchase.data;
}

// Test: Buy $1000 worth of BTC
buyCrypto(1000.00, 'USD', 'BTC').then(console.log);
```

### PHP

```php
<?php
function buyCrypto($fiatAmount, $fiatCurrency, $coin) {
    $headers = ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')];

    // Get quote
    $ch = curl_init('https://sentinelgate.biz/v1/crypto/quote');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode([
            'merchant_id' => getenv('MERCHANT_ID'), 'action' => 'BUY',
            'fiat_currency' => $fiatCurrency, 'fiat_amount' => $fiatAmount, 'crypto_coin' => $coin
        ])
    ]);
    $quote = json_decode(curl_exec($ch), true); curl_close($ch);

    // Confirm purchase
    $ch = curl_init('https://sentinelgate.biz/v1/crypto/buy');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode([
            'merchant_id' => getenv('MERCHANT_ID'),
            'quote_id' => $quote['quote_id'],
            'reference' => 'buy_' . strtolower($coin) . '_' . time()
        ])
    ]);
    $result = json_decode(curl_exec($ch), true); curl_close($ch);
    return $result;
}
print_r(buyCrypto(1000.00, 'USD', 'BTC'));
```

### Python

```python
import requests, os, time

API = 'https://sentinelgate.biz'
HEADERS = {'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}

def buy_crypto(fiat_amount, fiat_currency, coin):
    # Get quote
    quote = requests.post(f'{API}/v1/crypto/quote', json={
        'merchant_id': os.environ['MERCHANT_ID'], 'action': 'BUY',
        'fiat_currency': fiat_currency, 'fiat_amount': fiat_amount, 'crypto_coin': coin
    }, headers=HEADERS).json()

    print(f"Quote: {fiat_amount} {fiat_currency} = {quote['crypto_amount']} {coin}")

    # Confirm purchase
    result = requests.post(f'{API}/v1/crypto/buy', json={
        'merchant_id': os.environ['MERCHANT_ID'],
        'quote_id': quote['quote_id'],
        'reference': f'buy_{coin.lower()}_{int(time.time())}'
    }, headers=HEADERS).json()

    return result

print(buy_crypto(1000.00, 'USD', 'BTC'))
```

---

## Testing

| Fiat | Coin | Result |
|------|------|--------|
| $100 USD | BTC | Succeeds with test rate |
| $50 USD | ETH | Succeeds |
| $10 USD | USDT | Succeeds (1:1 rate) |
| $5 USD | BTC | Below minimum |

Sandbox uses fixed test rates. No real crypto is purchased. Use `sk_test_` prefix.

---

## Error Codes

| Code | Description |
|------|-------------|
| `QUOTE_EXPIRED` | Quote has expired — request a new one |
| `INSUFFICIENT_FIAT` | Fiat balance too low for purchase |
| `BELOW_MINIMUM` | Amount below minimum purchase |
| `DAILY_LIMIT_EXCEEDED` | Daily buy limit reached |
| `COIN_UNAVAILABLE` | Requested coin temporarily unavailable |

---

© 2026 SentinelGate — Whyte AG Group
