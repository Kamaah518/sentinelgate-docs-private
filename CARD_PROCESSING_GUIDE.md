# Payment Links Guide

Payment Links let you accept payments without a website. Create a link, share it with your customer, and get paid.

---

## Overview

A payment link is a unique URL that opens a SentinelGate hosted checkout page. Customers click the link, choose their payment method, and complete the transaction.

Each link includes:
- A checkout URL: `https://sentinelgate.biz/pay/{token}`
- A QR code: `https://sentinelgate.biz/pay/{token}/qr.png`

---

## Link Types

| Type | Use Case |
|------|---------|
| **STANDARD** | General-purpose payment collection |
| **INVOICE** | Billing with invoice number and due date |
| **GAMING** | Gaming top-ups with gamer tag support |

---

## Creating Links

### From the Merchant Portal

1. Log in to `https://sentinelgate.biz:3200`
2. Navigate to **Payment Links** in the sidebar
3. Click **New Payment Link**
4. Fill in:
   - **Title** — What the customer sees (e.g., "Annual Subscription")
   - **Type** — STANDARD, INVOICE, or GAMING
   - **Amount** — Fixed amount or leave blank for customer-entered
   - **Currency** — USD, GHS, KES, EUR, GBP
   - **Description** — Optional detail for the customer
   - **Expiry** — Optional expiration date
   - **Custom amount** — Toggle to let customers enter their own amount
5. Click **Create Payment Link**
6. Copy the link or download the QR code

### From the API

```bash
curl -X POST https://sentinelgate.biz/payment-links/create \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "type": "STANDARD",
    "title": "Product Purchase",
    "amount_cents": 2500,
    "currency": "USD"
  }'
```

---

## Sharing Links

You can share payment links via:
- **Direct URL** — Send via email, SMS, or messaging apps
- **QR Code** — Print or display the QR code image
- **Embed** — Add the link to a button on your website

---

## Customer Experience

1. Customer clicks the payment link
2. SentinelGate checkout page loads showing amount and merchant name
3. Customer selects a payment method (Card or Mobile Money)
4. Customer completes the payment
5. Success page displays with confirmation
6. Customer is redirected to `success_url` if configured

---

## Managing Links

### List All Links

```bash
curl https://sentinelgate.biz/payment-links/list?merchant_id=your-id \
  -H "x-api-key: sk_live_your_key"
```

### Disable a Link

```bash
curl -X POST https://sentinelgate.biz/payment-links/{id}/disable \
  -H "x-api-key: sk_live_your_key"
```

Disabled links show an "expired" message to customers.

---

## Events

SentinelGate tracks events on each payment link:

| Event | Description |
|-------|-------------|
| `LINK_CREATED` | Link was created |
| `LINK_VIEWED` | Customer opened the checkout page |
| `INITIATED` | Customer started a payment |
| `INTENT_CREATED` | Payment intent submitted to provider |
| `LINK_DISABLED` | Link was disabled |

---

## Best Practices

- Use descriptive titles so customers know what they are paying for
- Set expiry dates for time-sensitive offers
- Enable custom amounts for donations or variable pricing
- Monitor link usage in the Merchant Portal

---

© 2026 SentinelGate — Whyte AG Group
