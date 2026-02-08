# Mobile Money Integration Guide
**SentinelGate Payment Platform**

Complete guide for accepting mobile money payments across Africa.

---

## 📚 Table of Contents

- [For Business Owners](#for-business-owners)
- [For Developers](#for-developers)
- [For End Users](#for-end-users)
- [Supported Networks](#supported-networks)
- [Code Examples](#code-examples)
- [Testing](#testing)
- [Going Live](#going-live)

---

## 👔 For Business Owners

### What is Mobile Money?

Mobile money allows your customers to pay using their mobile phone accounts instead of bank cards. This is the **most popular payment method in Africa**, with over 500 million active users.

### Why Accept Mobile Money?

✅ **Higher Conversion Rates**
- 80% of African consumers prefer mobile money over cards
- No credit card required
- Instant payment confirmation

✅ **Wider Customer Reach**
- Reach unbanked populations (70% of Africans)
- Accept payments from multiple countries
- Works on any phone (smartphone not required)

✅ **Lower Fraud Risk**
- Direct mobile wallet deductions
- Real-time authentication
- No chargebacks

✅ **Faster Settlement**
- Funds settled within 24 hours
- Lower transaction fees than cards
- No intermediary banks

### Supported Countries & Networks

| Country | Networks | Currency | Coverage |
|---------|----------|----------|----------|
| 🇰🇪 Kenya | M-Pesa (Safaricom), Airtel Money | KES | 98% |
| 🇬🇭 Ghana | MTN Momo, Vodafone Cash, AirtelTigo | GHS | 85% |
| 🇳🇬 Nigeria | MTN Momo, Airtel Money | NGN | 75% |
| 🇹🇿 Tanzania | M-Pesa (Vodacom), Airtel Money, Tigo Pesa | TZS | 90% |
| 🇺🇬 Uganda | MTN Momo, Airtel Money | UGX | 80% |
| 🇿🇦 South Africa | MTN Momo | ZAR | 60% |
| 🇿🇲 Zambia | MTN Momo, Airtel Money | ZMW | 70% |
| 🇷🇼 Rwanda | MTN Momo, Airtel Money | RWF | 85% |

### Transaction Limits

| Network | Minimum | Maximum | Processing Time |
|---------|---------|---------|-----------------|
| M-Pesa (Kenya) | 10 KES | 150,000 KES | 10-30 seconds |
| MTN Momo (Ghana) | 1 GHS | 10,000 GHS | 30-60 seconds |
| MTN Momo (Nigeria) | 100 NGN | 50,000 NGN | 1-2 minutes |
| Airtel Money | Varies | Varies | 30-90 seconds |

### Costs

**Transaction Fees:**
- 1.5% - 3.5% per transaction (varies by country/network)
- No setup fees
- No monthly fees
- No minimum volume requirements

**Example:**
- Customer pays: 1,000 KES
- Platform fee (2.5%): 25 KES
- **You receive: 975 KES**

---

## 💻 For Developers

### Integration Overview

SentinelGate provides a **unified API** for all mobile money networks. One integration works across all supported countries.

**Payment Flow:**
```
Customer → Website → SentinelGate API → Mobile Network → Customer Phone
                           ↓
                    Webhook Callback
                           ↓
                   Your Server (confirmation)
```

### Quick Start (5 minutes)

#### 1. Get API Credentials

Contact: support@sentinelgate.com

You'll receive:
- API Key: `sk_live_xxx`
- API Secret: `secret_xxx`
- Webhook Secret: `whsec_xxx`

#### 2. Create Your First Payment

**Using cURL (Raw API):**
```bash
curl -X POST http://185.229.224.244:3000/api/payments/create \
  -H "X-API-Key: sk_live_xxx" \
  -H "X-API-Secret: secret_xxx" \
  -H "Content-Type: application/json" \
  -d '{
    "amount_cents": 100000,
    "currency": "KES",
    "provider": "MPESA_SAFARICOM",
    "metadata": {
      "phone_number": "254712345678",
      "description": "Payment for Order #12345"
    },
    "callback_url": "https://yoursite.com/webhook"
  }'
```

**Response:**
```json
{
  "success": true,
  "payment_id": "pay_clx1234567890",
  "status": "PENDING",
  "provider": "MPESA_SAFARICOM",
  "amount_cents": 100000,
  "currency": "KES",
  "provider_reference": "MPE202602071234",
  "next_action": {
    "type": "WAIT_PROVIDER",
    "message": "Customer will receive M-Pesa STK push"
  }
}
```

**Using Node.js:**
```javascript
const axios = require('axios');

const API_KEY = process.env.SENTINELGATE_API_KEY;
const API_SECRET = process.env.SENTINELGATE_API_SECRET;
const BASE_URL = 'http://185.229.224.244:3000';

async function createMobileMoneyPayment(amount, phoneNumber, description) {
  try {
    const response = await axios.post(
      `${BASE_URL}/api/payments/create`,
      {
        amount_cents: amount * 100, // Convert to cents
        currency: 'KES',
        provider: 'MPESA_SAFARICOM',
        metadata: {
          phone_number: phoneNumber,
          description: description
        },
        callback_url: 'https://yoursite.com/webhook'
      },
      {
        headers: {
          'X-API-Key': API_KEY,
          'X-API-Secret': API_SECRET,
          'Content-Type': 'application/json'
        }
      }
    );

    return response.data;
  } catch (error) {
    console.error('Payment error:', error.response?.data || error.message);
    throw error;
  }
}

// Usage
const payment = await createMobileMoneyPayment(
  1000,              // 1000 KES
  '254712345678',    // Phone number
  'Order #12345'     // Description
);

console.log('Payment ID:', payment.payment_id);
console.log('Status:', payment.status);
```

**Using Python:**
```python
import requests
import os

API_KEY = os.environ['SENTINELGATE_API_KEY']
API_SECRET = os.environ['SENTINELGATE_API_SECRET']
BASE_URL = 'http://185.229.224.244:3000'

def create_mobile_money_payment(amount, phone_number, description):
    """Create M-Pesa payment"""
    
    response = requests.post(
        f'{BASE_URL}/api/payments/create',
        headers={
            'X-API-Key': API_KEY,
            'X-API-Secret': API_SECRET,
            'Content-Type': 'application/json'
        },
        json={
            'amount_cents': amount * 100,  # Convert to cents
            'currency': 'KES',
            'provider': 'MPESA_SAFARICOM',
            'metadata': {
                'phone_number': phone_number,
                'description': description
            },
            'callback_url': 'https://yoursite.com/webhook'
        }
    )
    
    response.raise_for_status()
    return response.json()

# Usage
payment = create_mobile_money_payment(
    1000,              # 1000 KES
    '254712345678',    # Phone number
    'Order #12345'     # Description
)

print(f"Payment ID: {payment['payment_id']}")
print(f"Status: {payment['status']}")
```

**Using PHP:**
```php
<?php

$apiKey = getenv('SENTINELGATE_API_KEY');
$apiSecret = getenv('SENTINELGATE_API_SECRET');
$baseUrl = 'http://185.229.224.244:3000';

function createMobileMoneyPayment($amount, $phoneNumber, $description) {
    global $apiKey, $apiSecret, $baseUrl;
    
    $data = [
        'amount_cents' => $amount * 100, // Convert to cents
        'currency' => 'KES',
        'provider' => 'MPESA_SAFARICOM',
        'metadata' => [
            'phone_number' => $phoneNumber,
            'description' => $description
        ],
        'callback_url' => 'https://yoursite.com/webhook'
    ];
    
    $ch = curl_init("$baseUrl/api/payments/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-API-Key: $apiKey",
        "X-API-Secret: $apiSecret",
        "Content-Type: application/json"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Usage
$payment = createMobileMoneyPayment(
    1000,              // 1000 KES
    '254712345678',    // Phone number
    'Order #12345'     // Description
);

echo "Payment ID: " . $payment['payment_id'] . "\n";
echo "Status: " . $payment['status'] . "\n";
?>
```

### Provider Codes

Use these provider codes in your API requests:

| Provider | Code | Countries | Type |
|----------|------|-----------|------|
| Safaricom M-Pesa | `MPESA_SAFARICOM` | Kenya | STK Push |
| Vodacom M-Pesa | `MPESA_VODACOM` | Tanzania | STK Push |
| MTN Mobile Money | `MTN_MOMO` | Ghana, Nigeria, Uganda, Zambia, Rwanda | USSD/App |
| Airtel Money | `AIRTEL_MONEY` | Kenya, Tanzania, Uganda, Zambia, Rwanda | USSD/App |
| Orange Money | `ORANGE_MONEY` | Multiple African countries | USSD/App |
| Hubtel | `HUBTEL` | Ghana | Aggregator (all networks) |

### Webhook Implementation

Webhooks notify you when payment status changes. **This is critical** for confirming payments.

#### Setting Up Webhooks

**1. Create webhook endpoint:**
```javascript
// Express.js
const express = require('express');
const crypto = require('crypto');

app.post('/webhook', 
  express.raw({ type: 'application/json' }),  // Important: use raw body
  (req, res) => {
    // 1. Verify signature FIRST
    const signature = req.headers['x-webhook-signature'];
    const hmac = crypto.createHmac('sha256', process.env.WEBHOOK_SECRET);
    const expectedSignature = hmac.update(req.body).digest('hex');
    
    if (!crypto.timingSafeEqual(Buffer.from(signature), Buffer.from(expectedSignature))) {
      console.error('Invalid webhook signature');
      return res.status(401).send('Unauthorized');
    }
    
    // 2. Parse event
    const event = JSON.parse(req.body);
    console.log('Webhook received:', event);
    
    // 3. Respond immediately (IMPORTANT!)
    res.status(200).send('OK');
    
    // 4. Process asynchronously
    processWebhook(event).catch(console.error);
  }
);

async function processWebhook(event) {
  switch (event.event) {
    case 'payment.completed':
      console.log(`✅ Payment ${event.payment_id} completed`);
      // Mark order as paid
      await markOrderPaid(event.payment_id);
      // Send confirmation email
      await sendEmail(event.metadata.email, 'Payment successful');
      break;
      
    case 'payment.failed':
      console.log(`❌ Payment ${event.payment_id} failed`);
      // Notify customer
      await sendEmail(event.metadata.email, 'Payment failed');
      break;
      
    case 'payment.cancelled':
      console.log(`⚠️  Payment ${event.payment_id} cancelled by user`);
      break;
  }
}
```

**2. Webhook event structure:**
```json
{
  "event": "payment.completed",
  "payment_id": "pay_clx1234567890",
  "merchant_id": "your-merchant-id",
  "status": "SUCCESS",
  "amount_cents": 100000,
  "currency": "KES",
  "provider": "MPESA_SAFARICOM",
  "provider_reference": "MPE202602071234",
  "timestamp": "2026-02-07T10:30:00Z",
  "metadata": {
    "phone_number": "254712345678",
    "description": "Order #12345"
  },
  "reference": "ORDER-12345"
}
```

**3. Event types:**

| Event | Description | Action Required |
|-------|-------------|-----------------|
| `payment.pending` | Payment initiated | None (optional notification) |
| `payment.processing` | Being processed by network | None |
| `payment.completed` | ✅ Payment successful | **Mark order as paid** |
| `payment.failed` | ❌ Payment failed | Notify customer, offer retry |
| `payment.cancelled` | User cancelled | Update order status |
| `payment.expired` | Payment request expired (5 mins) | Notify customer |

### Phone Number Formatting

**Critical:** Phone numbers must be in international format without `+` or spaces.
```javascript
function formatPhoneNumber(phone, country = 'KE') {
  // Remove spaces, dashes, plus signs
  phone = phone.replace(/[\s\-\+]/g, '');
  
  // Convert local to international
  const countryPrefixes = {
    'KE': '254',  // Kenya
    'GH': '233',  // Ghana
    'NG': '234',  // Nigeria
    'TZ': '255',  // Tanzania
    'UG': '256',  // Uganda
    'ZA': '27',   // South Africa
    'ZM': '260',  // Zambia
    'RW': '250'   // Rwanda
  };
  
  // If starts with 0, replace with country code
  if (phone.startsWith('0')) {
    phone = countryPrefixes[country] + phone.substring(1);
  }
  
  // Validate format
  if (!/^\d{10,15}$/.test(phone)) {
    throw new Error('Invalid phone number format');
  }
  
  return phone;
}

// Examples
formatPhoneNumber('0712345678', 'KE')  // → 254712345678
formatPhoneNumber('712345678', 'KE')   // → 254712345678
formatPhoneNumber('+254712345678')     // → 254712345678
formatPhoneNumber('0244123456', 'GH')  // → 233244123456
```

### Error Handling
```javascript
async function createPayment(data) {
  try {
    const response = await axios.post('/api/payments/create', data);
    return response.data;
  } catch (error) {
    if (error.response) {
      // API returned error
      const { code, message } = error.response.data.error;
      
      switch (code) {
        case 'INVALID_PHONE':
          throw new Error('Phone number format is invalid. Use format: 254XXXXXXXXX');
        case 'INVALID_AMOUNT':
          throw new Error('Amount must be between 10 and 150,000 KES');
        case 'PROVIDER_UNAVAILABLE':
          throw new Error('M-Pesa service temporarily unavailable. Please try again.');
        case 'INSUFFICIENT_FUNDS':
          throw new Error('Customer has insufficient funds');
        default:
          throw new Error(message || 'Payment failed');
      }
    } else {
      // Network error
      throw new Error('Unable to connect to payment service');
    }
  }
}
```

---

## 👤 For End Users (Customers)

### How to Pay with Mobile Money

#### For M-Pesa (Kenya/Tanzania):

1. **On Merchant Website:**
   - Click "Pay with M-Pesa"
   - Enter your M-Pesa phone number (e.g., 0712345678)

2. **On Your Phone:**
   - You'll receive an **STK push** prompt
   - Enter your M-Pesa PIN
   - Confirm the amount and merchant name

3. **Confirmation:**
   - Receive SMS confirmation instantly
   - Order is confirmed on website

**Example STK Push:**
```
Enter PIN to pay KES 1,000.00 to
Merchant Name
for Order #12345
```

#### For MTN Momo / Airtel / Other Networks:

1. **On Website:**
   - Select your network (MTN, Airtel, etc.)
   - Enter your mobile money number

2. **On Your Phone:**
   - Dial the USSD code shown (e.g., `*170#`)
   - Select "Payments"
   - Approve the transaction
   - Enter your PIN

3. **Confirmation:**
   - Receive SMS confirmation
   - Order confirmed

### Safety & Security Tips

✅ **Always Verify:**
- Merchant name matches the website you're on
- Amount is correct before entering PIN
- You receive official SMS confirmation from your network

❌ **Never:**
- Share your mobile money PIN with anyone
- Pay to personal numbers claiming to be businesses
- Click suspicious payment links in SMS/email

### Common Issues & Solutions

| Problem | Cause | Solution |
|---------|-------|----------|
| **Didn't receive STK push** | Network delay or wrong number | Wait 30 seconds, check number is correct, try again |
| **"Insufficient Funds"** | Balance too low | Top up your account and retry |
| **"Wrong PIN"** | Incorrect PIN entered | Re-enter correct PIN (3 attempts allowed) |
| **Transaction Timeout** | Didn't respond in 2 minutes | Try payment again |
| **"Daily Limit Exceeded"** | Hit your transaction limit | Wait 24 hours or contact your network |
| **Payment Pending** | Network processing | Wait up to 5 minutes for confirmation |

### Refund Process

If you need a refund:

1. **Contact the merchant first** (not your mobile network)
2. Merchant initiates refund via SentinelGate
3. Funds returned to your mobile wallet in **1-3 business days**
4. You'll receive SMS confirmation of refund

**Note:** Mobile money networks don't support instant refunds like cards do.

---

## 🧪 Testing

### Test Environment

Use these credentials for testing (sandbox mode):

**API Endpoint:** `http://185.229.224.244:3000`

**Test Phone Numbers:**

| Number | Result | Use Case |
|--------|--------|----------|
| `254712345678` | ✅ Success (instant) | Test successful payment |
| `254700000000` | ❌ Insufficient funds | Test failed payment |
| `254711111111` | ⏱️  Timeout | Test timeout handling |
| `254722222222` | 🚫 User cancelled | Test cancellation |

**Test Amounts:**
```javascript
// Different test scenarios
const testScenarios = {
  success: { amount: 1000, phone: '254712345678' },
  lowFunds: { amount: 100000, phone: '254700000000' },
  timeout: { amount: 500, phone: '254711111111' },
  cancel: { amount: 2000, phone: '254722222222' }
};
```

### Integration Testing Checklist

- [ ] Successful payment flow (happy path)
- [ ] Failed payment handling
- [ ] Timeout handling
- [ ] Cancelled payment handling
- [ ] Invalid phone number validation
- [ ] Amount validation (min/max)
- [ ] Webhook signature verification
- [ ] Duplicate webhook handling (idempotency)
- [ ] Network error handling
- [ ] Payment status polling

### Example Test Script
```javascript
const assert = require('assert');

async function testMobileMoneyIntegration() {
  console.log('🧪 Testing Mobile Money Integration...\n');
  
  // Test 1: Successful payment
  console.log('Test 1: Successful payment');
  const payment1 = await createPayment({
    amount: 1000,
    phone: '254712345678',
    description: 'Test payment'
  });
  assert.strictEqual(payment1.status, 'PENDING');
  console.log('✅ Payment created\n');
  
  // Test 2: Invalid phone
  console.log('Test 2: Invalid phone number');
  try {
    await createPayment({
      amount: 1000,
      phone: 'invalid',
      description: 'Test'
    });
    assert.fail('Should have thrown error');
  } catch (error) {
    assert.strictEqual(error.response.data.error.code, 'INVALID_PHONE');
    console.log('✅ Validation working\n');
  }
  
  // Test 3: Invalid amount
  console.log('Test 3: Amount too low');
  try {
    await createPayment({
      amount: 5,  // Below minimum
      phone: '254712345678',
      description: 'Test'
    });
    assert.fail('Should have thrown error');
  } catch (error) {
    assert.strictEqual(error.response.data.error.code, 'INVALID_AMOUNT');
    console.log('✅ Amount validation working\n');
  }
  
  console.log('🎉 All tests passed!');
}

testMobileMoneyIntegration();
```

---

## 🚀 Going Live (Production)

### Prerequisites

Before going live, ensure you have:

1. **Business Registration**
   - Company registered in operating country
   - Valid business license
   - Tax registration number

2. **KYC Documentation**
   - Company registration certificate
   - Director IDs/passports
   - Proof of business address
   - Bank account details

3. **Technical Requirements**
   - SSL certificate for website
   - Webhook endpoint configured
   - Error logging system
   - Transaction monitoring

### Production Onboarding Process

**Step 1: Apply for Production Access**

Email: support@sentinelgate.com

Include:
- Business name and registration number
- Country of operation
- Expected monthly volume
- Website URL
- Contact information

**Step 2: Submit KYC Documents**

Upload via secure portal:
- Company registration
- Director identification
- Proof of address
- Bank account details

**Processing time:** 1-3 business days

**Step 3: Receive Production Credentials**

You'll receive:
```
Production API Key: sk_live_prod_XXXXXXXXXX
Production API Secret: secret_prod_XXXXXXXXXX
Webhook Secret: whsec_prod_XXXXXXXXXX
```

**Step 4: Update Environment**
```bash
# Update .env
SENTINELGATE_ENV=production
SENTINELGATE_API_KEY=sk_live_prod_XXXXXXXXXX
SENTINELGATE_API_SECRET=secret_prod_XXXXXXXXXX
SENTINELGATE_WEBHOOK_SECRET=whsec_prod_XXXXXXXXXX
```

**Step 5: Test with Small Amount**

Before full launch:
```javascript
// Test with minimal amount
const testPayment = await createPayment({
  amount: 10,  // 10 KES (minimum)
  phone: 'YOUR_REAL_PHONE',
  description: 'Production test'
});
```

**Step 6: Go Live!**

Enable for all customers once testing confirms everything works.

### Production Best Practices

✅ **DO:**
- Implement comprehensive logging
- Monitor failed payment rates
- Set up alerts for errors
- Have customer support ready
- Keep credentials secure in environment variables
- Use HTTPS for all communications
- Implement rate limiting
- Cache payment status locally
- Handle all error cases gracefully

❌ **DON'T:**
- Hardcode production credentials
- Skip webhook signature verification
- Ignore failed payment notifications
- Process duplicate webhooks
- Store customer PINs
- Make payments without user confirmation
- Exceed API rate limits

### Monitoring & Alerts

Set up alerts for:
```javascript
// Example monitoring
const metrics = {
  failureRate: (failedPayments / totalPayments) * 100,
  avgProcessingTime: totalTime / completedPayments,
  webhookDelay: webhookReceivedTime - paymentCreatedTime
};

// Alert if failure rate > 5%
if (metrics.failureRate > 5) {
  sendAlert('High payment failure rate detected');
}

// Alert if processing time > 2 minutes
if (metrics.avgProcessingTime > 120000) {
  sendAlert('Slow payment processing');
}
```

### Compliance Requirements

**Kenya (M-Pesa):**
- Safaricom M-Pesa merchant account
- Valid Kenyan business registration
- KRA PIN certificate

**Ghana (MTN Momo, etc.):**
- GRA TIN certificate
- Valid business registration
- Company bank account

**Nigeria (MTN, Airtel):**
- CAC registration
- Tax identification number
- Corporate bank account

**General:**
- AML/KYC compliance
- Transaction monitoring
- Customer data protection (GDPR/PDPA compliant)

---

## 📊 Reporting & Analytics

### Transaction Reports

Get payment reports via API:
```bash
# Get today's transactions
curl -X GET "http://185.229.224.244:3000/api/payments?from_date=2026-02-07T00:00:00Z&to_date=2026-02-07T23:59:59Z" \
  -H "X-API-Key: sk_live_xxx" \
  -H "X-API-Secret: secret_xxx"
```

### Settlement Reports

Track fund settlements:
```javascript
const settlements = await sg.settlements.list({
  from_date: '2026-02-01',
  to_date: '2026-02-07'
});

settlements.forEach(s => {
  console.log(`Date: ${s.date}`);
  console.log(`Amount: ${s.currency} ${s.amount}`);
  console.log(`Status: ${s.status}`);
});
```

### Reconciliation

Daily reconciliation process:

1. **Export transactions** from SentinelGate
2. **Match with your orders** database
3. **Flag discrepancies** for review
4. **Contact support** for unmatched transactions

---

## 🆘 Troubleshooting

### Common Integration Issues

**1. "Invalid API Key"**
```javascript
// ❌ Wrong
headers: { 'Authorization': 'Bearer sk_live_xxx' }

// ✅ Correct
headers: {
  'X-API-Key': 'sk_live_xxx',
  'X-API-Secret': 'secret_xxx'
}
```

**2. "Invalid phone number"**
```javascript
// ❌ Wrong formats
'0712345678'        // Missing country code
'+254712345678'     // Has + sign
'254 712 345 678'   // Has spaces

// ✅ Correct
'254712345678'
```

**3. Webhooks not received**

Check:
- [ ] Webhook URL is publicly accessible
- [ ] SSL certificate is valid
- [ ] Server returns 200 OK quickly
- [ ] Firewall allows incoming connections

**4. "Payment stuck in PENDING"**

Possible causes:
- Customer didn't complete payment on phone
- Network delay (wait up to 5 minutes)
- Customer cancelled

Action:
- Check payment status via API
- Contact customer to verify
- Offer payment retry option

### Getting Help

**Documentation:**
- API Docs: http://185.229.224.244:3000/docs
- GitHub: https://github.com/Kamaah518/sentinelgate-docs

**Support:**
- Email: support@sentinelgate.com
- Phone: +254-XXX-XXXXXX (24/7)
- Status Page: http://status.sentinelgate.com

**Include in support request:**
- Payment ID
- Error message
- Request/response logs (remove credentials!)
- Expected vs actual behavior

---

## 📚 Additional Resources

### Code Repositories

- **Node.js SDK:** https://github.com/sentinelgate/node-sdk
- **Python SDK:** https://github.com/sentinelgate/python-sdk
- **PHP SDK:** https://github.com/sentinelgate/php-sdk
- **Examples:** https://github.com/sentinelgate/examples

### Postman Collection

Import for easy API testing:
```
https://api.sentinelgate.com/postman/collection.json
```

### Webhooks Testing

Use webhook.site for testing:
```
https://webhook.site/unique-id
```

### Mobile Money Provider Docs

- **M-Pesa:** https://developer.safaricom.co.ke/
- **MTN Momo:** https://momodeveloper.mtn.com/
- **Airtel Money:** https://developers.airtel.com/

---

**Last Updated:** February 8, 2026  
**Version:** 1.0.0  
**License:** Proprietary  

**© 2026 SentinelGate Payment Services. All rights reserved.**

For the latest updates, visit: https://docs.sentinelgate.com

