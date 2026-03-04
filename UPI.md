# SentinelGate — Sample Code

Ready-to-run examples for all 25 payment methods in Node.js, PHP, and Python.

---

## Quick Start

### 1. Set Up Credentials

```bash
cp .env.example .env
# Edit .env with your SentinelGate API key and merchant ID
```

### 2. Run Tests

**Node.js:**
```bash
cd nodejs
npm install
node test-all-methods.js           # All 25 methods
node test-all-methods.js UPI       # Single method
node test-all-methods.js CRYPTO    # All crypto methods
npm run webhook                    # Start webhook server
```

**PHP:**
```bash
cd php
export SG_API_KEY=sk_test_your_key MERCHANT_ID=your-id
php test-all-methods.php           # All 25 methods
php test-all-methods.php UPI       # Single method
```

**Python:**
```bash
cd python
pip install -r requirements.txt
python test_all_methods.py          # All 25 methods
python test_all_methods.py UPI      # Single method
python test_all_methods.py --server # Webhook server
```

---

## Supported Methods

| # | Method | Rail Code | Currency |
|---|--------|-----------|----------|
| 1 | UPI | `UPI` | INR |
| 2 | ACH (USA) | `ACH_US` | USD |
| 3 | ACH (Ghana) | `ACH_GH` | GHS |
| 4 | eCheck (USA) | `ECHECK_US` | USD |
| 5 | EFT (Canada) | `EFT_CA` | CAD |
| 6 | Interac (Canada) | `INTERAC` | CAD |
| 7 | SEPA (Europe) | `SEPA` | EUR |
| 8 | PIX (Brazil) | `PIX` | BRL |
| 9 | China UnionPay | `CUP` | CNY |
| 10 | Snipe Móvil | `SNIPE` | MXN |
| 11 | Bank-to-Bank Collections | `B2B_COLLECT` | Multi |
| 12 | NIP (Nigeria) | `NIP_NG` | NGN |
| 13 | GIP (Ghana) | `GIP_GH` | GHS |
| 14 | MMI (Ghana) | `MMI_GH` | GHS |
| 15 | PesaLink (Kenya) | `PESALINK` | KES |
| 16 | RNDPS (Rwanda) | `RNDPS_RW` | RWF |
| 17 | TISS (Tanzania) | `TISS_TZ` | TZS |
| 18 | SINPE Móvil (Costa Rica) | `SINPE_CR` | CRC |
| 19 | SIPARD (Dominican Republic) | `SIPARD_DO` | DOP |
| 20 | ACH (Dominican Republic) | `ACH_DO` | DOP |
| 21 | Crypto Processing | `CRYPTO_PAY` | BTC/ETH/USDT/USDC |
| 22 | Crypto Transfer | `CRYPTO_SEND` | BTC/ETH/USDT/USDC |
| 23 | Buy Crypto | `CRYPTO_BUY` | BTC/ETH/USDT/USDC |
| 24 | eChip Token Processing | `ECHIP_PAY` | eCHIP |
| 25 | eChip Exchange | `ECHIP_SWAP` | eCHIP ↔ Crypto/Fiat |
| 26 | Apple Pay | `APPLE_PAY` | USD/EUR/GBP + 60 more |
| 27 | Google Pay | `GOOGLE_PAY` | USD/EUR/GBP + 80 more |

---

## File Structure

```
examples/
├── .env.example                    ← Copy to .env
├── README.md                       ← This file
├── nodejs/
│   ├── package.json
│   ├── test-all-methods.js         ← All 25 methods + test runner
│   └── webhook-server.js           ← Express webhook server
├── php/
│   ├── test-all-methods.php        ← All 25 methods + test runner
│   └── webhook-handler.php         ← Webhook endpoint
└── python/
    ├── requirements.txt
    └── test_all_methods.py         ← All 25 methods + Flask webhook server
```

---

## Sandbox Testing

All examples use sandbox/test mode by default:
- API keys with `sk_test_` prefix hit the sandbox
- No real money is moved
- Transactions auto-settle instantly
- Test accounts/phones/cards are documented per method

---

© 2026 SentinelGate — Whyte AG Group
