# Installation & Setup Guide

## For Merchants Using Plugins

### Shopify
1. Download plugin from merchant-packages/[your-merchant-id]/
2. Log into Shopify admin
3. Go to Apps → Manage private apps
4. Install SentinelGate plugin
5. Enter credentials from CREDENTIALS.txt
6. Test with small transaction

### WooCommerce
1. Download plugin from merchant-packages/[your-merchant-id]/
2. Log into WordPress admin
3. Go to Plugins → Add New → Upload
4. Upload and activate SentinelGate plugin
5. Configure credentials in Settings
6. Test checkout

## For Developers (Custom Integration)

### Step 1: Choose Your Language
- Python: See code-examples/python_example.py
- PHP: See code-examples/php_example.py
- Node.js: See code-examples/nodejs_example.js

### Step 2: Install Dependencies

**Python:**
```bash
pip install requests
```

**PHP:**
```bash
composer require guzzlehttp/guzzle
```

**Node.js:**
```bash
npm install axios dotenv
```

### Step 3: Set Up Environment Variables

Create `.env` file:
```env
SENTINELGATE_API_KEY=sk_live_your_key
SENTINELGATE_API_SECRET=secret_your_secret
SENTINELGATE_WEBHOOK_SECRET=whsec_your_webhook_secret
SENTINELGATE_BASE_URL=http://185.229.224.244:3000
```

### Step 4: Test Integration

1. Copy example code for your language
2. Update credentials
3. Run test payment
4. Verify webhook receipt

### Step 5: Deploy to Production

See: DEPLOYMENT_CHECKLIST.md

## Using Postman Collection

1. Open Postman
2. Import: SentinelGate_API.postman_collection.json
3. Set variables:
   - base_url: http://185.229.224.244:3000
   - api_key: [your key]
   - api_secret: [your secret]
4. Test endpoints

## Troubleshooting

See: documentation/troubleshooting/COMMON_ISSUES.md

## Support

Email: Support@SentinelGAte.Biz
