# Google Pay

Accept payments from customers using Google Pay on Chrome, Android devices, and any browser with a saved Google account. Tokenized transactions with biometric or device authentication.

---

## Overview

| Field | Value |
|-------|-------|
| Rail Code | `GOOGLE_PAY` |
| Currency | USD, EUR, GBP, CAD, GHS, KES + 80 more |
| Settlement | Real-time authorization / T+1 settlement |
| Min Amount | $0.01 |
| Max Amount | No fixed limit |
| Availability | 24/7 |
| Supported | Chrome, Android, any browser with Google account |

## How It Works

```
Customer → Taps "Pay with Google Pay" → Selects Card → Authenticates
→ Google Returns Encrypted Token → Token Sent to SentinelGate
→ SentinelGate Decrypts & Charges → Callback → Order Confirmed
```

1. Merchant displays the Google Pay button on checkout
2. Customer taps and selects a payment method from their Google account
3. Customer authenticates (fingerprint, PIN, or face unlock on mobile; password on web)
4. Google generates an encrypted payment token (DPAN or FPAN + cryptogram)
5. Token is sent to SentinelGate via `/v1/wallet/google-pay/charge`
6. SentinelGate decrypts the token and processes the charge
7. Instant authorization; settlement follows standard card timelines

---

## Prerequisites

1. **Google Pay & Wallet Console** — Register at pay.google.com/business/console
2. **Google Merchant ID** — Issued after business profile review
3. **Gateway merchant ID** — Your SentinelGate merchant ID
4. **HTTPS** — Google Pay only works on HTTPS pages

No certificates or domain verification required (unlike Apple Pay).

---

## Integration

### Web (JavaScript — Google Pay API)

#### 1. Load the Library & Create Button

```html
<!DOCTYPE html>
<html>
<head>
  <script src="https://pay.google.com/gp/p/js/pay.js"></script>
</head>
<body>
  <div id="google-pay-button-container"></div>

  <script>
    const baseRequest = { apiVersion: 2, apiVersionMinor: 0 };

    const tokenizationSpec = {
      type: 'PAYMENT_GATEWAY',
      parameters: {
        gateway: 'sentinelgate',
        gatewayMerchantId: 'your-merchant-id'  // Your SentinelGate merchant ID
      }
    };

    const allowedCardNetworks = ['VISA', 'MASTERCARD', 'AMEX'];
    const allowedCardAuthMethods = ['PAN_ONLY', 'CRYPTOGRAM_3DS'];

    const baseCardPaymentMethod = {
      type: 'CARD',
      parameters: {
        allowedAuthMethods: allowedCardAuthMethods,
        allowedCardNetworks: allowedCardNetworks
      }
    };

    const cardPaymentMethod = {
      ...baseCardPaymentMethod,
      tokenizationSpecification: tokenizationSpec
    };

    let paymentsClient;

    function getGooglePaymentsClient() {
      if (!paymentsClient) {
        paymentsClient = new google.payments.api.PaymentsClient({
          environment: 'PRODUCTION'  // Use 'TEST' for sandbox
        });
      }
      return paymentsClient;
    }

    async function onGooglePayLoaded() {
      const client = getGooglePaymentsClient();

      const response = await client.isReadyToPay({
        ...baseRequest,
        allowedPaymentMethods: [baseCardPaymentMethod]
      });

      if (response.result) {
        const button = client.createButton({
          onClick: onGooglePayClicked,
          buttonColor: 'black',
          buttonType: 'buy',
          buttonSizeMode: 'fill'
        });
        document.getElementById('google-pay-button-container').appendChild(button);
      }
    }

    async function onGooglePayClicked() {
      const paymentDataRequest = {
        ...baseRequest,
        allowedPaymentMethods: [cardPaymentMethod],
        transactionInfo: {
          totalPriceStatus: 'FINAL',
          totalPrice: '49.99',
          currencyCode: 'USD',
          countryCode: 'US'
        },
        merchantInfo: {
          merchantName: 'Your Store Name',
          merchantId: 'BCR2DN4TXXXXXXX'  // From Google Pay Console
        }
      };

      try {
        const paymentData = await getGooglePaymentsClient().loadPaymentData(paymentDataRequest);
        await processGooglePayToken(paymentData);
      } catch (err) {
        console.error('Google Pay error:', err);
      }
    }

    async function processGooglePayToken(paymentData) {
      const token = paymentData.paymentMethodData.tokenizationData.token;

      const response = await fetch('/api/google-pay/charge', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          payment_token: JSON.parse(token),
          amount: '49.99',
          currency: 'USD',
          order_id: 'ORD-001',
          card_network: paymentData.paymentMethodData.info.cardNetwork,
          card_last4: paymentData.paymentMethodData.info.cardDetails
        })
      });

      const result = await response.json();
      if (result.ok) {
        window.location.href = '/order/success';
      } else {
        alert('Payment failed: ' + (result.error || 'Unknown error'));
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', onGooglePayLoaded);
  </script>
</body>
</html>
```

#### 2. Server-Side: Charge the Token

```bash
curl -X POST https://sentinelgate.biz/v1/wallet/google-pay/charge \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "merchant_id": "your-merchant-id",
    "amount_cents": 4999,
    "currency": "USD",
    "email": "customer@example.com",
    "reference": "gpay_ref_001",
    "callback_url": "https://yoursite.com/webhooks/google-pay",
    "payment_token": {
      "signature": "MEYCIQDf...",
      "intermediateSigningKey": {
        "signedKey": "{\"keyValue\":\"MFkwEw...\",\"keyExpiration\":\"1678886400000\"}",
        "signatures": ["MEUCIQC..."]
      },
      "protocolVersion": "ECv2",
      "signedMessage": "{\"encryptedMessage\":\"BASE64...\",\"ephemeralPublicKey\":\"BASE64...\",\"tag\":\"BASE64...\"}"
    },
    "card_network": "VISA",
    "card_last4": "1234"
  }'
```

**Response:**

```json
{
  "ok": true,
  "payment_intent_id": "sg_txn_gpay_001",
  "status": "CAPTURED",
  "amount_cents": 4999,
  "currency": "USD",
  "card_network": "VISA",
  "card_last4": "1234",
  "auth_method": "CRYPTOGRAM_3DS"
}
```

---

## Sample Code

### Node.js (Express — Full Server)

```javascript
const express = require('express');
const axios = require('axios');
require('dotenv').config();

const app = express();
app.use(express.json());
app.use(express.static('public'));

const sg = axios.create({
  baseURL: 'https://sentinelgate.biz',
  headers: { 'x-api-key': process.env.SG_API_KEY, 'Content-Type': 'application/json' }
});

// Charge Google Pay token
app.post('/api/google-pay/charge', async (req, res) => {
  try {
    const { payment_token, amount, currency, order_id, card_network, card_last4 } = req.body;

    const response = await sg.post('/v1/wallet/google-pay/charge', {
      merchant_id: process.env.MERCHANT_ID,
      amount_cents: Math.round(parseFloat(amount) * 100),
      currency: currency || 'USD',
      email: req.body.email || 'customer@example.com',
      reference: `gpay_${order_id}_${Date.now()}`,
      callback_url: 'https://yoursite.com/webhooks/google-pay',
      payment_token,
      card_network,
      card_last4
    });

    res.json(response.data);
  } catch (err) {
    console.error('Google Pay charge failed:', err.response?.data);
    res.status(500).json({ ok: false, error: 'Charge failed' });
  }
});

// Webhook
app.post('/webhooks/google-pay', (req, res) => {
  const { event, transaction_id, status } = req.body;
  console.log(`[GOOGLE_PAY] ${event} | ${transaction_id} | ${status}`);
  // Verify signature, then fulfill order
  res.json({ received: true });
});

app.listen(3000, () => console.log('Google Pay server on http://localhost:3000'));
```

### PHP

```php
<?php
// Charge Google Pay token
function chargeGooglePay($paymentToken, $amountCents, $currency, $email, $cardNetwork, $cardLast4) {
    $ch = curl_init('https://sentinelgate.biz/v1/wallet/google-pay/charge');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'merchant_id' => getenv('MERCHANT_ID'),
            'amount_cents' => $amountCents,
            'currency' => $currency,
            'email' => $email,
            'reference' => 'gpay_' . time(),
            'callback_url' => 'https://yoursite.com/webhooks/google-pay',
            'payment_token' => $paymentToken,
            'card_network' => $cardNetwork,
            'card_last4' => $cardLast4
        ]),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . getenv('SG_API_KEY')
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $res;
}

// Handle incoming Google Pay token from frontend
$body = json_decode(file_get_contents('php://input'), true);
$result = chargeGooglePay(
    $body['payment_token'],
    round($body['amount'] * 100),
    $body['currency'] ?? 'USD',
    $body['email'] ?? 'customer@example.com',
    $body['card_network'] ?? 'VISA',
    $body['card_last4'] ?? '0000'
);
echo json_encode($result);
```

### Python

```python
import requests, os, time

API = 'https://sentinelgate.biz'
HEADERS = {'x-api-key': os.environ['SG_API_KEY'], 'Content-Type': 'application/json'}

def charge_google_pay(payment_token, amount_cents, currency, email, card_network, card_last4):
    """Charge a Google Pay token"""
    resp = requests.post(f'{API}/v1/wallet/google-pay/charge', json={
        'merchant_id': os.environ['MERCHANT_ID'],
        'amount_cents': amount_cents,
        'currency': currency,
        'email': email,
        'reference': f'gpay_{int(time.time())}',
        'callback_url': 'https://yoursite.com/webhooks/google-pay',
        'payment_token': payment_token,
        'card_network': card_network,
        'card_last4': card_last4
    }, headers=HEADERS)
    return resp.json()

# Flask endpoint
from flask import Flask, request, jsonify
app = Flask(__name__)

@app.route('/api/google-pay/charge', methods=['POST'])
def gpay_charge():
    data = request.json
    result = charge_google_pay(
        data['payment_token'],
        round(float(data['amount']) * 100),
        data.get('currency', 'USD'),
        data.get('email', 'customer@example.com'),
        data.get('card_network', 'VISA'),
        data.get('card_last4', '0000')
    )
    return jsonify(result)

@app.route('/webhooks/google-pay', methods=['POST'])
def gpay_webhook():
    data = request.json
    print(f"[GOOGLE_PAY] {data.get('event')} | {data.get('status')}")
    return jsonify(received=True), 200
```

---

## Android Native Integration

```kotlin
// build.gradle
implementation 'com.google.android.gms:play-services-wallet:19.3.0'

// PaymentActivity.kt
import com.google.android.gms.wallet.*

class PaymentActivity : AppCompatActivity() {

    private lateinit var paymentsClient: PaymentsClient

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        paymentsClient = Wallet.getPaymentsClient(
            this,
            Wallet.WalletOptions.Builder()
                .setEnvironment(WalletConstants.ENVIRONMENT_PRODUCTION)
                .build()
        )

        checkGooglePayAvailability()
    }

    private fun checkGooglePayAvailability() {
        val request = IsReadyToPayRequest.newBuilder()
            .addAllowedPaymentMethod(WalletConstants.PAYMENT_METHOD_CARD)
            .addAllowedPaymentMethod(WalletConstants.PAYMENT_METHOD_TOKENIZED_CARD)
            .build()

        paymentsClient.isReadyToPay(request).addOnCompleteListener { task ->
            if (task.result) {
                showGooglePayButton()
            }
        }
    }

    private fun requestPayment() {
        val tokenizationSpec = PaymentMethodTokenizationParameters.newBuilder()
            .setPaymentMethodTokenizationType(
                WalletConstants.PAYMENT_METHOD_TOKENIZATION_TYPE_PAYMENT_GATEWAY
            )
            .addParameter("gateway", "sentinelgate")
            .addParameter("gatewayMerchantId", "your-merchant-id")
            .build()

        val request = PaymentDataRequest.newBuilder()
            .setTransactionInfo(
                TransactionInfo.newBuilder()
                    .setTotalPriceStatus(WalletConstants.TOTAL_PRICE_STATUS_FINAL)
                    .setTotalPrice("49.99")
                    .setCurrencyCode("USD")
                    .build()
            )
            .addAllowedPaymentMethod(WalletConstants.PAYMENT_METHOD_CARD)
            .addAllowedPaymentMethod(WalletConstants.PAYMENT_METHOD_TOKENIZED_CARD)
            .setCardRequirements(
                CardRequirements.newBuilder()
                    .addAllowedCardNetworks(listOf("VISA", "MASTERCARD", "AMEX"))
                    .build()
            )
            .setPaymentMethodTokenizationParameters(tokenizationSpec)
            .build()

        AutoResolveHelper.resolveTask(
            paymentsClient.loadPaymentData(request), this, GOOGLE_PAY_REQUEST_CODE
        )
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == GOOGLE_PAY_REQUEST_CODE && resultCode == RESULT_OK) {
            val paymentData = PaymentData.getFromIntent(data!!)
            val token = paymentData?.paymentMethodToken?.token
            // Send token to your server → SentinelGate
        }
    }

    companion object {
        const val GOOGLE_PAY_REQUEST_CODE = 991
    }
}
```

---

## Hosted Checkout (Easiest)

If you use SentinelGate's hosted checkout, Google Pay is automatically available — no additional code needed.

```bash
curl -X POST https://sentinelgate.biz/v1/hosted/create \
  -H "x-api-key: sk_live_your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": "49.99",
    "currency": "USD",
    "order_id": "ORD-001",
    "customer_email": "buyer@example.com",
    "callback_url": "https://yoursite.com/webhooks",
    "wallets": ["APPLE_PAY", "GOOGLE_PAY"]
  }'
```

The hosted checkout automatically shows the Google Pay button when available.

---

## Testing

**Environment:** Set `environment: 'TEST'` in the PaymentsClient and use `sk_test_` API key.

| Scenario | Test Card (Google Test Suite) | Result |
|----------|-------------------------------|--------|
| Successful payment | Any test card | CAPTURED |
| Declined | Card ending 0002 | FAILED |
| 3DS challenge | Card ending 3220 | 3DS then CAPTURED |
| Network error | Card ending 9999 | PROVIDER_ERROR |

**Google Pay Test Cards (TEST environment):**

| Network | Number | Expiry | CVV |
|---------|--------|--------|-----|
| Visa | 4111 1111 1111 1111 | Any future | Any |
| Mastercard | 5555 5555 5555 4444 | Any future | Any |
| Amex | 3782 822463 10005 | Any future | Any |

In TEST mode, Google Pay returns test tokens that SentinelGate sandbox accepts. No real money moves.

---

## Webhook Callback

```json
{
  "event": "payment.captured",
  "transaction_id": "sg_txn_gpay_001",
  "status": "CAPTURED",
  "amount_cents": 4999,
  "currency": "USD",
  "rail": "GOOGLE_PAY",
  "metadata": {
    "card_network": "VISA",
    "card_last4": "1234",
    "auth_method": "CRYPTOGRAM_3DS",
    "device_type": "ANDROID"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `TOKEN_DECRYPT_FAILED` | Could not decrypt the Google Pay token |
| `TOKEN_EXPIRED` | Payment token has expired |
| `CARD_DECLINED` | Underlying card was declined |
| `UNSUPPORTED_NETWORK` | Card network not supported |
| `3DS_FAILED` | 3D Secure verification failed |
| `MERCHANT_NOT_CONFIGURED` | Google Pay not configured for this merchant |
| `INVALID_TOKEN_FORMAT` | Token structure does not match expected format |
| `GOOGLE_PAY_NOT_ENABLED` | Google Pay is not enabled for your account |

---

## Apple Pay vs Google Pay Comparison

| Feature | Apple Pay | Google Pay |
|---------|-----------|------------|
| Domain verification | Required | Not required |
| Certificate | Payment Processing Cert needed | No certificate needed |
| Session validation | Merchant session flow required | Not required |
| Supported browsers | Safari only | Chrome + any browser |
| Mobile | iOS, watchOS | Android |
| Token format | EC_v1 | ECv2 |
| Integration complexity | Higher | Lower |

Both are supported simultaneously on SentinelGate hosted checkout.

---

## Checklist

- [ ] Google Pay & Wallet Console account created
- [ ] Business profile approved by Google
- [ ] Google Merchant ID received
- [ ] Google Pay button renders on page
- [ ] Token charge working via SentinelGate
- [ ] Webhook receiving callbacks
- [ ] Tested in TEST environment
- [ ] Switched to PRODUCTION environment

---

© 2026 SentinelGate — Whyte AG Group
