# Merchant Setup Guide

This guide walks you through setting up your store to accept payments through SentinelGate.

---

## Step 1: Get Your Credentials

After onboarding, you will receive:

| Credential | Description |
|-----------|-------------|
| **Merchant ID** | Your unique merchant identifier (e.g., `your-merchant-id`) |
| **API Key** | Secret key for authenticating API requests (starts with `sk_live_`) |
| **Tenant ID** | Your tenant scope (usually `default`) |

Keep these credentials secure. Never expose your API key in client-side code.

---

## Step 2: Choose Your Integration

| Platform | Best For | Guide |
|----------|---------|-------|
| WooCommerce | WordPress stores | [WooCommerce Integration](WOOCOMMERCE_INTEGRATION.md) |
| Shopify | Shopify stores | [Shopify Integration](SHOPIFY_INTEGRATION.md) |
| Custom | Any website or app | [API Reference](../api/API_REFERENCE.md) |

---

## Step 3: Configure Payment Methods

SentinelGate routes payments based on your configured rails:

| Rail | What It Covers |
|------|---------------|
| `CARD` | Visa, Mastercard credit/debit cards |
| `MOMO` | Mobile money (Ghana, Kenya, East Africa) |
| `BANK_TRANSFER` | Direct bank transfers |

Your rails are configured during onboarding. Contact support to add or modify rails.

---

## Step 4: Set Up Webhooks

Webhooks notify your server when payment status changes. You need a publicly accessible HTTPS endpoint.

See: [Webhook Guide](WEBHOOK_GUIDE.md)

---

## Step 5: Test

1. Make a test payment through your store
2. Verify the order is marked as paid
3. Check the transaction in the Merchant Portal

---

## Merchant Portal

Access your dashboard at: `https://sentinelgate.biz:3200`

**Login with:**
- Tenant ID: `default` (or your assigned tenant)
- API Key: Your `sk_live_` key

**Dashboard features:**
- Transaction history with search and filters
- Revenue and success rate metrics
- Payment link creation and management
- Settlement tracking
- Dispute management
- API key management
- Audit log

See: [Merchant Portal Guide](MERCHANT_PORTAL_GUIDE.md)

---

## Going Live Checklist

- [ ] Received live API key from SentinelGate
- [ ] Plugin/integration installed and configured
- [ ] Webhook endpoint configured and verified
- [ ] Test payment completed successfully
- [ ] Success and cancel redirect URLs configured
- [ ] SSL certificate active on your domain

---

## Support

- **Email:** support@sentinelgate.biz
- **Response time:** Within 24 hours
- **Emergency:** Flag as urgent in subject line

---

© 2026 SentinelGate — Whyte AG Group
