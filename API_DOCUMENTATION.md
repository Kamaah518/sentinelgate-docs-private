# SentinelGate Payment Platform - API Documentation

**Version:** 1.0.0  
**Base URL:** `http://185.229.224.244:3000`  
**Last Updated:** February 7, 2026

---

## Table of Contents

1. [Authentication](#authentication)
2. [Payments API](#payments-api)
3. [Payouts API](#payouts-api)
4. [Webhooks](#webhooks)
5. [Provider Management](#provider-management)
6. [Error Codes](#error-codes)
7. [Testing](#testing)

---

## Authentication

All API requests must include authentication headers:
```http
X-API-Key: sk_live_your_api_key
X-API-Secret: secret_your_api_secret
Content-Type: application/json
```

### Example Request
```bash
curl -X POST http://185.229.224.244:3000/api/payments/create \
  -H "X-API-Key: sk_live_d5c4d04e4e1b6deb034ee257ec89ef207b0166e1a32ebd5f77cff774d912a260" \
  -H "X-API-Secret: secret_89e895e658c8bcd31983e8f6d90ba0c1801c1361cb3fc253e15fec5985fc3248" \
  -H "Content-Type: application/json" \
  -d '{"amount_cents": 10000, "currency": "KES"}'
```

---

## Payments API

### 1. Create Payment

Create a new payment transaction.

**Endpoint:** `POST /api/payments/create`

#### Request Body

##### MPESA Payment
```json
{
  "amount_cents": 10000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "metadata": {
    "phone_number": "254712345678",
    "description": "Payment for Order #123",
    "customer_name": "John Doe"
  },
  "callback_url": "https://your-site.com/webhook"
}
```

##### Card Payment (BUNI)
```json
{
  "amount_cents": 50000,
  "currency": "KES",
  "provider": "BUNI",
  "rail": "CARD",
  "metadata": {
    "card_number": "4111111111111111",
    "expiry_date": "12/25",
    "cvv": "123",
    "description": "Payment for Order #456"
  },
  "callback_url": "https://your-site.com/webhook"
}
```

##### Bank Transfer (KareenHub)
```json
{
  "amount_cents": 100000,
  "currency": "KES",
  "provider": "KAREENHUB",
  "metadata": {
    "account_number": "1234567890",
    "bank_code": "01",
    "description": "Payment for Invoice #789"
  },
  "callback_url": "https://your-site.com/webhook"
}
```

#### Response (Success)
```json
{
  "success": true,
  "payment_id": "pay_clx1234567890",
  "merchant_id": "brooks-ud-zone",
  "amount_cents": 10000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "provider_reference": "MPE2026020712345",
  "status": "PENDING",
  "created_at": "2026-02-07T03:15:30Z",
  "next_action": {
    "type": "WAIT_PROVIDER",
    "message": "Customer will receive STK push on their phone"
  }
}
```

#### Response (Error)
```json
{
  "success": false,
  "error": {
    "code": "INVALID_PHONE",
    "message": "Phone number must be in format 254XXXXXXXXX",
    "field": "metadata.phone_number"
  }
}
```

---

### 2. Get Payment Status

Retrieve the status of a payment.

**Endpoint:** `GET /api/payments/{payment_id}`

#### Request
```bash
curl -X GET http://185.229.224.244:3000/api/payments/pay_clx1234567890 \
  -H "X-API-Key: sk_live_..." \
  -H "X-API-Secret: secret_..."
```

#### Response
```json
{
  "payment_id": "pay_clx1234567890",
  "merchant_id": "brooks-ud-zone",
  "amount_cents": 10000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "provider_reference": "MPE2026020712345",
  "status": "SUCCESS",
  "created_at": "2026-02-07T03:15:30Z",
  "completed_at": "2026-02-07T03:16:45Z",
  "metadata": {
    "phone_number": "254712345678",
    "description": "Payment for Order #123"
  }
}
```

**Payment Statuses:**
- `PENDING` - Payment initiated, awaiting completion
- `SUCCESS` - Payment completed successfully
- `FAILED` - Payment failed
- `CANCELLED` - Payment cancelled by user or system
- `EXPIRED` - Payment request expired (typically after 5 minutes)

---

### 3. List Payments

Retrieve a list of payments with optional filters.

**Endpoint:** `GET /api/payments`

#### Query Parameters
- `status` - Filter by status (optional)
- `provider` - Filter by provider (optional)
- `from_date` - ISO 8601 date (optional)
- `to_date` - ISO 8601 date (optional)
- `limit` - Number of results (default: 50, max: 100)
- `offset` - Pagination offset (default: 0)

#### Request
```bash
curl -X GET "http://185.229.224.244:3000/api/payments?status=SUCCESS&limit=10" \
  -H "X-API-Key: sk_live_..." \
  -H "X-API-Secret: secret_..."
```

#### Response
```json
{
  "payments": [
    {
      "payment_id": "pay_clx1234567890",
      "amount_cents": 10000,
      "currency": "KES",
      "status": "SUCCESS",
      "provider": "MPESA_SAFARICOM",
      "created_at": "2026-02-07T03:15:30Z"
    }
  ],
  "total": 245,
  "limit": 10,
  "offset": 0
}
```

---

## Payouts API

### 1. Create Payout

Send money to a customer (MPESA only for now).

**Endpoint:** `POST /api/payouts/create`

#### Request Body
```json
{
  "amount_cents": 5000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "destination": "254712345678",
  "metadata": {
    "reason": "Refund for Order #123",
    "reference": "REF123456"
  },
  "callback_url": "https://your-site.com/webhook"
}
```

#### Response
```json
{
  "success": true,
  "payout_id": "pyt_clx9876543210",
  "amount_cents": 5000,
  "currency": "KES",
  "destination": "254712345678",
  "provider": "MPESA_SAFARICOM",
  "status": "PENDING",
  "created_at": "2026-02-07T03:20:00Z"
}
```

---

## Webhooks

SentinelGate sends webhook notifications for payment status updates.

### Webhook Payload
```json
{
  "event": "payment.completed",
  "payment_id": "pay_clx1234567890",
  "merchant_id": "brooks-ud-zone",
  "status": "SUCCESS",
  "amount_cents": 10000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "provider_reference": "MPE2026020712345",
  "timestamp": "2026-02-07T03:16:45Z",
  "metadata": {
    "phone_number": "254712345678",
    "description": "Payment for Order #123"
  }
}
```

### Event Types
- `payment.pending` - Payment initiated
- `payment.completed` - Payment successful
- `payment.failed` - Payment failed
- `payment.cancelled` - Payment cancelled
- `payout.pending` - Payout initiated
- `payout.completed` - Payout successful
- `payout.failed` - Payout failed

### Webhook Verification

Verify webhook authenticity using the signature header:
```javascript
const crypto = require('crypto');

function verifyWebhook(payload, signature, webhookSecret) {
  const hmac = crypto.createHmac('sha256', webhookSecret);
  const digest = hmac.update(JSON.stringify(payload)).digest('hex');
  return crypto.timingSafeEqual(
    Buffer.from(signature),
    Buffer.from(digest)
  );
}

// Express.js example
app.post('/webhook', express.raw({type: 'application/json'}), (req, res) => {
  const signature = req.headers['x-webhook-signature'];
  const webhookSecret = 'whsec_your_webhook_secret';
  
  if (!verifyWebhook(req.body, signature, webhookSecret)) {
    return res.status(401).send('Invalid signature');
  }
  
  const event = JSON.parse(req.body);
  console.log('Webhook received:', event.event);
  
  res.status(200).send('OK');
});
```

---

## Provider Management

### Available Payment Providers

#### 1. MPESA Safaricom (Kenya)
```json
{
  "code": "MPESA_SAFARICOM",
  "display_name": "M-Pesa",
  "type": "MOBILE_MONEY",
  "countries": ["KE"],
  "currencies": ["KES"],
  "min_amount_cents": 100,
  "max_amount_cents": 15000000
}
```

#### 2. BUNI (Kenya, Nigeria)
```json
{
  "code": "BUNI",
  "display_name": "Buni",
  "type": "PSP",
  "rails": ["CARD", "MOBILE_MONEY"],
  "countries": ["KE", "NG"],
  "currencies": ["KES", "NGN"],
  "min_amount_cents": 100,
  "max_amount_cents": 100000000
}
```

#### 3. KareenHub (Kenya)
```json
{
  "code": "KAREENHUB",
  "display_name": "KareenHub",
  "type": "BANK_AGGREGATOR",
  "rails": ["BANK_TRANSFER"],
  "countries": ["KE"],
  "currencies": ["KES"],
  "min_amount_cents": 100,
  "max_amount_cents": 50000000
}
```

#### 4. Stripe (Global)
```json
{
  "code": "STRIPE",
  "display_name": "Stripe",
  "type": "PSP",
  "rails": ["CARD"],
  "countries": ["GLOBAL"],
  "currencies": ["USD", "EUR", "GBP", "KES"],
  "min_amount_cents": 50,
  "max_amount_cents": 99999999
}
```

---

## Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `INVALID_API_KEY` | 401 | API key is missing or invalid |
| `INVALID_API_SECRET` | 401 | API secret is missing or invalid |
| `INSUFFICIENT_FUNDS` | 402 | Merchant account has insufficient balance |
| `INVALID_AMOUNT` | 400 | Amount is below minimum or above maximum |
| `INVALID_CURRENCY` | 400 | Currency not supported |
| `INVALID_PHONE` | 400 | Phone number format invalid |
| `INVALID_PROVIDER` | 400 | Provider code not recognized |
| `PROVIDER_ERROR` | 502 | Error from payment provider |
| `PAYMENT_NOT_FOUND` | 404 | Payment ID does not exist |
| `PAYMENT_EXPIRED` | 410 | Payment request has expired |
| `RATE_LIMIT_EXCEEDED` | 429 | Too many requests |
| `SERVER_ERROR` | 500 | Internal server error |

### Error Response Format
```json
{
  "success": false,
  "error": {
    "code": "INVALID_PHONE",
    "message": "Phone number must be in format 254XXXXXXXXX",
    "field": "metadata.phone_number"
  }
}
```

---

## Testing

### Sandbox Mode

Use these test credentials for sandbox testing:

**Test Phone Numbers (MPESA):**
- Success: `254712345678`
- Failure: `254700000000`
- Timeout: `254711111111`

**Test Cards (BUNI):**
- Success: `4111111111111111` (Visa)
- Decline: `4000000000000002`
- Insufficient Funds: `4000000000009995`

### Test Environment
```
Base URL: http://185.229.224.244:3000
```

### Example Test Script
```bash
#!/bin/bash

API_KEY="sk_live_your_api_key"
API_SECRET="secret_your_api_secret"
BASE_URL="http://185.229.224.244:3000"

# Test MPESA payment
echo "Testing MPESA payment..."
curl -X POST "${BASE_URL}/api/payments/create" \
  -H "X-API-Key: ${API_KEY}" \
  -H "X-API-Secret: ${API_SECRET}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 100,
    "currency": "KES",
    "provider": "MPESA_SAFARICOM",
    "metadata": {
      "phone_number": "254712345678",
      "description": "Test Payment"
    },
    "callback_url": "https://webhook.site/unique-id"
  }'

echo -e "\n\nTest complete!"
```

---

## Rate Limits

- **Standard Plan:** 100 requests per minute
- **Premium Plan:** 1000 requests per minute

Rate limit headers are included in all responses:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1707274800
```

---

## Support

### Technical Support
- Email: Support@SentinelGAte.Biz
- Documentation: https://sentinelgate.biz/api/docs

### API Status
- Status Page: https://sentinelgate.biz/api/status

---

**© 2026 SentinelGate. All rights reserved.**
