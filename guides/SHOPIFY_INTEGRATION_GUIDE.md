# Shopify Integration Guide
**SentinelGate Payment Gateway for Shopify**

Complete guide for integrating SentinelGate payments into your Shopify store.

---

## 📚 Table of Contents

- [For Store Owners](#for-store-owners)
- [For Developers](#for-developers)
- [Installation](#installation)
- [Configuration](#configuration)
- [Testing](#testing)
- [Going Live](#going-live)
- [Troubleshooting](#troubleshooting)

---

## 🛍️ For Store Owners

### What is SentinelGate for Shopify?

SentinelGate is a payment gateway plugin for Shopify that allows you to accept:
- **Mobile Money** (M-Pesa, MTN Momo, Airtel Money, etc.)
- **Credit/Debit Cards** (Visa, Mastercard, Amex)
- **Bank Transfers**

**Perfect for African merchants** wanting to accept local payment methods.

### Why Use SentinelGate on Shopify?

✅ **Accept Local Payments**
- M-Pesa and other mobile money (most popular in Africa)
- Local cards with lower fees
- Bank transfers for large orders

✅ **Easy Setup**
- Install plugin in minutes
- No coding required
- Works with any Shopify theme

✅ **Better Conversion**
- 80% of African customers prefer mobile money
- Familiar payment methods = more sales
- Lower cart abandonment

✅ **Competitive Pricing**
- Lower fees than international gateways
- No setup costs
- No monthly fees

### Pricing

| Transaction Type | Fee | Settlement Time |
|-----------------|-----|-----------------|
| Mobile Money | 2.5% | 24 hours |
| Cards | 3.5% | 24-48 hours |
| Bank Transfer | 1.5% | 24-72 hours |

**No hidden fees. No monthly charges. No setup costs.**

---

## 💻 For Developers

### Prerequisites

Before you start:

- ✅ Shopify store (any plan)
- ✅ SentinelGate merchant account
- ✅ API credentials (API Key, Secret, Webhook Secret)
- ✅ Admin access to Shopify

### Architecture Overview
```
Customer Checkout → Shopify → SentinelGate Plugin → SentinelGate API
                                                            ↓
                                                     Mobile Network/Bank
                                                            ↓
                                                   Webhook Callback
                                                            ↓
                                              Shopify Order Updated
```

### Installation Methods

#### Method 1: Shopify App Store (Easiest)

1. **Search in Shopify App Store:**
   - Go to: https://apps.shopify.com
   - Search: "SentinelGate Payment Gateway"
   - Click "Add app"

2. **Install:**
   - Review permissions
   - Click "Install app"
   - Redirected to SentinelGate configuration

3. **Configure:**
   - Enter API credentials
   - Select payment methods
   - Click "Save"

**Time to complete:** 5 minutes

#### Method 2: Manual Installation (For Developers)

**Step 1: Download Plugin**
```bash
# Clone from GitHub
git clone https://github.com/sentinelgate/shopify-plugin.git
cd shopify-plugin

# Or download ZIP
wget https://github.com/sentinelgate/shopify-plugin/archive/main.zip
unzip main.zip
```

**Step 2: Install in Shopify**
```bash
# Using Shopify CLI
shopify login
shopify app deploy

# Or manually upload to Shopify admin
# Admin → Apps → Develop apps → Create app
```

**Step 3: Configure Credentials**

In Shopify Admin:
1. Go to: **Settings → Payments**
2. Scroll to: **Alternative payment methods**
3. Click: **SentinelGate**
4. Enter:
   - API Key: `sk_live_xxx`
   - API Secret: `secret_xxx`
   - Webhook Secret: `whsec_xxx`

**Step 4: Enable Payment Methods**

Select which payment methods to offer:
- ☑️ M-Pesa (Kenya)
- ☑️ MTN Mobile Money (Ghana, Nigeria, Uganda, etc.)
- ☑️ Airtel Money
- ☑️ Credit/Debit Cards
- ☑️ Bank Transfer

**Step 5: Test Mode**

Enable test mode for testing:
- ☑️ Enable test mode
- Use test credentials
- Test with dummy data

---

## ⚙️ Configuration

### Basic Settings

**1. API Credentials:**
```yaml
# In Shopify Admin → Apps → SentinelGate → Settings

Environment: Production
API Key: sk_live_your_api_key_here
API Secret: secret_your_api_secret_here
Webhook Secret: whsec_your_webhook_secret_here
```

**2. Payment Methods:**

Enable the payment methods you want to offer:
```yaml
Mobile Money:
  - ☑️ M-Pesa (Kenya)
  - ☑️ MTN Mobile Money (Multi-country)
  - ☑️ Airtel Money
  - ☑️ Orange Money
  - ☑️ Hubtel (Ghana - all networks)

Cards:
  - ☑️ Visa
  - ☑️ Mastercard
  - ☑️ American Express

Other:
  - ☑️ Bank Transfer
```

**3. Currency Settings:**
```yaml
Primary Currency: KES (Kenya Shillings)
Accept Multi-Currency: Yes
Supported: KES, GHS, NGN, TZS, UGX, ZAR
```

**4. Order Settings:**
```yaml
Auto-fulfill orders: No (wait for payment confirmation)
Order prefix: SENT-
Email notifications: Yes
```

### Advanced Settings

**1. Webhook Configuration:**
```yaml
Webhook URL: https://your-store.myshopify.com/apps/sentinelgate/webhook
Webhook Events:
  - payment.completed
  - payment.failed
  - payment.cancelled
Retry Policy: Exponential backoff (3 attempts)
```

**2. Payment Flow:**
```yaml
Payment Page: Redirect to SentinelGate hosted page
Return URL: https://your-store.myshopify.com/thank-you
Cancel URL: https://your-store.myshopify.com/checkout
Timeout: 5 minutes
```

**3. Limits:**
```yaml
Minimum Order Amount: 100 KES
Maximum Order Amount: 500,000 KES
Daily Transaction Limit: 5,000,000 KES
```

**4. Branding:**

Customize the payment page:
```yaml
Logo: Upload your store logo (PNG, max 500KB)
Primary Color: #1A73E8
Button Text: "Pay Now"
Show Store Name: Yes
```

---

## 🧪 Testing

### Test Mode Setup

**1. Enable Test Mode:**

Shopify Admin → Apps → SentinelGate → Settings:
- ☑️ Enable test mode
- Save changes

**2. Use Test Credentials:**
```yaml
Test API Key: sk_test_xxxxxxxxxxxx
Test API Secret: secret_test_xxxxxxxxxxxx
Test Webhook Secret: whsec_test_xxxxxxxxxxxx
```

**3. Test Phone Numbers:**

| Number | Result | Use For |
|--------|--------|---------|
| `254712345678` | ✅ Success | Successful payment |
| `254700000000` | ❌ Insufficient funds | Failed payment |
| `254711111111` | ⏱️  Timeout | Timeout scenario |
| `254722222222` | 🚫 Cancelled | User cancellation |

**4. Test Cards:**

| Card Number | Result | Use For |
|-------------|--------|---------|
| `4111111111111111` | ✅ Success | Successful card payment |
| `4000000000000002` | ❌ Declined | Card declined |
| `4000000000000119` | ⏱️  3D Secure | 3D Secure authentication |

CVV: Any 3 digits  
Expiry: Any future date

### Testing Checklist

**Mobile Money Flow:**

- [ ] Add product to cart
- [ ] Proceed to checkout
- [ ] Enter shipping details
- [ ] Select "M-Pesa" payment method
- [ ] Enter test phone number
- [ ] Verify STK push simulation
- [ ] Confirm payment
- [ ] Check order status updated to "Paid"
- [ ] Receive order confirmation email

**Card Payment Flow:**

- [ ] Add product to cart
- [ ] Proceed to checkout
- [ ] Select "Credit Card" payment method
- [ ] Enter test card details
- [ ] Complete 3D Secure (if prompted)
- [ ] Verify payment confirmation
- [ ] Check order status

**Webhook Testing:**

- [ ] Payment completed webhook received
- [ ] Order status updated automatically
- [ ] Customer email sent
- [ ] Failed payment handled correctly
- [ ] Cancelled payment handled

**Error Scenarios:**

- [ ] Invalid phone number shows error
- [ ] Insufficient funds shows proper message
- [ ] Network timeout handled gracefully
- [ ] Duplicate payment prevented

---

## 🚀 Going Live

### Pre-Launch Checklist

**1. Configuration:**
- [ ] Test mode disabled
- [ ] Production API credentials entered
- [ ] All payment methods tested
- [ ] Webhook URL verified
- [ ] SSL certificate active on store

**2. Legal & Compliance:**
- [ ] Terms of service updated
- [ ] Privacy policy includes payment info
- [ ] Refund policy clearly stated
- [ ] Business registration verified

**3. Customer Experience:**
- [ ] Payment methods clearly displayed
- [ ] Mobile-optimized checkout
- [ ] Clear payment instructions
- [ ] Support contact visible

**4. Technical:**
- [ ] Error logging enabled
- [ ] Transaction monitoring active
- [ ] Backup payment method configured
- [ ] Customer support trained

### Launch Steps

**Step 1: Switch to Production**
```yaml
# Shopify Admin → Apps → SentinelGate → Settings
Environment: Production
☐ Enable test mode  # Uncheck this
API Key: sk_live_prod_xxxxxxxxxxxx
API Secret: secret_prod_xxxxxxxxxxxx
Webhook Secret: whsec_prod_xxxxxxxxxxxx
```

**Step 2: Test with Real Payment**

Make a small test purchase:
- Use real phone number
- Pay minimal amount (e.g., 10 KES)
- Verify entire flow works
- Check money received in settlement account

**Step 3: Monitor First Transactions**

Watch closely for first 24 hours:
- Check all payments complete successfully
- Monitor error rates
- Respond quickly to customer issues
- Verify settlements

**Step 4: Enable for All Customers**

Once confident:
- Make SentinelGate the primary payment method
- Promote local payment options
- Update marketing materials

### Post-Launch Monitoring

**Daily:**
- Check failed payment rate (should be <5%)
- Review customer support tickets
- Verify settlements received

**Weekly:**
- Analyze payment method usage
- Review conversion rates
- Optimize payment flow if needed

**Monthly:**
- Review transaction fees
- Check for fraud patterns
- Update documentation

---

## 🎨 Customization

### Customize Checkout Experience

**1. Custom Payment Icons:**

Add custom icons for payment methods:
```liquid
<!-- In your theme's checkout.liquid -->
{% if payment_method == 'sentinelgate' %}
  <div class="payment-icons">
    <img src="{{ 'mpesa-icon.png' | asset_url }}" alt="M-Pesa">
    <img src="{{ 'card-icon.png' | asset_url }}" alt="Cards">
  </div>
{% endif %}
```

**2. Custom Payment Instructions:**
```liquid
<!-- Add payment instructions -->
{% if payment_method == 'sentinelgate_mpesa' %}
  <div class="payment-help">
    <h4>How to pay with M-Pesa:</h4>
    <ol>
      <li>Enter your M-Pesa phone number</li>
      <li>Check your phone for STK push</li>
      <li>Enter your M-Pesa PIN</li>
      <li>Payment confirmed instantly!</li>
    </ol>
  </div>
{% endif %}
```

**3. Custom Styling:**
```css
/* In your theme's CSS */
.sentinelgate-payment-button {
  background-color: #00A651; /* M-Pesa green */
  color: white;
  padding: 15px 30px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
}

.sentinelgate-payment-button:hover {
  background-color: #008C44;
}
```

### Multi-Language Support

Configure payment method names in different languages:
```yaml
# In plugin settings
Languages:
  English:
    mpesa: "Pay with M-Pesa"
    card: "Pay with Card"
  Swahili:
    mpesa: "Lipa na M-Pesa"
    card: "Lipa na Kadi"
  French:
    mpesa: "Payer avec M-Pesa"
    card: "Payer par Carte"
```

---

## 📊 Analytics & Reporting

### Transaction Dashboard

View in Shopify Admin → Apps → SentinelGate → Dashboard:

**Today's Overview:**
```
Total Transactions: 45
Successful: 42 (93.3%)
Failed: 3 (6.7%)
Total Amount: KES 125,450
```

**Payment Method Breakdown:**
```
M-Pesa: 30 (66.7%)
Cards: 10 (22.2%)
Bank Transfer: 5 (11.1%)
```

**Average Transaction:**
```
Amount: KES 2,788
Processing Time: 25 seconds
Success Rate: 93.3%
```

### Export Reports

Export transaction data:
```bash
# CSV Export
# Shopify Admin → Apps → SentinelGate → Reports → Export
# Date Range: Last 30 days
# Format: CSV
# Fields: Order ID, Amount, Status, Payment Method, Date
```

**Sample CSV:**
```csv
Order ID,Amount,Currency,Status,Payment Method,Customer Phone,Date
#1001,1500,KES,SUCCESS,MPESA_SAFARICOM,254712345678,2026-02-07
#1002,3200,KES,SUCCESS,CARD,N/A,2026-02-07
#1003,800,KES,FAILED,MPESA_SAFARICOM,254700000000,2026-02-07
```

### Reconciliation

Daily reconciliation process:

1. Export SentinelGate transactions
2. Match with Shopify orders
3. Verify settlement amounts
4. Flag discrepancies
5. Contact support for unmatched items

---

## 🛠️ Troubleshooting

### Common Issues

#### 1. "Payment method not showing at checkout"

**Causes:**
- Plugin not activated
- Test mode enabled
- Currency not supported
- Order amount below minimum

**Solutions:**
```yaml
Check:
  - Apps → SentinelGate → Settings → Status: Active
  - Test mode: Disabled (for production)
  - Store currency: In supported list
  - Order minimum: >= 100 KES
```

#### 2. "Webhook not received"

**Causes:**
- Incorrect webhook URL
- SSL certificate issues
- Server firewall blocking

**Solutions:**
```bash
# Verify webhook URL
# Should be: https://your-store.myshopify.com/apps/sentinelgate/webhook

# Test webhook endpoint
curl -X POST https://your-store.myshopify.com/apps/sentinelgate/webhook \
  -H "Content-Type: application/json" \
  -d '{"test": true}'

# Check SSL
curl -I https://your-store.myshopify.com
# Should return: HTTP/2 200
```

#### 3. "Payment stuck in pending"

**Causes:**
- Customer didn't complete payment
- Network timeout
- Webhook not processed

**Actions:**
```yaml
1. Check payment status in SentinelGate dashboard
2. Contact customer to verify payment
3. If paid but not updated:
   - Manually verify with SentinelGate
   - Update order status in Shopify
4. If not paid:
   - Cancel order
   - Send payment retry link
```

#### 4. "Customer charged but order not created"

**CRITICAL - Immediate Actions:**
```yaml
1. Do NOT refund automatically
2. Check SentinelGate dashboard for payment
3. Verify payment ID exists
4. Check Shopify order logs
5. Contact SentinelGate support with:
   - Payment ID
   - Customer email/phone
   - Amount and date
6. Support will either:
   - Match payment to order
   - Initiate refund if duplicate
```

#### 5. "Multiple payment attempts charged"

**Prevention:**
```javascript
// Plugin already implements this
// But verify in settings:
Settings → Advanced → Duplicate Prevention: Enabled
Idempotency Window: 24 hours
```

**If it happens:**
1. Verify both charges in SentinelGate dashboard
2. Refund duplicate via SentinelGate (not Shopify)
3. Contact customer with explanation
4. Report to SentinelGate support

### Debug Mode

Enable debug logging:
```yaml
# Shopify Admin → Apps → SentinelGate → Settings → Advanced
Debug Mode: Enabled
Log Level: Verbose
Log Retention: 7 days
```

**View logs:**
```
Admin → Apps → SentinelGate → Logs
```

**Log sample:**
```
2026-02-07 10:30:15 INFO Payment created: pay_clx123
2026-02-07 10:30:16 INFO Redirecting to SentinelGate
2026-02-07 10:30:45 INFO Webhook received: payment.completed
2026-02-07 10:30:46 INFO Order #1001 marked as paid
2026-02-07 10:30:47 INFO Email notification sent
```

### Getting Support

**Self-Service:**
1. Check this documentation
2. Search Shopify Community
3. Review plugin changelog

**Contact Support:**

**Email:** shopify-support@sentinelgate.com

**Include:**
- Shopify store URL
- SentinelGate merchant ID
- Order number (if applicable)
- Payment ID (if applicable)
- Screenshots of error
- Steps to reproduce

**Response Time:**
- Critical (payments down): 1 hour
- High (affecting customers): 4 hours
- Normal (questions): 24 hours

**24/7 Emergency Hotline:**
+254-XXX-XXXXXX (payment processing issues only)

---

## 🔒 Security Best Practices

### Protecting API Credentials

✅ **DO:**
- Store credentials in Shopify's secure settings
- Use different credentials for test/production
- Rotate credentials every 90 days
- Limit staff access to credentials

❌ **DON'T:**
- Share credentials via email
- Store in theme code
- Commit to version control
- Use same credentials across stores

### PCI Compliance

The plugin is **PCI DSS compliant** when:
- Using SentinelGate's hosted payment page (default)
- Not storing card details in Shopify
- SSL enabled on store

**Your responsibilities:**
- Keep Shopify up to date
- Use strong admin passwords
- Enable 2FA on Shopify account
- Review access logs regularly

### Fraud Prevention

Built-in fraud detection:
```yaml
# Automatic checks
- Velocity checks (multiple attempts)
- Amount validation
- Geolocation verification
- Device fingerprinting

# Manual review triggers
- Orders over 100,000 KES
- Multiple failed attempts
- Mismatched shipping/billing
- High-risk countries
```

**Custom rules:**
```yaml
Settings → Fraud Prevention:
  Block orders from: [Country codes]
  Require verification for: Amount > 50,000 KES
  Max failed attempts: 3
  Cooldown period: 1 hour
```

---

## 📱 Mobile Optimization

### Mobile Checkout

Plugin automatically optimizes for mobile:

**Features:**
- ✅ Responsive design
- ✅ Touch-friendly buttons
- ✅ Auto-focus on phone input
- ✅ Native keyboard for numbers
- ✅ M-Pesa STK push integration

**Mobile-specific settings:**
```yaml
Mobile Optimization:
  Simplified checkout: Yes
  One-tap M-Pesa: Yes
  Remember payment method: Yes
  Biometric confirmation: Yes (where available)
```

### Progressive Web App (PWA)

For Shopify Plus stores with PWA:
```javascript
// Service worker integration
// Auto-included in plugin

// Supports:
- Offline order queueing
- Background webhook processing
- Push notifications for payment status
```

---

## 🌍 Multi-Store Setup

### Managing Multiple Stores

If you have multiple Shopify stores:

**Option 1: Single SentinelGate Account**
```yaml
# Use same merchant account
# Different API keys per store

Store A (Kenya):
  API Key: sk_live_kenya_xxx
  Currency: KES
  Methods: M-Pesa, Cards

Store B (Ghana):
  API Key: sk_live_ghana_xxx
  Currency: GHS
  Methods: MTN Momo, Cards
```

**Option 2: Separate Accounts**
```yaml
# Different merchant accounts
# For different business entities

Benefits:
  - Separate settlements
  - Independent reporting
  - Different branding
```

### Multi-Currency

Handle multiple currencies:
```yaml
Enable Shopify Payments Multi-Currency
Configure exchange rates
SentinelGate auto-converts to:
  - Local currency for mobile money
  - Settlement currency for your account
```

---

## 🎓 Best Practices

### Optimize Conversion

**1. Clear Payment Options:**
```liquid
<!-- Show popular local methods first -->
<div class="payment-methods">
  <button class="payment-method featured">
    <img src="mpesa.png"> M-Pesa (Most Popular)
  </button>
  <button class="payment-method">
    <img src="card.png"> Credit/Debit Card
  </button>
</div>
```

**2. Build Trust:**
- Display security badges
- Show "Powered by SentinelGate"
- Add customer testimonials
- Clear refund policy

**3. Reduce Friction:**
- Auto-detect payment method from phone number
- Remember previous payment method
- Enable one-click checkout
- Minimize form fields

**4. Mobile-First:**
- Large touch targets (min 44x44px)
- Simplified checkout on mobile
- Fast page load (<3 seconds)
- Sticky payment button

### Customer Communication

**Email Templates:**
```html
<!-- Payment confirmation -->
Subject: Payment received - Order #{{ order.number }}

Hi {{ customer.name }},

We've received your payment of {{ order.total }} via M-Pesa.

Order details:
- Order number: #{{ order.number }}
- Amount paid: {{ order.total }}
- Payment method: M-Pesa
- M-Pesa reference: {{ transaction.reference }}

Your order is being processed and will ship within 24 hours.

Track your order: {{ order.tracking_url }}

Thank you for shopping with us!
```

**SMS Notifications:**
```
Payment received! Your order #1001 for KES 1,500 is confirmed. 
Track: https://store.com/track/1001
```

---

## 📈 Growth & Scaling

### Transaction Volume

As you grow:

| Monthly Volume | Fee Tier | Settlement |
|---------------|----------|------------|
| 0 - 1M KES | 2.5% | T+1 |
| 1M - 5M KES | 2.3% | T+1 |
| 5M - 20M KES | 2.0% | Same day |
| 20M+ KES | Custom | Real-time |

**Contact sales for enterprise pricing**

### Advanced Features

Available for high-volume merchants:

- **Recurring Billing** - Subscriptions with auto-retry
- **Split Payments** - Multi-vendor payouts
- **Dynamic Routing** - Optimize success rates
- **Dedicated Support** - Priority response
- **Custom Integration** - API customizations

---

## 🔄 Updates & Maintenance

### Plugin Updates

**Automatic Updates:**
- Security patches: Auto-applied
- Bug fixes: Auto-applied within 24 hours
- Feature updates: Opt-in via admin panel

**Manual Updates:**
```bash
# If you installed manually
cd shopify-plugin
git pull origin main
shopify app deploy
```

**Update Notifications:**
- Email alerts for critical updates
- In-app notifications for features
- Changelog: https://docs.sentinelgate.com/changelog

### Backward Compatibility

- Minimum Shopify version: 2.0
- Support window: 24 months
- Migration guides provided for breaking changes

---

## 📞 Support & Resources

### Documentation
- **API Docs:** https://docs.sentinelgate.com/api
- **Video Tutorials:** https://youtube.com/sentinelgate
- **Developer Portal:** https://developers.sentinelgate.com

### Community
- **Slack:** sentinelgate.slack.com
- **Forum:** community.sentinelgate.com
- **GitHub:** github.com/sentinelgate

### Contact
- **General:** support@sentinelgate.com
- **Shopify-specific:** shopify-support@sentinelgate.com
- **Sales:** sales@sentinelgate.com
- **Emergency:** +254-XXX-XXXXXX (24/7)

---

**Last Updated:** February 8, 2026  
**Plugin Version:** 2.1.0  
**Shopify Compatibility:** 2.0+  

**© 2026 SentinelGate Payment Services. All rights reserved.**

