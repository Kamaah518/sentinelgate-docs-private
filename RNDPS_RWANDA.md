# eChip Exchange — Swap eChips for Crypto or Fiat

Exchange eChip tokens for cryptocurrency (BTC, ETH, USDT, USDC) or fiat currency (USD, EUR, KES, GHS). Enables merchants and users to liquidate or acquire eChips through a real-time exchange.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `ECHIP_SWAP` |
| Swap Pairs | eCHIP ↔ BTC, ETH, USDT, USDC, USD, EUR, KES, GHS |
| Settlement | Instant |
| Min Amount | 10 eCHIP / $1.00 equivalent |
| Max Amount | 10,000,000 eCHIP / $100,000 per day |
| Availability | 24/7 |
| Fee | 0.5% of swap value |

## How It Works

```
Merchant → Requests Quote (eCHIP → BTC or USD → eCHIP)
→ Reviews Rate and Fee → Confirms Swap → Instant Settlement → Balance Updated
```

**Two directions:**
- **Sell eChips:** Convert eCHIP → Crypto or Fiat
- **Buy eChips:** Convert Crypto or Fiat → eCHIP

---

## Integration

### Get Exchange Quote

```bash
curl -X POST https://sentinelgate.biz/v1/echip/quote \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "direction": "SELL",
    "echip_amount": 10000,
    "target_currency": "USDT"
  }'
```

### Quote Response

```json
{
  "ok": true,
  "quote_id": "eq_abc123",
  "direction": "SELL",
  "echip_amount": 10000,
  "target_currency": "USDT",
  "target_amount": "99.50",
  "rate": "0.01",
  "fee_echip": 50,
  "fee_pct": "0.5%",
  "valid_until": "2026-03-04T01:25:00Z"
}
```

### Confirm Swap

```bash
curl -X POST https://sentinelgate.biz/v1/echip/swap \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "quote_id": "eq_abc123",
    "reference": "swap_001"
  }'
```

### Swap Response

```json
{
  "ok": true,
  "swap_id": "sg_swap_001",
  "status": "COMPLETED",
  "direction": "SELL",
  "echip_debited": 10000,
  "target_credited": "99.50",
  "target_currency": "USDT",
  "rate": "0.01",
  "fee": 50,
  "echip_balance_after": 115000,
  "target_balance_after": "1,249.50"
}
```

### Buy eChips (Reverse Direction)

```bash
curl -X POST https://sentinelgate.biz/v1/echip/quote \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "direction": "BUY",
    "fiat_amount": 100.00,
    "source_currency": "USD"
  }'
```

### Buy Quote Response

```json
{
  "ok": true,
  "quote_id": "eq_def456",
  "direction": "BUY",
  "source_amount": 100.00,
  "source_currency": "USD",
  "echip_amount": 9950,
  "rate": "0.01",
  "fee_usd": "0.50",
  "valid_until": "2026-03-04T01:25:00Z"
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

// Sell eChips for Crypto or Fiat
async function sellEChips(echipAmount, targetCurrency) {
  // Step 1: Get quote
  const quote = await sg.post('/v1/echip/quote', {
    merchant_id: process.env.MERCHANT_ID,
    direction: 'SELL',
    echip_amount: echipAmount,
    target_currency: targetCurrency
  });

  console.log(`Quote: ${echipAmount} eCHIP = ${quote.data.target_amount} ${targetCurrency}`);
  console.log(`Fee: ${quote.data.fee_echip} eCHIP (${quote.data.fee_pct})`);

  // Step 2: Confirm swap
  const swap = await sg.post('/v1/echip/swap', {
    merchant_id: process.env.MERCHANT_ID,
    quote_id: quote.data.quote_id,
    reference: `swap_sell_${Date.now()}`
  });

  console.log('Swap complete:', swap.data);
  return swap.data;
}

// Buy eChips with Fiat or Crypto
async function buyEChips(fiatAmount, sourceCurrency) {
  const quote = await sg.post('/v1/echip/quote', {
    merchant_id: process.env.MERCHANT_ID,
    direction: 'BUY',
    fiat_amount: fiatAmount,
    source_currency: sourceCurrency
  });

  console.log(`Quote: ${fiatAmount} ${sourceCurrency} = ${quote.data.echip_amount} eCHIP`);

  const swap = await sg.post('/v1/echip/swap', {
    merchant_id: process.env.MERCHANT_ID,
    quote_id: quote.data.quote_id,
    reference: `swap_buy_${Date.now()}`
  });

  return swap.data;
}

// Test: Sell 10000 eChips for USDT
sellEChips(10000, 'USDT').then(console.log);

// Test: Buy eChips with $100 USD
buyEChips(100.00, 'USD').then(console.log);
```

### PHP

```php
<?php
function eChipQuote($direction, $params) {
    $payload = array_merge([
        'merchant_id' => getenv('MERCHANT_ID'),
        'direction' => $direction
    ], $params);

    $ch = curl_init('https://sentinelgate.biz/v1/echip/quote');
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch);
    return $res;
}

function eChipSwap($quoteId, $reference) {
    $ch = curl_init('https://sentinelgate.biz/v1/echip/swap');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'merchant_id' => getenv('MERCHANT_ID'),
            'quote_id' => $quoteId,
            'reference' => $reference
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . getenv('SG_API_KEY')],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch);
    return $res;
}

// Sell 10000 eChips for USDT
$quote = eChipQuote('SELL', ['echip_amount' => 10000, 'target_currency' => 'USDT']);
$swap = eChipSwap($quote['quote_id'], 'swap_sell_' . time());
print_r($swap);

// Buy eChips with $100 USD
$quote = eChipQuote('BUY', ['fiat_amount' => 100.00, 'source_currency' => 'USD']);
$swap = eChipSwap($quote['quote_id'], 'swap_buy_' . time());
print_r($swap);
```

### Python

```python
import requests, os, time

API = 'https://sentinelgate.biz'
HEADERS = {'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}

def echip_quote(direction, **kwargs):
    payload = {'merchant_id': os.environ['MERCHANT_ID'], 'direction': direction, **kwargs}
    return requests.post(f'{API}/v1/echip/quote', json=payload, headers=HEADERS).json()

def echip_swap(quote_id, reference):
    return requests.post(f'{API}/v1/echip/swap', json={
        'merchant_id': os.environ['MERCHANT_ID'],
        'quote_id': quote_id, 'reference': reference
    }, headers=HEADERS).json()

# Sell 10000 eChips for USDT
quote = echip_quote('SELL', echip_amount=10000, target_currency='USDT')
print(f"Quote: {quote['echip_amount']} eCHIP = {quote['target_amount']} USDT")
swap = echip_swap(quote['quote_id'], f'swap_sell_{int(time.time())}')
print('Swap result:', swap)

# Buy eChips with $100 USD
quote = echip_quote('BUY', fiat_amount=100.00, source_currency='USD')
print(f"Quote: $100 USD = {quote['echip_amount']} eCHIP")
swap = echip_swap(quote['quote_id'], f'swap_buy_{int(time.time())}')
print('Swap result:', swap)

# Sell eChips for BTC
quote = echip_quote('SELL', echip_amount=100000, target_currency='BTC')
print(f"Quote: 100000 eCHIP = {quote['target_amount']} BTC")
```

---

## Exchange Rates

eChip exchange rates are determined by:
- **Base rate:** 1 eCHIP = $0.01 USD (peg)
- **Crypto rates:** Market rate at time of quote (locked for 60 seconds)
- **Fee:** 0.5% deducted from the swap

| Pair | Example Rate |
|------|-------------|
| eCHIP → USD | 100 eCHIP = $1.00 |
| eCHIP → USDT | 100 eCHIP = 1.00 USDT |
| eCHIP → BTC | 100,000 eCHIP = ~0.0148 BTC |
| eCHIP → ETH | 100,000 eCHIP = ~0.294 ETH |
| USD → eCHIP | $1.00 = 100 eCHIP |
| BTC → eCHIP | 0.01 BTC = ~67,476 eCHIP |

---

## Testing

| Direction | Test Input | Result |
|-----------|-----------|--------|
| SELL | 10,000 eCHIP → USDT | 99.50 USDT (after fee) |
| SELL | 10,000 eCHIP → USD | $99.50 (after fee) |
| SELL | 100,000 eCHIP → BTC | ~0.01475 BTC |
| BUY | $100 USD → eCHIP | 9,950 eCHIP (after fee) |
| BUY | 1 USDT → eCHIP | 99.5 eCHIP |
| SELL | 5 eCHIP → USD | Below minimum error |

Sandbox uses fixed rates. Use `sk_test_` prefix API key.

---

## Error Codes

| Code | Description |
|------|-------------|
| `QUOTE_EXPIRED` | Quote has expired — request new one |
| `INSUFFICIENT_ECHIPS` | Not enough eChips to sell |
| `INSUFFICIENT_FIAT` | Not enough fiat/crypto to buy eChips |
| `BELOW_MINIMUM` | Swap amount below minimum threshold |
| `DAILY_LIMIT_EXCEEDED` | Daily swap limit reached |
| `PAIR_UNAVAILABLE` | Requested currency pair temporarily unavailable |
| `RATE_CHANGED` | Market rate moved beyond tolerance — re-quote |

---

© 2026 SentinelGate — Whyte AG Group
