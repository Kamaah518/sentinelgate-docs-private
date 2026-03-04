/**
 * SentinelGate PSP — Node.js SDK Examples
 * All 25 Payment Methods
 *
 * Setup:
 *   npm install axios dotenv
 *
 * Environment variables (.env):
 *   SG_API_KEY=sk_test_your_key
 *   MERCHANT_ID=your-merchant-id
 *   CALLBACK_URL=https://yoursite.com/webhooks
 */

require('dotenv').config();
const axios = require('axios');

const sg = axios.create({
  baseURL: 'https://sentinelgate.biz',
  headers: {
    'x-api-key': process.env.SG_API_KEY,
    'Content-Type': 'application/json'
  }
});

const MERCHANT_ID = process.env.MERCHANT_ID;
const CALLBACK = process.env.CALLBACK_URL || 'https://yoursite.com/webhooks';

// ─────────────────────────────────────────────────────────────
// Helper: Generic charge
// ─────────────────────────────────────────────────────────────
async function charge(rail, currency, amountCents, email, metadata = {}) {
  const ref = `${rail.toLowerCase()}_${Date.now()}`;
  const res = await sg.post('/v1/charge', {
    amount_cents: amountCents,
    currency,
    merchant_id: MERCHANT_ID,
    rail,
    email,
    reference: ref,
    callback_url: `${CALLBACK}/${rail.toLowerCase()}`,
    metadata
  });
  console.log(`[${rail}] Status: ${res.data.status} | Ref: ${ref}`);
  return res.data;
}

// ═════════════════════════════════════════════════════════════
// 1. UPI (India)
// ═════════════════════════════════════════════════════════════
async function testUPI() {
  return charge('UPI', 'INR', 100000, 'buyer@example.com', {
    vpa: 'success@upi',
    customer_name: 'Raj Sharma'
  });
}

// ═════════════════════════════════════════════════════════════
// 2. ACH (USA)
// ═════════════════════════════════════════════════════════════
async function testACH_US() {
  return charge('ACH_US', 'USD', 25000, 'buyer@example.com', {
    account_number: '1234567890',
    routing_number: '021000021',
    account_type: 'checking',
    account_holder: 'John Doe'
  });
}

// ═════════════════════════════════════════════════════════════
// 3. ACH (Ghana)
// ═════════════════════════════════════════════════════════════
async function testACH_GH() {
  return charge('ACH_GH', 'GHS', 50000, 'buyer@example.com', {
    account_number: '1234567890123',
    bank_code: 'GCB',
    account_name: 'Kwame Asante'
  });
}

// ═════════════════════════════════════════════════════════════
// 4. eCheck (USA)
// ═════════════════════════════════════════════════════════════
async function testECheck() {
  return charge('ECHECK_US', 'USD', 75000, 'buyer@example.com', {
    account_number: '1234567890',
    routing_number: '021000021',
    check_number: '1001',
    account_holder: 'Jane Doe',
    account_type: 'checking'
  });
}

// ═════════════════════════════════════════════════════════════
// 5. EFT (Canada)
// ═════════════════════════════════════════════════════════════
async function testEFT_CA() {
  return charge('EFT_CA', 'CAD', 15000, 'buyer@example.com', {
    transit_number: '12345',
    institution_number: '001',
    account_number: '1234567',
    account_holder: 'Sarah Chen'
  });
}

// ═════════════════════════════════════════════════════════════
// 6. Interac (Canada)
// ═════════════════════════════════════════════════════════════
async function testInterac() {
  return charge('INTERAC', 'CAD', 10000, 'success@test.com', {
    customer_name: 'Alex Thompson',
    message: 'Payment for Order #1234'
  });
}

// ═════════════════════════════════════════════════════════════
// 7. SEPA (Europe)
// ═════════════════════════════════════════════════════════════
async function testSEPA() {
  return charge('SEPA', 'EUR', 50000, 'buyer@example.com', {
    iban: 'DE89370400440532013000',
    bic: 'COBADEFFXXX',
    account_holder: 'Hans Mueller',
    mandate_ref: 'MNDT-2026-001'
  });
}

// ═════════════════════════════════════════════════════════════
// 8. PIX (Brazil)
// ═════════════════════════════════════════════════════════════
async function testPIX() {
  return charge('PIX', 'BRL', 100000, 'buyer@example.com', {
    pix_key: 'success@test.com',
    pix_key_type: 'EMAIL',
    customer_name: 'Maria Silva',
    customer_cpf: '12345678901'
  });
}

// ═════════════════════════════════════════════════════════════
// 9. China UnionPay
// ═════════════════════════════════════════════════════════════
async function testCUP() {
  return charge('CUP', 'CNY', 500000, 'buyer@example.com', {
    card_type: 'DEBIT',
    customer_name: 'Wei Zhang'
  });
}

// ═════════════════════════════════════════════════════════════
// 10. Snipe Móvil
// ═════════════════════════════════════════════════════════════
async function testSnipe() {
  return charge('SNIPE', 'MXN', 100000, 'buyer@example.com', {
    phone: '+5215500000001',
    customer_name: 'Carlos Rodriguez'
  });
}

// ═════════════════════════════════════════════════════════════
// 11. Bank-to-Bank Transfer Collections
// ═════════════════════════════════════════════════════════════
async function testB2B() {
  return charge('B2B_COLLECT', 'USD', 100000, 'buyer@example.com', {
    source_country: 'US',
    source_currency: 'USD',
    source_bank_code: '021000021',
    account_number: '1234567890',
    account_holder: 'John Doe',
    purpose: 'GOODS_PAYMENT'
  });
}

// ═════════════════════════════════════════════════════════════
// 12. NIP (Nigeria)
// ═════════════════════════════════════════════════════════════
async function testNIP() {
  return charge('NIP_NG', 'NGN', 5000000, 'buyer@example.com', {
    account_number: '0123456789',
    bank_code: '058',
    account_name: 'Emeka Obi'
  });
}

// ═════════════════════════════════════════════════════════════
// 13. GIP (Ghana)
// ═════════════════════════════════════════════════════════════
async function testGIP() {
  return charge('GIP_GH', 'GHS', 100000, 'buyer@example.com', {
    account_number: '1234567890123',
    bank_code: 'GCB',
    account_name: 'Ama Mensah'
  });
}

// ═════════════════════════════════════════════════════════════
// 14. MMI (Ghana)
// ═════════════════════════════════════════════════════════════
async function testMMI() {
  return charge('MMI_GH', 'GHS', 50000, 'buyer@example.com', {
    wallet_number: '0241234567',
    network: 'MTN',
    customer_name: 'Kwesi Adu'
  });
}

// ═════════════════════════════════════════════════════════════
// 15. PesaLink (Kenya)
// ═════════════════════════════════════════════════════════════
async function testPesaLink() {
  return charge('PESALINK', 'KES', 500000, 'buyer@example.com', {
    account_number: '1234567890',
    bank_code: 'KCB',
    account_name: 'James Mwangi'
  });
}

// ═════════════════════════════════════════════════════════════
// 16. RNDPS (Rwanda)
// ═════════════════════════════════════════════════════════════
async function testRNDPS() {
  return charge('RNDPS_RW', 'RWF', 5000000, 'buyer@example.com', {
    account_number: '100012345678',
    bank_code: 'BK',
    account_name: 'Jean Uwimana',
    payment_type: 'BANK'
  });
}

// ═════════════════════════════════════════════════════════════
// 17. TISS (Tanzania)
// ═════════════════════════════════════════════════════════════
async function testTISS() {
  return charge('TISS_TZ', 'TZS', 10000000, 'buyer@example.com', {
    account_number: '0123456789012',
    bank_code: 'CRDB',
    account_name: 'Joseph Mwamba'
  });
}

// ═════════════════════════════════════════════════════════════
// 18. SINPE Móvil (Costa Rica)
// ═════════════════════════════════════════════════════════════
async function testSINPE() {
  return charge('SINPE_CR', 'CRC', 5000000, 'buyer@example.com', {
    phone: '+50600000001',
    customer_name: 'Ana Ramirez'
  });
}

// ═════════════════════════════════════════════════════════════
// 19. SIPARD (Dominican Republic)
// ═════════════════════════════════════════════════════════════
async function testSIPARD() {
  return charge('SIPARD_DO', 'DOP', 500000, 'buyer@example.com', {
    account_number: '1234567890',
    bank_code: 'BPD',
    account_name: 'Maria Gonzalez'
  });
}

// ═════════════════════════════════════════════════════════════
// 20. ACH (Dominican Republic)
// ═════════════════════════════════════════════════════════════
async function testACH_DO() {
  return charge('ACH_DO', 'DOP', 200000, 'buyer@example.com', {
    account_number: '1234567890',
    bank_code: 'BPD',
    account_holder: 'Carlos Perez'
  });
}

// ═════════════════════════════════════════════════════════════
// 21. Crypto Processing (Collect)
// ═════════════════════════════════════════════════════════════
async function testCryptoPay() {
  return charge('CRYPTO_PAY', 'USD', 10000, 'buyer@example.com', {
    accepted_coins: ['BTC', 'ETH', 'USDT', 'USDC'],
    settlement_currency: 'USD',
    expiry_minutes: 30
  });
}

// ═════════════════════════════════════════════════════════════
// 22. Crypto Transfer (Send)
// ═════════════════════════════════════════════════════════════
async function testCryptoSend() {
  const res = await sg.post('/v1/crypto/send', {
    merchant_id: MERCHANT_ID,
    coin: 'USDT',
    network: 'TRC20',
    to_address: 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq',
    amount: '250.00',
    reference: `crypto_send_${Date.now()}`,
    callback_url: `${CALLBACK}/crypto-send`
  });
  console.log('[CRYPTO_SEND] Transfer ID:', res.data.transfer_id);
  return res.data;
}

// ═════════════════════════════════════════════════════════════
// 23. Buy Crypto (For Merchants)
// ═════════════════════════════════════════════════════════════
async function testCryptoBuy() {
  // Step 1: Quote
  const quote = await sg.post('/v1/crypto/quote', {
    merchant_id: MERCHANT_ID,
    action: 'BUY',
    fiat_currency: 'USD',
    fiat_amount: 1000.00,
    crypto_coin: 'BTC'
  });
  console.log(`[CRYPTO_BUY] Quote: $1000 = ${quote.data.crypto_amount} BTC @ ${quote.data.rate}`);

  // Step 2: Confirm
  const purchase = await sg.post('/v1/crypto/buy', {
    merchant_id: MERCHANT_ID,
    quote_id: quote.data.quote_id,
    reference: `buy_btc_${Date.now()}`
  });
  console.log('[CRYPTO_BUY] Status:', purchase.data.status);
  return purchase.data;
}

// ═════════════════════════════════════════════════════════════
// 24. eChip Token Processing (Collect)
// ═════════════════════════════════════════════════════════════
async function testEChipPay() {
  return charge('ECHIP_PAY', 'ECHIP', 50000, 'gamer@example.com', {
    gamer_tag: 'ProGamer2026',
    wallet_id: 'ew_abc123def456',
    item_type: 'GAME_CREDIT',
    item_name: '500 Gold Coins',
    game_id: 'clash-kingdoms'
  });
}

// ═════════════════════════════════════════════════════════════
// 25. eChip Exchange (Swap)
// ═════════════════════════════════════════════════════════════
async function testEChipSwap() {
  // Sell eChips for USDT
  const quote = await sg.post('/v1/echip/quote', {
    merchant_id: MERCHANT_ID,
    direction: 'SELL',
    echip_amount: 10000,
    target_currency: 'USDT'
  });
  console.log(`[ECHIP_SWAP] Quote: 10000 eCHIP = ${quote.data.target_amount} USDT`);

  const swap = await sg.post('/v1/echip/swap', {
    merchant_id: MERCHANT_ID,
    quote_id: quote.data.quote_id,
    reference: `swap_${Date.now()}`
  });
  console.log('[ECHIP_SWAP] Status:', swap.data.status);
  return swap.data;
}

// ═════════════════════════════════════════════════════════════
// 26. Apple Pay
// ═════════════════════════════════════════════════════════════
async function testApplePay() {
  // In production, the payment_token comes from the Apple Pay JS session.
  // For sandbox testing, SentinelGate accepts a test token structure.
  const res = await sg.post('/v1/wallet/apple-pay/charge', {
    merchant_id: MERCHANT_ID,
    amount_cents: 4999,
    currency: 'USD',
    email: 'buyer@example.com',
    reference: `apple_${Date.now()}`,
    callback_url: `${CALLBACK}/apple-pay`,
    payment_token: {
      paymentData: {
        data: 'SANDBOX_TEST_TOKEN',
        header: {
          ephemeralPublicKey: 'TEST_KEY',
          publicKeyHash: 'TEST_HASH',
          transactionId: `test_apple_${Date.now()}`
        },
        signature: 'TEST_SIGNATURE',
        version: 'EC_v1'
      },
      paymentMethod: {
        displayName: 'Visa 1234',
        network: 'Visa',
        type: 'debit'
      },
      transactionIdentifier: `test_apple_${Date.now()}`
    }
  });
  console.log('[APPLE_PAY] Status:', res.data.status);
  return res.data;
}

// ═════════════════════════════════════════════════════════════
// 27. Google Pay
// ═════════════════════════════════════════════════════════════
async function testGooglePay() {
  // In production, the payment_token comes from the Google Pay JS API.
  // For sandbox testing, SentinelGate accepts a test token structure.
  const res = await sg.post('/v1/wallet/google-pay/charge', {
    merchant_id: MERCHANT_ID,
    amount_cents: 4999,
    currency: 'USD',
    email: 'buyer@example.com',
    reference: `gpay_${Date.now()}`,
    callback_url: `${CALLBACK}/google-pay`,
    payment_token: {
      signature: 'SANDBOX_TEST_SIGNATURE',
      intermediateSigningKey: {
        signedKey: '{"keyValue":"TEST_KEY","keyExpiration":"9999999999999"}',
        signatures: ['TEST_SIG']
      },
      protocolVersion: 'ECv2',
      signedMessage: '{"encryptedMessage":"SANDBOX_TEST","ephemeralPublicKey":"TEST","tag":"TEST"}'
    },
    card_network: 'VISA',
    card_last4: '4444'
  });
  console.log('[GOOGLE_PAY] Status:', res.data.status);
  return res.data;
}

// ═════════════════════════════════════════════════════════════
// Test Runner
// ═════════════════════════════════════════════════════════════
const ALL_TESTS = {
  '1.  UPI':            testUPI,
  '2.  ACH_US':         testACH_US,
  '3.  ACH_GH':         testACH_GH,
  '4.  ECHECK_US':      testECheck,
  '5.  EFT_CA':         testEFT_CA,
  '6.  INTERAC':        testInterac,
  '7.  SEPA':           testSEPA,
  '8.  PIX':            testPIX,
  '9.  CUP':            testCUP,
  '10. SNIPE':          testSnipe,
  '11. B2B_COLLECT':    testB2B,
  '12. NIP_NG':         testNIP,
  '13. GIP_GH':         testGIP,
  '14. MMI_GH':         testMMI,
  '15. PESALINK':       testPesaLink,
  '16. RNDPS_RW':       testRNDPS,
  '17. TISS_TZ':        testTISS,
  '18. SINPE_CR':       testSINPE,
  '19. SIPARD_DO':      testSIPARD,
  '20. ACH_DO':         testACH_DO,
  '21. CRYPTO_PAY':     testCryptoPay,
  '22. CRYPTO_SEND':    testCryptoSend,
  '23. CRYPTO_BUY':     testCryptoBuy,
  '24. ECHIP_PAY':      testEChipPay,
  '25. ECHIP_SWAP':     testEChipSwap,
  '26. APPLE_PAY':      testApplePay,
  '27. GOOGLE_PAY':     testGooglePay,
};

async function runAll() {
  console.log('═══════════════════════════════════════════════════');
  console.log('  SentinelGate — Testing All 25 Payment Methods');
  console.log('═══════════════════════════════════════════════════\n');

  const results = [];

  for (const [name, fn] of Object.entries(ALL_TESTS)) {
    try {
      console.log(`\n▶ ${name}`);
      const data = await fn();
      results.push({ method: name, status: 'PASS', id: data.payment_intent_id || data.transfer_id || data.purchase_id || data.swap_id || 'N/A' });
      console.log(`  ✓ ${name} — OK`);
    } catch (err) {
      const msg = err.response?.data?.error || err.message;
      results.push({ method: name, status: 'FAIL', error: msg });
      console.log(`  ✗ ${name} — ${msg}`);
    }
  }

  console.log('\n═══════════════════════════════════════════════════');
  console.log('  RESULTS SUMMARY');
  console.log('═══════════════════════════════════════════════════');
  const passed = results.filter(r => r.status === 'PASS').length;
  const failed = results.filter(r => r.status === 'FAIL').length;
  results.forEach(r => console.log(`  ${r.status === 'PASS' ? '✓' : '✗'} ${r.method}`));
  console.log(`\n  ${passed} passed, ${failed} failed, ${results.length} total`);
}

// Run single method: node test-all-methods.js UPI
// Run all: node test-all-methods.js
const target = process.argv[2];
if (target) {
  const match = Object.entries(ALL_TESTS).find(([k]) => k.includes(target.toUpperCase()));
  if (match) match[1]().then(d => console.log(JSON.stringify(d, null, 2))).catch(console.error);
  else console.log('Unknown method. Available:', Object.keys(ALL_TESTS).join(', '));
} else {
  runAll();
}
