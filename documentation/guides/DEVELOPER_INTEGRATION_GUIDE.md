# Developer Integration Guide

**For:** Software Developers & Technical Teams  
**Level:** Intermediate to Advanced  
**Time to Complete:** 1-2 hours

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [Authentication](#authentication)
3. [API Architecture](#api-architecture)
4. [Payment Integration](#payment-integration)
5. [Webhook Implementation](#webhook-implementation)
6. [Error Handling](#error-handling)
7. [Testing Strategy](#testing-strategy)
8. [Production Deployment](#production-deployment)
9. [Best Practices](#best-practices)

---

## Quick Start

### 1. Prerequisites

- API credentials (API Key, API Secret, Webhook Secret)
- Development environment with HTTP client
- SSL/TLS support for webhooks (production)
- Server that can receive HTTP POST requests

### 2. First API Call (cURL)
```bash
curl -X POST http://185.229.224.244:3000/api/payments/create \
  -H "X-API-Key: sk_live_your_api_key" \
  -H "X-API-Secret: secret_your_api_secret" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 10000,
    "currency": "KES",
    "provider": "MPESA_SAFARICOM",
    "metadata": {
      "phone_number": "254712345678",
      "description": "Test Payment"
    },
    "callback_url": "https://your-domain.com/webhook"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "payment_id": "pay_clx1234567890",
  "status": "PENDING",
  "provider_reference": "MPE2026020712345",
  "created_at": "2026-02-07T03:15:30Z"
}
```

---

## Authentication

### Header-Based Authentication

All API requests require two headers:
```http
X-API-Key: sk_live_your_api_key_here
X-API-Secret: secret_your_api_secret_here
```

### Security Best Practices

1. **Never** expose credentials in client-side code
2. **Always** use HTTPS in production
3. **Store** credentials in environment variables
4. **Rotate** credentials every 90 days
5. **Use** different credentials for test vs production

### Environment Variables Setup
```bash
# .env file
SENTINELGATE_API_KEY=sk_live_...
SENTINELGATE_API_SECRET=secret_...
SENTINELGATE_WEBHOOK_SECRET=whsec_...
SENTINELGATE_BASE_URL=http://185.229.224.244:3000
```

---

## API Architecture

### Base URL
```
Production: http://185.229.224.244:3000
Sandbox: http://185.229.224.244:3000 (same, use test credentials)
```

### RESTful Endpoints
```
POST   /api/payments/create       - Create a payment
GET    /api/payments/:id          - Get payment status
GET    /api/payments              - List payments
POST   /api/payouts/create        - Create a payout
GET    /api/payouts/:id           - Get payout status
```

### Request/Response Format

- **Content-Type:** `application/json`
- **Character Encoding:** UTF-8
- **Date Format:** ISO 8601 (e.g., `2026-02-07T03:15:30Z`)
- **Amount Format:** Integer in cents (10000 = 100.00 KES)

---

## Payment Integration

### Payment Flow Diagram
```
┌─────────────┐
│   Customer  │
└──────┬──────┘
       │ Initiates payment
       ▼
┌─────────────┐
│ Your Server │─────┐
└──────┬──────┘     │
       │            │ 1. Create payment request
       │            ▼
       │     ┌─────────────┐
       │     │ SentinelGate│
       │     └──────┬──────┘
       │            │ 2. Forward to provider
       │            ▼
       │     ┌─────────────┐
       │     │  Provider   │
       │     │ (M-Pesa/    │
       │     │  BUNI)      │
       │     └──────┬──────┘
       │            │ 3. Process payment
       │            ▼
       │     ┌─────────────┐
       │     │  Customer   │
       │     │  Bank/Phone │
       │     └──────┬──────┘
       │            │ 4. Confirmation
       │            ▼
       │     ┌─────────────┐
       │     │ SentinelGate│
       │     └──────┬──────┘
       │            │ 5. Webhook notification
       │            ▼
       │     ┌─────────────┐
       └─────│ Your Server │
             └─────────────┘
```

### Implementation Steps

#### Step 1: Create Payment
```javascript
const axios = require('axios');

async function createPayment(orderAmount, customerPhone, orderRef) {
  const response = await axios.post(
    `${process.env.SENTINELGATE_BASE_URL}/api/payments/create`,
    {
      amount_cents: orderAmount * 100, // Convert to cents
      currency: 'KES',
      provider: 'MPESA_SAFARICOM',
      metadata: {
        phone_number: customerPhone,
        description: `Payment for order ${orderRef}`,
        order_reference: orderRef
      },
      callback_url: `${process.env.YOUR_DOMAIN}/webhook/sentinelgate`
    },
    {
      headers: {
        'X-API-Key': process.env.SENTINELGATE_API_KEY,
        'X-API-Secret': process.env.SENTINELGATE_API_SECRET,
        'Content-Type': 'application/json'
      }
    }
  );
  
  return response.data;
}
```

#### Step 2: Store Payment Reference
```javascript
// Save to database
await db.orders.update({
  where: { id: orderRef },
  data: {
    payment_id: response.payment_id,
    payment_status: 'PENDING',
    provider_reference: response.provider_reference
  }
});
```

#### Step 3: Handle Webhook (see next section)

---

## Webhook Implementation

### Setting Up Webhook Endpoint
```javascript
const express = require('express');
const crypto = require('crypto');

const app = express();

// IMPORTANT: Use express.raw() for webhook signature verification
app.post('/webhook/sentinelgate', 
  express.raw({type: 'application/json'}),
  async (req, res) => {
    try {
      // 1. Verify webhook signature
      const signature = req.headers['x-webhook-signature'];
      const isValid = verifyWebhookSignature(
        req.body,
        signature,
        process.env.SENTINELGATE_WEBHOOK_SECRET
      );
      
      if (!isValid) {
        return res.status(401).send('Invalid signature');
      }
      
      // 2. Parse webhook payload
      const event = JSON.parse(req.body);
      
      // 3. Handle different event types
      switch (event.event) {
        case 'payment.completed':
          await handlePaymentCompleted(event);
          break;
        case 'payment.failed':
          await handlePaymentFailed(event);
          break;
        case 'payment.cancelled':
          await handlePaymentCancelled(event);
          break;
      }
      
      // 4. Acknowledge receipt (important!)
      res.status(200).send('OK');
      
    } catch (error) {
      console.error('Webhook error:', error);
      res.status(500).send('Error processing webhook');
    }
  }
);

function verifyWebhookSignature(payload, signature, secret) {
  const hmac = crypto.createHmac('sha256', secret);
  const digest = hmac.update(payload).digest('hex');
  return crypto.timingSafeEqual(
    Buffer.from(signature),
    Buffer.from(digest)
  );
}

async function handlePaymentCompleted(event) {
  // Update order status in database
  await db.orders.update({
    where: { payment_id: event.payment_id },
    data: {
      payment_status: 'COMPLETED',
      paid_at: new Date(event.timestamp),
      provider_reference: event.provider_reference
    }
  });
  
  // Send confirmation email
  await sendOrderConfirmationEmail(event.payment_id);
  
  // Update inventory
  await updateInventory(event.payment_id);
}
```

### Webhook Event Types
```typescript
type WebhookEvent = {
  event: 'payment.pending' | 'payment.completed' | 'payment.failed' | 'payment.cancelled';
  payment_id: string;
  merchant_id: string;
  status: string;
  amount_cents: number;
  currency: string;
  provider: string;
  provider_reference: string;
  timestamp: string; // ISO 8601
  metadata: Record<string, any>;
}
```

### Webhook Retry Logic

SentinelGate will retry failed webhooks with exponential backoff:
- Attempt 1: Immediate
- Attempt 2: 1 minute later
- Attempt 3: 5 minutes later
- Attempt 4: 15 minutes later
- Attempt 5: 1 hour later

**Best Practice:** Always return `200 OK` immediately, process async.

---

## Error Handling

### HTTP Status Codes

| Status | Meaning | Action |
|--------|---------|--------|
| 200 | Success | Process response |
| 400 | Bad Request | Check request parameters |
| 401 | Unauthorized | Verify API credentials |
| 402 | Payment Required | Insufficient merchant balance |
| 404 | Not Found | Check payment ID exists |
| 429 | Rate Limited | Implement exponential backoff |
| 500 | Server Error | Retry with backoff |
| 502 | Provider Error | Provider is down, try different provider |

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

### Retry Strategy
```javascript
async function createPaymentWithRetry(payload, maxRetries = 3) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      return await createPayment(payload);
    } catch (error) {
      if (attempt === maxRetries) throw error;
      
      // Exponential backoff: 2^attempt seconds
      const delay = Math.pow(2, attempt) * 1000;
      await new Promise(resolve => setTimeout(resolve, delay));
    }
  }
}
```

---

## Testing Strategy

### Test Environment

Use the same base URL with test credentials.

### Test Data

**Test Phone Numbers (M-Pesa):**
```javascript
const TEST_PHONES = {
  success: '254712345678',    // Always succeeds
  failure: '254700000000',    // Always fails
  timeout: '254711111111',    // Simulates timeout
  pending: '254722222222'     // Stays pending for 5 mins
};
```

**Test Cards (BUNI):**
```javascript
const TEST_CARDS = {
  success: {
    number: '4111111111111111',
    expiry: '12/25',
    cvv: '123'
  },
  decline: {
    number: '4000000000000002',
    expiry: '12/25',
    cvv: '123'
  },
  insufficient: {
    number: '4000000000009995',
    expiry: '12/25',
    cvv: '123'
  }
};
```

### Unit Test Example
```javascript
const { expect } = require('chai');
const SentinelGateClient = require('./sentinelgate-client');

describe('SentinelGate Payment Integration', () => {
  let client;
  
  beforeEach(() => {
    client = new SentinelGateClient(
      process.env.TEST_API_KEY,
      process.env.TEST_API_SECRET
    );
  });
  
  it('should create M-Pesa payment successfully', async () => {
    const result = await client.createMpesaPayment(
      10000,
      '254712345678',
      'Test Payment'
    );
    
    expect(result.success).to.be.true;
    expect(result.payment_id).to.match(/^pay_/);
    expect(result.status).to.equal('PENDING');
  });
  
  it('should handle invalid phone number', async () => {
    try {
      await client.createMpesaPayment(10000, 'invalid', 'Test');
      expect.fail('Should have thrown error');
    } catch (error) {
      expect(error.response.data.error.code).to.equal('INVALID_PHONE');
    }
  });
});
```

---

## Production Deployment

### Pre-Launch Checklist

- [ ] Replace test credentials with production credentials
- [ ] Update webhook URL to production domain
- [ ] Verify SSL/TLS certificate is valid
- [ ] Test webhook endpoint receives notifications
- [ ] Implement proper error logging
- [ ] Set up monitoring and alerts
- [ ] Create runbook for common issues
- [ ] Test with small real transaction
- [ ] Verify settlement account is configured

### Environment Configuration
```bash
# Production .env
NODE_ENV=production
SENTINELGATE_API_KEY=sk_live_production_key
SENTINELGATE_API_SECRET=secret_production_secret
SENTINELGATE_WEBHOOK_SECRET=whsec_production_secret
WEBHOOK_URL=https://yourdomain.com/webhook/sentinelgate
```

### Monitoring
```javascript
// Log all payment requests
logger.info('Payment created', {
  payment_id: result.payment_id,
  amount: amount_cents,
  provider: provider,
  customer: customer_id
});

// Track payment success rate
metrics.increment('payments.created');
metrics.increment(`payments.${provider}.created`);

// Alert on failures
if (result.status === 'FAILED') {
  alerting.notify('Payment failed', {
    payment_id: result.payment_id,
    error: result.error
  });
}
```

---

## Best Practices

### 1. Idempotency

Use unique reference IDs to prevent duplicate payments:
```javascript
const orderRef = `ORDER-${Date.now()}-${Math.random()}`;

await createPayment({
  // ...
  metadata: {
    order_reference: orderRef  // Unique per order
  }
});
```

### 2. Timeout Handling

Set appropriate timeouts:
```javascript
const response = await axios.post(url, data, {
  timeout: 30000, // 30 seconds
  headers: headers
});
```

### 3. Database Transactions

Wrap critical operations in transactions:
```javascript
await db.transaction(async (tx) => {
  await tx.orders.update({...});
  await tx.inventory.update({...});
  await tx.payments.create({...});
});
```

### 4. Logging

Log all payment operations:
```javascript
logger.info('Payment initiated', { payment_id, amount, customer });
logger.info('Webhook received', { event, payment_id });
logger.error('Payment failed', { payment_id, error });
```

### 5. Customer Communication

Always inform customers about payment status:
```javascript
await sendSMS(customer.phone, `Your payment of KES ${amount} was successful. Ref: ${payment_id}`);
```

---

## Advanced Topics

### Multi-Provider Fallback
```javascript
const PROVIDERS = ['MPESA_SAFARICOM', 'BUNI', 'KAREENHUB'];

async function createPaymentWithFallback(payload) {
  for (const provider of PROVIDERS) {
    try {
      const result = await createPayment({
        ...payload,
        provider: provider
      });
      
      if (result.success) return result;
    } catch (error) {
      console.error(`${provider} failed:`, error);
      continue; // Try next provider
    }
  }
  
  throw new Error('All providers failed');
}
```

### Rate Limiting
```javascript
const rateLimit = require('express-rate-limit');

const limiter = rateLimit({
  windowMs: 60 * 1000, // 1 minute
  max: 100, // 100 requests per minute
  message: 'Too many requests'
});

app.use('/api/', limiter);
```

---

## Support & Resources

- **API Reference:** See `API_DOCUMENTATION.md`
- **Code Examples:** See `code-examples/` directory
- **Postman Collection:** `SentinelGate_API.postman_collection.json`
- **Technical Support:** Support@SentinelGAte.Biz
- **Status Page:** http://status.sentinelgate.biz

---

**© 2026 SentinelGate. All rights reserved.**
