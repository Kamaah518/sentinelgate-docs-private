# Card Payments - Complete Guide
**Sentinel Payment Gateway - For Developers, Merchants & Customers**

Version: 2.0  
Last Updated: February 12, 2026  
Audience: All Stakeholders

---

## 📚 Documentation for Different Audiences

This documentation is organized into sections for different users:

- **[For Customers](#for-customers)** - How to pay with cards safely
- **[For Merchants](#for-merchants)** - Business setup and management
- **[For Developers](#for-developers)** - Technical integration guide

---

# For Customers

## 🛒 How to Pay with Your Card

### What Cards Can I Use?

You can pay with the following cards on Sentinel-powered websites:

✅ **Debit Cards:**
- Visa Debit
- Mastercard Debit
- Verve (Nigeria)
- Local bank debit cards

✅ **Credit Cards:**
- Visa
- Mastercard
- American Express
- Discover
- Diners Club

✅ **Prepaid Cards:**
- Visa Prepaid
- Mastercard Prepaid

### Supported Countries & Currencies

We accept cards from **190+ countries** and support **150+ currencies** including:
- 🇺🇸 USD (US Dollar)
- 🇪🇺 EUR (Euro)
- 🇬🇧 GBP (British Pound)
- 🇰🇪 KES (Kenyan Shilling)
- 🇿🇦 ZAR (South African Rand)
- 🇳🇬 NGN (Nigerian Naira)
- 🇬🇭 GHS (Ghanaian Cedi)
- And many more...

---

## 🔒 Is It Safe to Pay with My Card?

### YES! Here's Why:

**1. Bank-Level Encryption**
- Your card details are encrypted using the same technology banks use
- We NEVER store your full card number
- All data is transmitted over secure HTTPS connections

**2. 3D Secure Protection**
- Extra verification step (like OTP from your bank)
- Protects you from unauthorized transactions
- Required for transactions over certain amounts

**3. PCI DSS Compliant**
- We meet the highest security standards for card processing
- Regular security audits
- Continuous monitoring for fraud

**4. Two-Factor Authentication**
- Your bank may send you an OTP (One-Time Password)
- Confirms that YOU are making the purchase
- Added layer of security

### What We Do to Protect You:

✅ SSL/TLS encryption for all transactions  
✅ Fraud detection and prevention systems  
✅ Real-time transaction monitoring  
✅ Secure tokenization (we don't store your full card number)  
✅ Compliance with international security standards  

---

## 💳 Step-by-Step: How to Complete Your Payment

### Step 1: Enter Your Card Details

When you click "Pay" on a merchant's website, you'll see a secure payment form:

```
┌─────────────────────────────────────┐
│  Secure Payment - Sentinel Gateway  │
├─────────────────────────────────────┤
│                                     │
│  Card Number: [________________]    │
│  🔒 1234 5678 9012 3456            │
│                                     │
│  Expiry Date: [MM] / [YY]          │
│  🔒 12 / 27                        │
│                                     │
│  CVV/CVC: [___]  ⓘ                │
│  🔒 123                            │
│                                     │
│  Cardholder Name:                  │
│  [_________________________]       │
│  John Doe                          │
│                                     │
│  [✓] Save card for future use      │
│                                     │
│  Total: $100.00 USD                │
│                                     │
│  [ Pay Securely ]                  │
│                                     │
│  🔒 Your payment is secure          │
└─────────────────────────────────────┘
```

**What to Enter:**
- **Card Number:** The 16-digit number on the front of your card
- **Expiry Date:** MM/YY format (e.g., 12/27)
- **CVV/CVC:** 3 digits on the back (4 digits for Amex, on the front)
- **Name:** Exactly as it appears on your card

### Step 2: Verify Your Identity (3D Secure)

For your security, you may be asked to verify the payment:

```
┌─────────────────────────────────────┐
│     🏦 Your Bank Verification       │
├─────────────────────────────────────┤
│                                     │
│  To complete this payment,          │
│  please verify with your bank:      │
│                                     │
│  📱 Enter OTP sent to ***-***-1234 │
│                                     │
│  OTP: [_] [_] [_] [_] [_] [_]      │
│                                     │
│  Didn't receive code?               │
│  [Resend OTP]                      │
│                                     │
│  [ Verify & Pay ]                  │
│                                     │
│  ⏱️ Code expires in 5:00            │
└─────────────────────────────────────┘
```

**Verification Methods:**
- **SMS OTP:** Code sent to your phone
- **Bank App:** Approve in your banking app
- **Email:** Code sent to your email
- **Security Questions:** Answer preset questions

### Step 3: Payment Confirmation

Once verified, you'll see:

```
┌─────────────────────────────────────┐
│     ✅ Payment Successful!          │
├─────────────────────────────────────┤
│                                     │
│  Amount Paid: $100.00 USD          │
│  Transaction ID: TXN-ABC123XYZ     │
│  Date: Feb 12, 2026 at 5:30 PM    │
│                                     │
│  Card: Visa ending in 3456         │
│  Merchant: Example Store           │
│                                     │
│  📧 Receipt sent to:               │
│  your.email@example.com            │
│                                     │
│  [ Download Receipt ]              │
│  [ Return to Store ]               │
│                                     │
└─────────────────────────────────────┘
```

**What Happens Next:**
1. ✅ You'll receive an email receipt
2. ✅ Your bank will send you a transaction notification
3. ✅ The merchant will process your order
4. ✅ Transaction appears on your bank statement in 1-3 days

---

## ❓ Common Questions from Customers

### Q: Where is the CVV/CVC number?

**For Visa, Mastercard, Discover:**
- **Location:** Back of card
- **Digits:** 3 digits
- **Example:** 123

**For American Express:**
- **Location:** Front of card (above card number)
- **Digits:** 4 digits
- **Example:** 1234

### Q: Why do I need to enter an OTP?

The OTP (One-Time Password) is for **your security**:
- Confirms you authorized the payment
- Prevents someone else from using your card
- Required by your bank for large transactions
- Standard security practice worldwide

### Q: Is it safe to save my card?

**YES, it's safe when you see "Powered by Sentinel"**

When you save a card:
- We store only a secure "token" (not your full card number)
- Your actual card details are kept by secure payment processors
- You can delete saved cards anytime
- Makes future payments faster and easier

**Never save cards on:**
- Unsecured websites (no HTTPS padlock)
- Unknown or suspicious merchants
- Public or shared devices

### Q: What if I don't receive the OTP?

**Try these steps:**

1. **Wait 60 seconds** - Sometimes SMS is delayed
2. **Check spam/junk** - For email OTPs
3. **Click "Resend OTP"** - Request a new code
4. **Contact your bank** - They can verify your phone number
5. **Try alternative method** - Use bank app if SMS fails

### Q: Can I use my card internationally?

**Yes!** Most cards work internationally:

✅ **Visa & Mastercard:** Accepted worldwide  
✅ **American Express:** Most countries (check merchant)  
✅ **Verve:** Nigeria and some African countries  

**Note:** Your bank may charge international transaction fees (typically 2-3%)

### Q: How long does payment take?

| Step | Time |
|------|------|
| Entering card details | 30-60 seconds |
| 3D Secure verification | 30-60 seconds |
| Payment processing | 2-10 seconds |
| **Total** | **~2 minutes** |

Funds are deducted immediately, but may show as "pending" for 1-3 days.

### Q: What if my payment fails?

**Common reasons & solutions:**

| Problem | Solution |
|---------|----------|
| **Insufficient Funds** | Check your balance, try another card |
| **Card Expired** | Check expiry date, use valid card |
| **Wrong CVV** | Double-check the 3-digit code |
| **Card Blocked** | Contact your bank to unlock |
| **Daily Limit Exceeded** | Wait 24 hours or contact bank |
| **International Payment Blocked** | Ask bank to enable international payments |

**Still having issues?**
1. Contact the merchant's support
2. Call your bank
3. Try a different card or payment method

---

## 🔐 How to Stay Safe When Paying Online

### ✅ DO:

- **Check for HTTPS** - Look for the padlock 🔒 in your browser
- **Verify the merchant** - Make sure you're on the correct website
- **Use secure networks** - Avoid public WiFi for payments
- **Keep records** - Save receipts and transaction IDs
- **Enable notifications** - Get SMS alerts for all transactions
- **Use strong passwords** - For merchant accounts
- **Log out after paying** - Especially on shared devices

### ❌ DON'T:

- **Share your CVV** - NEVER give it over phone or email
- **Share OTP codes** - Your bank will NEVER ask for these
- **Use public computers** - For financial transactions
- **Save cards on unfamiliar sites** - Only on trusted merchants
- **Click suspicious links** - Could be phishing attempts
- **Ignore bank alerts** - Report unknown transactions immediately

### 🚨 Red Flags - When NOT to Pay:

❌ Website doesn't have HTTPS (no padlock)  
❌ Spelling errors in website URL  
❌ Unusually low prices (too good to be true)  
❌ Pressure to pay immediately  
❌ Request to pay via email or messaging apps  
❌ Asking for card details via email  
❌ No contact information for the seller  

---

## 🆘 What to Do If Something Goes Wrong

### If You Were Charged Twice:

1. **Don't panic** - This is usually a temporary hold
2. **Check your bank statement** - One charge may be pending
3. **Contact the merchant** - They can verify the charge
4. **Wait 3-5 days** - Duplicate charges are usually reversed automatically
5. **Dispute if needed** - Contact your bank if not resolved

### If You Didn't Authorize a Charge:

**Act immediately:**

1. **Contact your bank** - Report unauthorized transaction
2. **Freeze your card** - Prevent further unauthorized charges
3. **File a dispute** - Banks have fraud protection
4. **Change passwords** - For any saved accounts
5. **Monitor your statements** - Check for other suspicious activity

### If You Need a Refund:

1. **Contact the merchant first** - Most refunds are processed by merchants
2. **Provide transaction details** - Transaction ID, date, amount
3. **Wait 5-10 business days** - Standard refund processing time
4. **Check your statement** - Refund may show as a credit
5. **Dispute if needed** - If merchant doesn't respond within 14 days

### Getting Help:

**Merchant Support:**
- Check the merchant's website for contact info
- Have your order number and transaction ID ready

**Your Bank:**
- Call the number on the back of your card
- Most banks have 24/7 fraud hotlines

**Sentinel Support:**
- If you see "Powered by Sentinel" on the payment page
- Email: support@sentinelgate.biz
- Available 24/7 for payment issues

---

## 💡 Pro Tips for Customers

### 1. Set Up Transaction Alerts

Most banks offer SMS/email alerts for:
- Every transaction (free)
- Transactions over a certain amount
- International transactions
- Online purchases

**Why?** You'll know immediately if someone uses your card.

### 2. Use Virtual Cards

Some banks offer virtual card numbers:
- Temporary card number for online shopping
- Can be disabled after use
- Limits exposure of your real card number
- Perfect for new or unfamiliar merchants

### 3. Keep Your Card Details Updated

- Check expiry date before shopping
- Update saved cards when you get new ones
- Remove old cards from merchant accounts
- Notify merchants if card is lost/stolen

### 4. Understand Your Rights

**Chargeback Rights:**
- You can dispute unauthorized charges
- Banks typically give you 60-120 days to dispute
- Keep all receipts and correspondence
- Zero liability for fraud (most cards)

**Refund Rights:**
- Merchants typically have refund policies
- Digital goods may have different rules
- Read merchant's return policy before buying
- Your bank can help if merchant doesn't respond

---

# For Merchants

## 💼 Business Overview

### Why Accept Card Payments?

**Increase Sales:**
- 67% of customers prefer card payments
- Average 30% increase in sales after adding cards
- Higher conversion rates than cash-only
- Customers spend 18% more with cards

**Better Cash Flow:**
- Funds in your account in 1-3 business days
- Automatic reconciliation
- Reduced handling of cash
- Lower risk of theft

**Global Reach:**
- Accept international customers
- Multi-currency support
- 24/7 payment acceptance
- No geographical limitations

### What You Need to Get Started

1. **Business Registration**
   - Valid business license
   - Tax identification number
   - Business bank account

2. **Sentinel Merchant Account**
   - Sign up at merchant portal
   - Complete verification (1-2 days)
   - No setup fees

3. **Integration**
   - Add payment button to website
   - Or use our hosted checkout page
   - 15 minutes to go live

---

## 🚀 Getting Started as a Merchant

### Step 1: Create Your Account

1. Go to https://sentinelgate.biz:3200
2. Click "Sign Up for Merchant Account"
3. Provide:
   - Business name and registration
   - Contact information
   - Bank account for settlements
   - Business category

### Step 2: Complete Verification

**What We Need:**
- Business registration documents
- Director ID/Passport
- Business bank statement (last 3 months)
- Business address proof

**Processing Time:** 1-2 business days

### Step 3: Configure Your Settings

**In Your Merchant Dashboard:**

1. **Set Up Payment Options**
   - Enable card payments
   - Choose supported currencies
   - Set minimum/maximum amounts

2. **Configure Webhooks**
   - Get real-time payment notifications
   - Update order status automatically

3. **Customize Checkout**
   - Add your logo
   - Choose brand colors
   - Set return URLs

### Step 4: Go Live!

**Two Integration Options:**

**Option A: Hosted Checkout (Easiest)**
- Add a "Pay with Card" button
- Customers redirected to our secure page
- No coding required
- 15 minutes to integrate

**Option B: API Integration**
- Full control over design
- Seamless checkout experience
- Requires developer
- See Developer section below

---

## 📊 Merchant Dashboard Guide

### Dashboard Overview

```
┌────────────────────────────────────────────────┐
│  Sentinel Merchant Dashboard                    │
├────────────────────────────────────────────────┤
│                                                 │
│  Today's Summary                                │
│  ┌──────────┬──────────┬──────────┬──────────┐ │
│  │ Revenue  │ Trans.   │ Success  │ Avg Order│ │
│  │ $2,450   │   45     │  95.6%   │  $54.44  │ │
│  └──────────┴──────────┴──────────┴──────────┘ │
│                                                 │
│  Recent Transactions                            │
│  ┌────────────────────────────────────────────┐ │
│  │ 5:30 PM  $150.00  ✅ Succeeded  Visa-3456 │ │
│  │ 5:28 PM   $89.99  ✅ Succeeded  MC-8901   │ │
│  │ 5:25 PM  $245.50  ⏱️ Processing Visa-1234 │ │
│  │ 5:20 PM   $67.00  ❌ Failed     Visa-5678 │ │
│  └────────────────────────────────────────────┘ │
│                                                 │
│  [ View All ]  [ Export ]  [ Analytics ]       │
└────────────────────────────────────────────────┘
```

### Key Features

**1. Transaction Management**
- View all transactions in real-time
- Filter by status, date, amount, card type
- Export to CSV/Excel for accounting
- Search by customer email or transaction ID

**2. Refund Management**
- Issue full or partial refunds
- Track refund status
- Automatic customer notifications
- Refund history and reports

**3. Analytics & Reports**
- Sales trends and charts
- Success rate monitoring
- Popular payment methods
- Peak transaction times
- Revenue forecasting

**4. Customer Management**
- Save customer card tokens
- View customer payment history
- Manage saved cards
- Customer lifetime value

**5. Settings**
- Update business information
- Manage team members
- Configure notifications
- Set transaction limits
- Webhook management

---

## 💰 Pricing & Fees

### Transaction Fees

| Transaction Type | Fee | Example |
|------------------|-----|---------|
| **Domestic Cards** | 2.9% + $0.30 | $100 sale = $3.20 fee |
| **International Cards** | 3.9% + $0.30 | $100 sale = $4.20 fee |
| **Amex Cards** | 3.5% + $0.30 | $100 sale = $3.80 fee |

**Volume Discounts Available:**
- $50K+/month: 2.7% + $0.30
- $100K+/month: 2.5% + $0.30
- $500K+/month: Custom pricing

### Additional Fees

- **Monthly Fee:** $0 (No monthly minimum!)
- **Setup Fee:** $0
- **Refund Fee:** $0 (we refund our fee too)
- **Chargeback Fee:** $15 (only if you lose)
- **Payout Fee:** $0 (free daily payouts)

### When Do You Get Paid?

**Standard Payout Schedule:**
- **Business Days:** T+2 (2 business days after transaction)
- **Weekends:** Next business day
- **Holidays:** Next business day

**Example:**
- Monday sale → Wednesday payout
- Friday sale → Tuesday payout (skip weekend)

**Instant Payouts Available:**
- 1% fee for instant transfer
- Funds in minutes
- Available for verified accounts

---

## 🛡️ Fraud Prevention for Merchants

### Built-In Fraud Protection

**Automatic Fraud Detection:**
- Machine learning algorithms
- Real-time risk scoring
- Unusual pattern detection
- Velocity checks (too many transactions)

**3D Secure Benefits:**
- Liability shift to card issuer
- Lower chargeback rates
- Higher customer trust
- Required for high-value transactions

### How to Reduce Chargebacks

**Best Practices:**

1. **Clear Product Descriptions**
   - Accurate photos and details
   - Clear pricing and fees
   - Realistic delivery times

2. **Great Customer Service**
   - Respond to inquiries quickly
   - Handle complaints promptly
   - Make refunds easy

3. **Clear Billing Descriptor**
   - Use recognizable business name
   - Include contact phone number
   - Customers should recognize the charge

4. **Require CVV**
   - Always ask for CVV/CVC code
   - Significantly reduces fraud
   - Industry best practice

5. **Use Address Verification**
   - Match billing address with card
   - Reduces fraudulent transactions
   - Optional but recommended

### Warning Signs of Fraud

🚨 **Be cautious when you see:**

- Multiple transactions from same IP
- High-value first-time purchase
- Rush shipping requests
- Different billing and shipping addresses
- Customer refuses to provide additional verification
- Multiple cards tried in quick succession
- Orders from high-risk countries (for your business)

**What to do:** Contact customer to verify before shipping

---

## 📧 Customer Communication

### Automatic Emails

**Payment Successful:**
```
Subject: Payment Received - Order #12345

Dear [Customer Name],

Your payment of $100.00 has been received successfully!

Transaction ID: TXN-ABC123XYZ
Date: February 12, 2026 at 5:30 PM
Card: Visa ending in 3456

Order Details:
Order #12345
Estimated Delivery: February 15, 2026

Need help? Contact us at support@yourstore.com

Thank you for your business!
[Your Store Name]
```

**Payment Failed:**
```
Subject: Payment Issue - Order #12345

Dear [Customer Name],

We encountered an issue processing your payment.

Reason: Insufficient funds

Please:
1. Check your card balance
2. Try a different card
3. Contact your bank if needed

[Retry Payment Button]

Need help? We're here for you!
support@yourstore.com

[Your Store Name]
```

**Refund Processed:**
```
Subject: Refund Issued - Order #12345

Dear [Customer Name],

Your refund has been processed successfully!

Refund Amount: $100.00
Original Transaction: TXN-ABC123XYZ
Refund ID: REF-XYZ789

The refund will appear in your account within 5-10 business days.

Questions? Contact support@yourstore.com

[Your Store Name]
```

---

## 🔧 Merchant Troubleshooting

### High Decline Rate?

**Common Causes:**

1. **Insufficient Customer Information**
   - Solution: Request billing address
   - Enable AVS (Address Verification System)

2. **International Cards Blocked**
   - Solution: Enable international payments in settings

3. **Customers Not Completing 3DS**
   - Solution: Simplify checkout process
   - Educate customers about security

4. **Technical Issues**
   - Solution: Check integration
   - Test payment flow regularly

### Low Conversion Rate?

**Optimization Tips:**

1. **Simplify Checkout**
   - Fewer form fields
   - Save card option
   - Guest checkout available

2. **Build Trust**
   - Display security badges
   - Show accepted cards
   - Add customer reviews

3. **Transparent Pricing**
   - Show all fees upfront
   - No surprise charges
   - Clear shipping costs

4. **Mobile Optimization**
   - Responsive design
   - Large buttons
   - Easy form filling

### Need Help?

**Merchant Support:**
- **Email:** merchant-support@sentinelgate.biz
- **Phone:** +1-XXX-XXX-XXXX (Business hours)
- **Portal:** 24/7 ticket system
- **Response Time:** < 2 hours

**Account Manager:**
- Assigned for $50K+/month merchants
- Dedicated support
- Custom integrations
- Volume discounts

---

# For Developers

## 👨‍💻 Technical Integration Guide

### Quick Start (5 Minutes)

**1. Get API Credentials**

Login to merchant portal and get:
```bash
MERCHANT_ID=merch_abc123
API_KEY=sk_live_xxxxxxxxxxxxx
API_SECRET=xxxxxxxxxxxxxxxxxxxxx
```

**2. Install SDK (Optional)**

```bash
# Node.js
npm install @sentinel-gateway/sdk

# Python
pip install sentinel-gateway

# PHP
composer require sentinel-gateway/sdk
```

**3. Create Your First Payment**

```javascript
const Sentinel = require('@sentinel-gateway/sdk');

const sentinel = new Sentinel({
  merchantId: process.env.MERCHANT_ID,
  apiKey: process.env.API_KEY,
  apiSecret: process.env.API_SECRET
});

// Create payment
const payment = await sentinel.payments.create({
  amount: 10000, // $100.00 in cents
  currency: 'USD',
  customer: {
    email: 'customer@example.com',
    name: 'John Doe'
  }
});

// Redirect customer
res.redirect(payment.checkout_url);
```

---

## 🔌 Integration Methods

### Method 1: Hosted Checkout (Recommended)

**Pros:**
- ✅ PCI compliant out of the box
- ✅ No card data on your servers
- ✅ 15 minutes to integrate
- ✅ Automatic security updates
- ✅ Mobile optimized

**How It Works:**

```javascript
// 1. Create payment intent
const payment = await sentinel.payments.create({
  amount: 10000,
  currency: 'USD',
  return_url: 'https://yoursite.com/success',
  cancel_url: 'https://yoursite.com/cancel'
});

// 2. Redirect to checkout
res.redirect(payment.checkout_url);

// 3. Handle return
app.get('/success', async (req, res) => {
  const { payment_id } = req.query;
  const payment = await sentinel.payments.retrieve(payment_id);
  
  if (payment.status === 'succeeded') {
    // Fulfill order
  }
});
```

### Method 2: Embedded Checkout

**Pros:**
- ✅ Seamless user experience
- ✅ Still PCI compliant
- ✅ Customizable styling
- ✅ No redirect

**Implementation:**

```html
<!DOCTYPE html>
<html>
<head>
  <script src="https://js.sentinelgate.biz/v1/"></script>
</head>
<body>
  <div id="card-element"></div>
  <button id="pay-button">Pay $100.00</button>

  <script>
    const sentinel = Sentinel('pk_live_xxxxx');
    const cardElement = sentinel.elements.create('card');
    cardElement.mount('#card-element');

    document.getElementById('pay-button').addEventListener('click', async () => {
      const result = await sentinel.confirmPayment({
        amount: 10000,
        currency: 'USD',
        cardElement: cardElement
      });

      if (result.status === 'succeeded') {
        // Show success message
      }
    });
  </script>
</body>
</html>
```

### Method 3: Direct API

**Pros:**
- ✅ Full control
- ✅ Custom UX
- ✅ Advanced features

**Cons:**
- ⚠️ Requires PCI compliance
- ⚠️ More complex

**Example:**

```javascript
// 1. Tokenize card (client-side)
const token = await sentinel.createToken({
  number: '4242424242424242',
  exp_month: 12,
  exp_year: 2027,
  cvc: '123'
});

// 2. Create payment (server-side)
const payment = await sentinel.payments.create({
  amount: 10000,
  currency: 'USD',
  card_token: token.id,
  customer: {
    email: 'customer@example.com'
  }
});
```

---

## 📡 API Reference

### Base URL

```
Production: https://sentinelgate.biz:3002
Sandbox:    https://sandbox.sentinelgate.biz:3002
```

### Authentication

All requests require these headers:

```http
POST /payments HTTP/1.1
Host: sentinelgate.biz:3002
Content-Type: application/json
X-Merchant-ID: merch_abc123
X-API-Key: sk_live_xxxxx
X-API-Secret: xxxxxxxxxxxxx
```

### Create Payment

**POST /payments**

```json
{
  "amount_cents": 10000,
  "currency": "USD",
  "payment_method": "CARD",
  "customer": {
    "email": "customer@example.com",
    "name": "John Doe",
    "phone": "+1234567890"
  },
  "billing_address": {
    "line1": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "US"
  },
  "return_url": "https://yoursite.com/success",
  "cancel_url": "https://yoursite.com/cancel",
  "webhook_url": "https://yoursite.com/webhook",
  "metadata": {
    "order_id": "ORD-12345",
    "notes": "VIP customer"
  }
}
```

**Response (201 Created):**

```json
{
  "success": true,
  "payment_id": "pay_abc123xyz",
  "status": "pending",
  "amount_cents": 10000,
  "currency": "USD",
  "checkout_url": "https://checkout.sentinelgate.biz/pay/abc123",
  "expires_at": "2026-02-12T18:00:00Z",
  "created_at": "2026-02-12T17:00:00Z"
}
```

### Retrieve Payment

**GET /payments/{payment_id}**

```json
{
  "success": true,
  "payment_id": "pay_abc123xyz",
  "status": "succeeded",
  "amount_cents": 10000,
  "currency": "USD",
  "card": {
    "last4": "4242",
    "brand": "visa",
    "exp_month": 12,
    "exp_year": 2027,
    "country": "US",
    "funding": "credit"
  },
  "customer": {
    "email": "customer@example.com",
    "name": "John Doe"
  },
  "billing_address": {
    "line1": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "US"
  },
  "metadata": {
    "order_id": "ORD-12345"
  },
  "created_at": "2026-02-12T17:00:00Z",
  "completed_at": "2026-02-12T17:01:23Z"
}
```

### Refund Payment

**POST /payments/{payment_id}/refund**

```json
{
  "amount_cents": 5000,
  "reason": "customer_request",
  "metadata": {
    "refund_reason": "Product returned - defective"
  }
}
```

**Response:**

```json
{
  "success": true,
  "refund_id": "ref_xyz789",
  "payment_id": "pay_abc123xyz",
  "amount_cents": 5000,
  "status": "succeeded",
  "created_at": "2026-02-12T18:00:00Z"
}
```

### List Payments

**GET /payments?limit=10&status=succeeded**

```json
{
  "success": true,
  "data": [
    {
      "payment_id": "pay_abc123",
      "amount_cents": 10000,
      "status": "succeeded",
      "created_at": "2026-02-12T17:00:00Z"
    }
  ],
  "has_more": true,
  "next_cursor": "cursor_xyz"
}
```

---

## 🔔 Webhooks

### Why Use Webhooks?

Webhooks notify your server when events happen:
- Payment succeeded
- Payment failed  
- Refund processed
- Chargeback received

**Benefits:**
- Real-time updates
- No polling required
- Reliable delivery
- Automatic retries

### Setting Up Webhooks

**1. Create Endpoint**

```javascript
const express = require('express');
const app = express();

app.post('/webhook', 
  express.raw({type: 'application/json'}), 
  async (req, res) => {
    const signature = req.headers['x-sentinel-signature'];
    const payload = req.body;
    
    // Verify signature
    if (!verifySignature(payload, signature)) {
      return res.status(401).send('Invalid signature');
    }
    
    const event = JSON.parse(payload);
    
    // Handle event
    switch (event.type) {
      case 'payment.succeeded':
        await fulfillOrder(event.data);
        break;
      case 'payment.failed':
        await notifyCustomer(event.data);
        break;
      case 'refund.succeeded':
        await updateOrder(event.data);
        break;
    }
    
    res.status(200).send('OK');
  }
);
```

**2. Verify Signatures**

```javascript
const crypto = require('crypto');

function verifySignature(payload, signature) {
  const secret = process.env.WEBHOOK_SECRET;
  const hash = crypto
    .createHmac('sha256', secret)
    .update(payload)
    .digest('hex');
  
  return crypto.timingSafeEqual(
    Buffer.from(signature),
    Buffer.from(hash)
  );
}
```

**3. Register Webhook URL**

Via Dashboard:
1. Settings → Webhooks
2. Add endpoint: `https://yoursite.com/webhook`
3. Copy webhook secret
4. Save

Via API:
```javascript
await sentinel.webhooks.create({
  url: 'https://yoursite.com/webhook',
  events: ['payment.*', 'refund.*']
});
```

### Webhook Events

| Event | Description |
|-------|-------------|
| `payment.created` | Payment intent created |
| `payment.processing` | Payment being processed |
| `payment.succeeded` | Payment completed |
| `payment.failed` | Payment failed |
| `payment.canceled` | Payment canceled |
| `refund.created` | Refund initiated |
| `refund.succeeded` | Refund completed |
| `refund.failed` | Refund failed |
| `chargeback.created` | Chargeback filed |
| `chargeback.won` | You won dispute |
| `chargeback.lost` | You lost dispute |

### Webhook Payload

```json
{
  "id": "evt_abc123",
  "type": "payment.succeeded",
  "created_at": "2026-02-12T17:30:00Z",
  "data": {
    "payment_id": "pay_abc123xyz",
    "amount_cents": 10000,
    "currency": "USD",
    "status": "succeeded",
    "card": {
      "last4": "4242",
      "brand": "visa"
    },
    "customer": {
      "email": "customer@example.com"
    },
    "metadata": {
      "order_id": "ORD-12345"
    }
  }
}
```

### Retry Logic

Webhooks are retried automatically:
- **Retry 1:** After 5 seconds
- **Retry 2:** After 1 minute
- **Retry 3:** After 10 minutes
- **Retry 4:** After 1 hour
- **Retry 5:** After 6 hours

**Your endpoint should:**
- Return 200 status quickly (< 5 seconds)
- Process async if needed
- Be idempotent (handle duplicates)

---

## 🧪 Testing

### Test Mode

Use sandbox credentials:

```bash
MERCHANT_ID=test_merch_abc123
API_KEY=sk_test_xxxxx
API_SECRET=test_xxxxx
```

**Sandbox Environment:**
- No real money processed
- Instant settlement
- All features available
- Reset data anytime

### Test Cards

**Successful Payments:**

```
Visa:       4242 4242 4242 4242
Mastercard: 5555 5555 5555 4444
Amex:       3782 822463 10005

CVV:        Any 3 digits (123)
Expiry:     Any future date (12/27)
Name:       Any name
```

**Test Scenarios:**

```
# 3D Secure Required
Visa: 4000 0027 6000 3184

# Declined - Insufficient Funds
Visa: 4000 0000 0000 9995

# Declined - Stolen Card
Visa: 4000 0000 0000 9979

# Declined - Lost Card
Visa: 4000 0000 0000 9987

# Processing Error
Visa: 4000 0000 0000 0101

# Expired Card
Visa: 4000 0000 0000 0069
```

### Testing Webhooks

**Use webhook.site:**

1. Go to https://webhook.site
2. Copy unique URL
3. Add as webhook endpoint
4. Make test payment
5. See webhook payload in real-time

**Or use ngrok locally:**

```bash
# Install ngrok
npm install -g ngrok

# Start your local server
node server.js

# Create tunnel
ngrok http 3000

# Use ngrok URL as webhook
# https://abc123.ngrok.io/webhook
```

---

## 🔒 Security Best Practices

### 1. Never Expose Secrets

```javascript
// ❌ WRONG - Hard-coded secrets
const apiKey = 'sk_live_abc123';

// ✅ CORRECT - Environment variables
const apiKey = process.env.SENTINEL_API_KEY;
```

**Use .env file:**

```bash
# .env (DO NOT COMMIT TO GIT!)
SENTINEL_MERCHANT_ID=merch_abc123
SENTINEL_API_KEY=sk_live_xxxxx
SENTINEL_API_SECRET=xxxxx
WEBHOOK_SECRET=whsec_xxxxx
```

**Add to .gitignore:**

```bash
# .gitignore
.env
.env.local
.env.production
credentials/
secrets/
```

### 2. Validate All Input

```javascript
// Validate amount
function validatePayment(data) {
  if (!data.amount || data.amount < 100) {
    throw new Error('Minimum amount is $1.00');
  }
  
  if (data.amount > 1000000) {
    throw new Error('Maximum amount is $10,000.00');
  }
  
  // Validate currency
  const validCurrencies = ['USD', 'EUR', 'GBP', 'KES'];
  if (!validCurrencies.includes(data.currency)) {
    throw new Error('Unsupported currency');
  }
  
  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(data.email)) {
    throw new Error('Invalid email');
  }
}
```

### 3. Implement Rate Limiting

```javascript
const rateLimit = require('express-rate-limit');

const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // 100 requests per window
  message: 'Too many requests'
});

app.use('/api/', limiter);
```

### 4. Use HTTPS Only

```javascript
// Force HTTPS
app.use((req, res, next) => {
  if (req.header('x-forwarded-proto') !== 'https') {
    return res.redirect(`https://${req.header('host')}${req.url}`);
  }
  next();
});
```

### 5. Sanitize User Input

```javascript
const validator = require('validator');

function sanitize(input) {
  return validator.escape(input.trim());
}

// Use in routes
app.post('/payment', (req, res) => {
  const email = sanitize(req.body.email);
  const name = sanitize(req.body.name);
  // ...
});
```

### 6. Log Securely

```javascript
// ❌ WRONG - Logs sensitive data
console.log('Payment:', {
  cardNumber: req.body.cardNumber,
  cvv: req.body.cvv
});

// ✅ CORRECT - Logs safely
console.log('Payment:', {
  paymentId: payment.id,
  amount: payment.amount,
  last4: payment.card.last4
});
```

---

## 📚 Code Examples

### Complete Node.js Example

```javascript
const express = require('express');
const Sentinel = require('@sentinel-gateway/sdk');
require('dotenv').config();

const app = express();
app.use(express.json());

const sentinel = new Sentinel({
  merchantId: process.env.SENTINEL_MERCHANT_ID,
  apiKey: process.env.SENTINEL_API_KEY,
  apiSecret: process.env.SENTINEL_API_SECRET
});

// Create checkout
app.post('/api/checkout', async (req, res) => {
  try {
    const { amount, currency, customer } = req.body;
    
    const payment = await sentinel.payments.create({
      amount_cents: amount * 100,
      currency: currency,
      customer: customer,
      return_url: `${req.protocol}://${req.get('host')}/success`,
      cancel_url: `${req.protocol}://${req.get('host')}/cancel`,
      webhook_url: `${req.protocol}://${req.get('host')}/webhook`
    });
    
    res.json({
      success: true,
      checkout_url: payment.checkout_url
    });
  } catch (error) {
    res.status(400).json({
      success: false,
      error: error.message
    });
  }
});

// Success page
app.get('/success', async (req, res) => {
  const { payment_id } = req.query;
  
  const payment = await sentinel.payments.retrieve(payment_id);
  
  if (payment.status === 'succeeded') {
    res.send(`
      <h1>Payment Successful!</h1>
      <p>Transaction ID: ${payment.payment_id}</p>
      <p>Amount: $${payment.amount_cents / 100}</p>
    `);
  }
});

// Webhook handler
app.post('/webhook', express.raw({type: 'application/json'}), (req, res) => {
  const signature = req.headers['x-sentinel-signature'];
  
  if (!sentinel.webhooks.verify(req.body, signature)) {
    return res.status(401).send('Invalid signature');
  }
  
  const event = JSON.parse(req.body);
  
  switch (event.type) {
    case 'payment.succeeded':
      console.log('Payment succeeded:', event.data.payment_id);
      // Fulfill order
      break;
    case 'payment.failed':
      console.log('Payment failed:', event.data.payment_id);
      // Notify customer
      break;
  }
  
  res.status(200).send('OK');
});

app.listen(3000, () => {
  console.log('Server running on port 3000');
});
```

### Complete Python Example

```python
from flask import Flask, request, redirect, jsonify
import os
from sentinel_gateway import Sentinel

app = Flask(__name__)

sentinel = Sentinel(
    merchant_id=os.getenv('SENTINEL_MERCHANT_ID'),
    api_key=os.getenv('SENTINEL_API_KEY'),
    api_secret=os.getenv('SENTINEL_API_SECRET')
)

@app.route('/api/checkout', methods=['POST'])
def checkout():
    try:
        data = request.json
        
        payment = sentinel.payments.create(
            amount_cents=int(data['amount'] * 100),
            currency=data['currency'],
            customer=data['customer'],
            return_url=f"{request.url_root}success",
            cancel_url=f"{request.url_root}cancel",
            webhook_url=f"{request.url_root}webhook"
        )
        
        return jsonify({
            'success': True,
            'checkout_url': payment['checkout_url']
        })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 400

@app.route('/success')
def success():
    payment_id = request.args.get('payment_id')
    payment = sentinel.payments.retrieve(payment_id)
    
    if payment['status'] == 'succeeded':
        return f"""
            <h1>Payment Successful!</h1>
            <p>Transaction ID: {payment['payment_id']}</p>
            <p>Amount: ${payment['amount_cents'] / 100}</p>
        """

@app.route('/webhook', methods=['POST'])
def webhook():
    signature = request.headers.get('X-Sentinel-Signature')
    
    if not sentinel.webhooks.verify(request.data, signature):
        return 'Invalid signature', 401
    
    event = request.json
    
    if event['type'] == 'payment.succeeded':
        print(f"Payment succeeded: {event['data']['payment_id']}")
        # Fulfill order
    elif event['type'] == 'payment.failed':
        print(f"Payment failed: {event['data']['payment_id']}")
        # Notify customer
    
    return 'OK', 200

if __name__ == '__main__':
    app.run(port=3000)
```

### Complete PHP Example

```php
<?php
require 'vendor/autoload.php';

use SentinelGateway\Sentinel;

$sentinel = new Sentinel([
    'merchant_id' => getenv('SENTINEL_MERCHANT_ID'),
    'api_key' => getenv('SENTINEL_API_KEY'),
    'api_secret' => getenv('SENTINEL_API_SECRET')
]);

// Create checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/checkout') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $payment = $sentinel->payments->create([
            'amount_cents' => $data['amount'] * 100,
            'currency' => $data['currency'],
            'customer' => $data['customer'],
            'return_url' => 'https://yoursite.com/success',
            'cancel_url' => 'https://yoursite.com/cancel',
            'webhook_url' => 'https://yoursite.com/webhook'
        ]);
        
        echo json_encode([
            'success' => true,
            'checkout_url' => $payment['checkout_url']
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Success page
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/success') {
    $payment_id = $_GET['payment_id'];
    $payment = $sentinel->payments->retrieve($payment_id);
    
    if ($payment['status'] === 'succeeded') {
        echo "
            <h1>Payment Successful!</h1>
            <p>Transaction ID: {$payment['payment_id']}</p>
            <p>Amount: $" . ($payment['amount_cents'] / 100) . "</p>
        ";
    }
    exit;
}

// Webhook handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/webhook') {
    $signature = $_SERVER['HTTP_X_SENTINEL_SIGNATURE'];
    $payload = file_get_contents('php://input');
    
    if (!$sentinel->webhooks->verify($payload, $signature)) {
        http_response_code(401);
        echo 'Invalid signature';
        exit;
    }
    
    $event = json_decode($payload, true);
    
    switch ($event['type']) {
        case 'payment.succeeded':
            error_log("Payment succeeded: " . $event['data']['payment_id']);
            // Fulfill order
            break;
        case 'payment.failed':
            error_log("Payment failed: " . $event['data']['payment_id']);
            // Notify customer
            break;
    }
    
    echo 'OK';
    exit;
}
```

---

## 🆘 Support & Resources

### Developer Support

**Email:** dev-support@sentinelgate.biz  
**Documentation:** https://docs.sentinelgate.biz  
**API Status:** https://status.sentinelgate.biz  
**Response Time:** < 4 hours

### Resources

- **API Reference:** Complete API documentation
- **SDK Documentation:** Language-specific guides
- **Integration Examples:** Sample apps and code
- **Video Tutorials:** Step-by-step guides
- **Community Forum:** Ask questions, share solutions

### Getting Help

1. **Check Documentation First**
   - Most questions answered in docs
   - Search functionality available

2. **Test in Sandbox**
   - Replicate issue in test mode
   - Helps us debug faster

3. **Contact Support**
   - Include merchant ID
   - Share code snippets
   - Describe expected vs actual behavior
   - Include error messages

---

## 📖 Glossary

### For Everyone

**3D Secure (3DS):** Extra security step where you verify payment with your bank (OTP, app approval, etc.)

**Authorization:** When merchant checks if you have enough money (holds the amount temporarily)

**Card Network:** Companies that process card payments (Visa, Mastercard, etc.)

**Chargeback:** When customer disputes a charge with their bank

**CVV/CVC:** 3-digit security code on back of card (4 digits for Amex on front)

**Issuer:** Your bank (the one that issued your card)

**Merchant:** Business selling goods/services

**OTP:** One-Time Password sent by your bank

**PCI DSS:** Security standards for handling card data

**Refund:** Money returned to customer

**Settlement:** When money moves from customer's bank to merchant's bank

**Token:** Secure reference to card details (instead of storing full number)

### For Developers

**API:** Application Programming Interface (how systems talk to each other)

**Endpoint:** Specific URL for API operation (e.g., /payments)

**Idempotency:** Same request produces same result (safe to retry)

**Sandbox:** Test environment with fake money

**SDK:** Software Development Kit (pre-built code library)

**Webhook:** Server notification when event happens

---

## 📜 Changelog

### Version 2.0 (February 2026)
- ✅ Added multi-audience documentation
- ✅ Customer payment guide
- ✅ Merchant business guide  
- ✅ Developer technical guide
- ✅ Complete code examples
- ✅ Security best practices
- ✅ Troubleshooting guides

### Version 1.0 (January 2026)
- Initial release
- Developer-focused documentation
- Basic integration guide

---

## 📄 Legal & Compliance

### Terms of Service

By using Sentinel Payment Gateway, you agree to:
- Follow PCI DSS requirements
- Maintain secure integration
- Protect customer data
- Report security issues promptly

Full terms: https://sentinelgate.biz/terms

### Privacy Policy

We protect customer data:
- Encrypted storage
- Minimal data collection
- GDPR compliant
- Right to deletion

Full policy: https://sentinelgate.biz/privacy

### Data Protection

**Customer Data:**
- Encrypted in transit (TLS 1.3)
- Encrypted at rest (AES-256)
- Tokenized card numbers
- Regular security audits

**Your Responsibilities:**
- Secure credential storage
- HTTPS on all pages
- Regular security updates
- Prompt incident reporting

---

**End of Complete Documentation**

*For the latest updates, visit: https://docs.sentinelgate.biz*

---

## Quick Links

- **For Customers:** [How to Pay Safely](#for-customers)
- **For Merchants:** [Business Setup](#for-merchants)
- **For Developers:** [Technical Integration](#for-developers)
- **Support:** support@sentinelgate.biz
- **Emergency:** support@sentinelgate.biz

---

© 2026 Sentinel Payment Gateway. All rights reserved.
