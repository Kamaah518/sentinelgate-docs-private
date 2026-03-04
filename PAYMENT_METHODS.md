# SentinelGate PSP

**Secure Payment Gateway Platform**

SentinelGate is a payment gateway that connects online stores to multiple payment providers through a single, unified API. It supports card payments, mobile money, and bank transfers across multiple currencies.

---

## Who Is This For?

| Audience | Start Here |
|----------|-----------|
| Merchants setting up payments | [Merchant Setup Guide](docs/MERCHANT_SETUP_GUIDE.md) |
| Developers integrating WooCommerce | [WooCommerce Integration](docs/WOOCOMMERCE_INTEGRATION.md) |
| Developers integrating Shopify | [Shopify Integration](docs/SHOPIFY_INTEGRATION.md) |
| Developers building custom integrations | [API Reference](api/API_REFERENCE.md) |
| Developers implementing webhooks | [Webhook Guide](docs/WEBHOOK_GUIDE.md) |
| Developers using Payment Links | [Payment Links Guide](docs/PAYMENT_LINKS_GUIDE.md) |
| Admin console operators | [Admin Console Guide](docs/ADMIN_CONSOLE_GUIDE.md) |
| Customers with questions about paying | [Customer FAQ](docs/CUSTOMER_FAQ.md) |

---

## How It Works

```
┌──────────────┐     ┌───────────────────┐     ┌──────────────┐
│  Your Store  │     │   SentinelGate    │     │   Payment    │
│              │────▶│                   │────▶│  Providers   │
│ WooCommerce  │     │  Hosted Checkout  │     │              │
│   Shopify    │     │  Payment Links    │     │  Card        │
│  Custom App  │     │  Order Routing    │     │  Mobile $    │
│              │◀────│                   │◀────│  Bank Xfer   │
└──────────────┘     └───────────────────┘     └──────────────┘
```

**For the merchant:** Install a plugin or add a few lines of code. SentinelGate handles everything else.

**For the customer:** A clean, secure payment page. Enter card details or use mobile money. Get a confirmation.

**For the developer:** One API, multiple providers. Automatic failover, webhook callbacks, idempotent processing.

---

## Supported Platforms

| Platform | Integration Type | Setup Time |
|----------|-----------------|------------|
| WooCommerce | Plugin (zip upload) | ~10 minutes |
| Shopify | Webhook middleware + redirect script | ~30 minutes |
| Custom Website | REST API | Varies |

## Supported Payment Methods (27)

| Category | Methods |
|----------|---------|
| **Digital Wallets** | Apple Pay, Google Pay |
| **Cards & Wallets** | UPI (INR), China UnionPay (CNY/USD) |
| **ACH & Direct Debit** | ACH USA (USD), ACH Ghana (GHS), eCheck USA (USD), ACH Dominican Republic (DOP) |
| **Real-Time Payments** | SEPA (EUR), PIX (BRL), EFT Canada (CAD), Interac Canada (CAD) |
| **Africa Instant** | NIP Nigeria (NGN), GIP Ghana (GHS), MMI Ghana (GHS), PesaLink Kenya (KES), RNDPS Rwanda (RWF), TISS Tanzania (TZS) |
| **Latin America** | SINPE Móvil Costa Rica (CRC), Snipe Móvil (MXN), SIPARD Dominican Republic (DOP) |
| **Bank-to-Bank** | Multi-currency B2B Transfer Collections |
| **Crypto** | Collect Crypto Payments, Crypto Transfers, Buy Crypto |
| **Gaming** | eChip Token Processing, eChip Exchange (eCHIP ↔ Crypto/Fiat) |

Full details: [Payment Methods Directory](docs/PAYMENT_METHODS.md)

---

## Quick Start

### WooCommerce (Fastest)

1. Download `sentinelgate-psp.zip`
2. WordPress Admin → Plugins → Upload → Activate
3. WooCommerce → Settings → Payments → SentinelGate PSP
4. Enter your API Key, Secret, and Webhook Secret
5. Save → Test a purchase

Full guide: [WooCommerce Integration](docs/WOOCOMMERCE_INTEGRATION.md)

### Shopify

1. Create a Custom App in Shopify with order permissions
2. Register your store with SentinelGate
3. Add webhooks pointing to SentinelGate
4. Add redirect script to checkout
5. Test an order

Full guide: [Shopify Integration](docs/SHOPIFY_INTEGRATION.md)

### Custom Integration

```bash
curl -X POST https://sentinelgate.biz/v1/hosted/create \
  -H "x-api-key: your_api_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": "50.00",
    "currency": "USD",
    "order_id": "ORD-001",
    "customer_email": "buyer@example.com",
    "callback_url": "https://yoursite.com/payment-callback",
    "success_url": "https://yoursite.com/thank-you"
  }'
```

Redirect the customer to the `redirect_url` in the response. Full guide: [API Reference](api/API_REFERENCE.md)

---

## Security

- **HTTPS/TLS 1.3** — All communication encrypted in transit
- **AES-256-GCM** — Sensitive credentials encrypted at rest
- **HMAC SHA-512** — All webhooks are signature verified
- **No card storage** — Card details are never stored on SentinelGate servers
- **PCI Compliant** — Hosted checkout means merchants don't need PCI certification
- **Idempotent processing** — Duplicate webhooks are safely ignored
- **Rate limiting** — All endpoints protected against abuse

---

## Support

| Channel | Details |
|---------|---------|
| Email | support@sentinelgate.biz |
| Documentation | https://sentinelgate.biz/docs |

---

## Documentation Index

### Guides

| File | Description |
|------|-------------|
| [MERCHANT_SETUP_GUIDE.md](docs/MERCHANT_SETUP_GUIDE.md) | Store owner setup, dashboard, managing payments |
| [WOOCOMMERCE_INTEGRATION.md](docs/WOOCOMMERCE_INTEGRATION.md) | WooCommerce plugin installation and configuration |
| [SHOPIFY_INTEGRATION.md](docs/SHOPIFY_INTEGRATION.md) | Shopify webhook and redirect setup |
| [API_REFERENCE.md](api/API_REFERENCE.md) | Full REST API documentation |
| [WEBHOOK_GUIDE.md](docs/WEBHOOK_GUIDE.md) | Webhook setup, verification, and handling |
| [PAYMENT_LINKS_GUIDE.md](docs/PAYMENT_LINKS_GUIDE.md) | Payment link creation and management |
| [PAYMENT_METHODS.md](docs/PAYMENT_METHODS.md) | All 25 supported payment methods directory |
| [CARD_PROCESSING_GUIDE.md](docs/CARD_PROCESSING_GUIDE.md) | Card payment flow and 3D Secure |
| [MOBILE_MONEY_GUIDE.md](docs/MOBILE_MONEY_GUIDE.md) | Mobile money integration guide |
| [ADMIN_CONSOLE_GUIDE.md](docs/ADMIN_CONSOLE_GUIDE.md) | Admin console operations |
| [MERCHANT_PORTAL_GUIDE.md](docs/MERCHANT_PORTAL_GUIDE.md) | Merchant portal features |
| [SECURITY.md](docs/SECURITY.md) | Security practices and compliance |
| [INSTALLATION.md](docs/INSTALLATION.md) | Self-hosted installation instructions |
| [CUSTOMER_FAQ.md](docs/CUSTOMER_FAQ.md) | FAQ for end customers |
| [CHANGELOG.md](CHANGELOG.md) | Platform change history |

### Payment Method Guides (25 methods — with test data and sample code)

| # | Method | Guide |
|---|--------|-------|
| 1 | UPI (India) | [methods/UPI.md](methods/UPI.md) |
| 2 | ACH (USA) | [methods/ACH_US.md](methods/ACH_US.md) |
| 3 | ACH (Ghana) | [methods/ACH_GHANA.md](methods/ACH_GHANA.md) |
| 4 | eCheck (USA) | [methods/ECHECK_US.md](methods/ECHECK_US.md) |
| 5 | EFT (Canada) | [methods/EFT_CANADA.md](methods/EFT_CANADA.md) |
| 6 | Interac (Canada) | [methods/INTERAC_CANADA.md](methods/INTERAC_CANADA.md) |
| 7 | SEPA (Europe) | [methods/SEPA.md](methods/SEPA.md) |
| 8 | PIX (Brazil) | [methods/PIX.md](methods/PIX.md) |
| 9 | China UnionPay | [methods/CHINA_UNIONPAY.md](methods/CHINA_UNIONPAY.md) |
| 10 | Snipe Móvil | [methods/SNIPE_MOVIL.md](methods/SNIPE_MOVIL.md) |
| 11 | Bank-to-Bank Collections | [methods/BANK_TO_BANK.md](methods/BANK_TO_BANK.md) |
| 12 | NIP — NIBSS Instant Payment (Nigeria) | [methods/NIP_NIGERIA.md](methods/NIP_NIGERIA.md) |
| 13 | GIP — GhIPSS Instant Pay (Ghana) | [methods/GIP_GHANA.md](methods/GIP_GHANA.md) |
| 14 | MMI — Mobile Money Interop (Ghana) | [methods/MMI_GHANA.md](methods/MMI_GHANA.md) |
| 15 | PesaLink (Kenya) | [methods/PESALINK.md](methods/PESALINK.md) |
| 16 | RNDPS (Rwanda) | [methods/RNDPS_RWANDA.md](methods/RNDPS_RWANDA.md) |
| 17 | TISS (Tanzania) | [methods/TISS_TANZANIA.md](methods/TISS_TANZANIA.md) |
| 18 | SINPE Móvil (Costa Rica) | [methods/SINPE_MOVIL.md](methods/SINPE_MOVIL.md) |
| 19 | SIPARD / LBTR (Dominican Republic) | [methods/SIPARD_DOMINICAN.md](methods/SIPARD_DOMINICAN.md) |
| 20 | ACH (Dominican Republic) | [methods/ACH_DOMINICAN.md](methods/ACH_DOMINICAN.md) |
| 21 | Crypto Processing (Collect) | [methods/CRYPTO_PROCESSING.md](methods/CRYPTO_PROCESSING.md) |
| 22 | Crypto Transfer (Send) | [methods/CRYPTO_TRANSFER.md](methods/CRYPTO_TRANSFER.md) |
| 23 | Buy Crypto (For Merchants) | [methods/CRYPTO_BUY.md](methods/CRYPTO_BUY.md) |
| 24 | eChip Token Processing | [methods/ECHIP_PROCESSING.md](methods/ECHIP_PROCESSING.md) |
| 25 | eChip Exchange | [methods/ECHIP_EXCHANGE.md](methods/ECHIP_EXCHANGE.md) |
| 26 | Apple Pay | [methods/APPLE_PAY.md](methods/APPLE_PAY.md) |
| 27 | Google Pay | [methods/GOOGLE_PAY.md](methods/GOOGLE_PAY.md) |

### Sample Code

| Language | Files | Run |
|----------|-------|-----|
| Node.js | [examples/nodejs/](examples/nodejs/) | `node test-all-methods.js` |
| PHP | [examples/php/](examples/php/) | `php test-all-methods.php` |
| Python | [examples/python/](examples/python/) | `python test_all_methods.py` |

---

© 2026 SentinelGate — Whyte AG Group. All rights reserved.
