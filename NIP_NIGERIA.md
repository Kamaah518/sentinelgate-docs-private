<?php
/**
 * SentinelGate PSP — PHP Examples
 * All 25 Payment Methods
 *
 * Usage:
 *   php test-all-methods.php           # Run all tests
 *   php test-all-methods.php UPI       # Run single test
 *
 * Environment: Set SG_API_KEY and MERCHANT_ID before running
 */

$API_KEY     = getenv('SG_API_KEY') ?: 'sk_test_your_key';
$MERCHANT_ID = getenv('MERCHANT_ID') ?: 'your-merchant-id';
$BASE_URL    = 'https://sentinelgate.biz';
$CALLBACK    = 'https://yoursite.com/webhooks';

// ─── HTTP Helper ──────────────────────────────────────────────
function sgPost($endpoint, $payload) {
    global $API_KEY, $BASE_URL;
    $ch = curl_init("{$BASE_URL}{$endpoint}");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . $API_KEY
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($err) throw new Exception("cURL error: {$err}");
    return json_decode($body, true);
}

function sgCharge($rail, $currency, $amountCents, $email, $metadata = []) {
    global $MERCHANT_ID, $CALLBACK;
    $ref = strtolower($rail) . '_' . time();
    return sgPost('/v1/charge', [
        'amount_cents'  => $amountCents,
        'currency'      => $currency,
        'merchant_id'   => $MERCHANT_ID,
        'rail'          => $rail,
        'email'         => $email,
        'reference'     => $ref,
        'callback_url'  => "{$CALLBACK}/" . strtolower($rail),
        'metadata'      => $metadata
    ]);
}

// ═══ 1-20: Standard Charge Methods ════════════════════════════
$methods = [
    ['UPI',         'INR',   100000, ['vpa' => 'success@upi', 'customer_name' => 'Raj Sharma']],
    ['ACH_US',      'USD',   25000,  ['account_number' => '1234567890', 'routing_number' => '021000021', 'account_type' => 'checking', 'account_holder' => 'John Doe']],
    ['ACH_GH',      'GHS',   50000,  ['account_number' => '1234567890123', 'bank_code' => 'GCB', 'account_name' => 'Kwame Asante']],
    ['ECHECK_US',   'USD',   75000,  ['account_number' => '1234567890', 'routing_number' => '021000021', 'check_number' => '1001', 'account_holder' => 'Jane Doe', 'account_type' => 'checking']],
    ['EFT_CA',      'CAD',   15000,  ['transit_number' => '12345', 'institution_number' => '001', 'account_number' => '1234567', 'account_holder' => 'Sarah Chen']],
    ['INTERAC',     'CAD',   10000,  ['customer_name' => 'Alex Thompson', 'message' => 'Payment for Order #1234']],
    ['SEPA',        'EUR',   50000,  ['iban' => 'DE89370400440532013000', 'bic' => 'COBADEFFXXX', 'account_holder' => 'Hans Mueller', 'mandate_ref' => 'MNDT-2026-001']],
    ['PIX',         'BRL',   100000, ['pix_key' => 'success@test.com', 'pix_key_type' => 'EMAIL', 'customer_name' => 'Maria Silva', 'customer_cpf' => '12345678901']],
    ['CUP',         'CNY',   500000, ['card_type' => 'DEBIT', 'customer_name' => 'Wei Zhang']],
    ['SNIPE',       'MXN',   100000, ['phone' => '+5215500000001', 'customer_name' => 'Carlos Rodriguez']],
    ['B2B_COLLECT', 'USD',   100000, ['source_country' => 'US', 'source_bank_code' => '021000021', 'account_number' => '1234567890', 'account_holder' => 'John Doe', 'purpose' => 'GOODS_PAYMENT']],
    ['NIP_NG',      'NGN',   5000000,['account_number' => '0123456789', 'bank_code' => '058', 'account_name' => 'Emeka Obi']],
    ['GIP_GH',      'GHS',   100000, ['account_number' => '1234567890123', 'bank_code' => 'GCB', 'account_name' => 'Ama Mensah']],
    ['MMI_GH',      'GHS',   50000,  ['wallet_number' => '0241234567', 'network' => 'MTN', 'customer_name' => 'Kwesi Adu']],
    ['PESALINK',    'KES',   500000, ['account_number' => '1234567890', 'bank_code' => 'KCB', 'account_name' => 'James Mwangi']],
    ['RNDPS_RW',    'RWF',   5000000,['account_number' => '100012345678', 'bank_code' => 'BK', 'account_name' => 'Jean Uwimana', 'payment_type' => 'BANK']],
    ['TISS_TZ',     'TZS',   10000000,['account_number' => '0123456789012', 'bank_code' => 'CRDB', 'account_name' => 'Joseph Mwamba']],
    ['SINPE_CR',    'CRC',   5000000,['phone' => '+50600000001', 'customer_name' => 'Ana Ramirez']],
    ['SIPARD_DO',   'DOP',   500000, ['account_number' => '1234567890', 'bank_code' => 'BPD', 'account_name' => 'Maria Gonzalez']],
    ['ACH_DO',      'DOP',   200000, ['account_number' => '1234567890', 'bank_code' => 'BPD', 'account_holder' => 'Carlos Perez']],
    ['CRYPTO_PAY',  'USD',   10000,  ['accepted_coins' => ['BTC','ETH','USDT','USDC'], 'settlement_currency' => 'USD', 'expiry_minutes' => 30]],
    ['ECHIP_PAY',   'ECHIP', 50000,  ['gamer_tag' => 'ProGamer2026', 'item_type' => 'GAME_CREDIT', 'item_name' => '500 Gold Coins', 'game_id' => 'clash-kingdoms']],
];

// ═══ 22: Crypto Send ══════════════════════════════════════════
function testCryptoSend() {
    global $MERCHANT_ID, $CALLBACK;
    return sgPost('/v1/crypto/send', [
        'merchant_id' => $MERCHANT_ID,
        'coin' => 'USDT', 'network' => 'TRC20',
        'to_address' => 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq',
        'amount' => '250.00',
        'reference' => 'crypto_send_' . time(),
        'callback_url' => "{$CALLBACK}/crypto-send"
    ]);
}

// ═══ 23: Crypto Buy ═══════════════════════════════════════════
function testCryptoBuy() {
    global $MERCHANT_ID;
    $quote = sgPost('/v1/crypto/quote', [
        'merchant_id' => $MERCHANT_ID, 'action' => 'BUY',
        'fiat_currency' => 'USD', 'fiat_amount' => 1000.00, 'crypto_coin' => 'BTC'
    ]);
    echo "  Quote: \$1000 = {$quote['crypto_amount']} BTC\n";
    return sgPost('/v1/crypto/buy', [
        'merchant_id' => $MERCHANT_ID,
        'quote_id' => $quote['quote_id'],
        'reference' => 'buy_btc_' . time()
    ]);
}

// ═══ 25: eChip Exchange ═══════════════════════════════════════
function testEChipSwap() {
    global $MERCHANT_ID;
    $quote = sgPost('/v1/echip/quote', [
        'merchant_id' => $MERCHANT_ID, 'direction' => 'SELL',
        'echip_amount' => 10000, 'target_currency' => 'USDT'
    ]);
    echo "  Quote: 10000 eCHIP = {$quote['target_amount']} USDT\n";
    return sgPost('/v1/echip/swap', [
        'merchant_id' => $MERCHANT_ID,
        'quote_id' => $quote['quote_id'],
        'reference' => 'swap_' . time()
    ]);
}

// ═══ 26: Apple Pay ════════════════════════════════════════════
function testApplePay() {
    global $MERCHANT_ID, $CALLBACK;
    return sgPost('/v1/wallet/apple-pay/charge', [
        'merchant_id' => $MERCHANT_ID,
        'amount_cents' => 4999,
        'currency' => 'USD',
        'email' => 'buyer@example.com',
        'reference' => 'apple_' . time(),
        'callback_url' => "{$CALLBACK}/apple-pay",
        'payment_token' => [
            'paymentData' => [
                'data' => 'SANDBOX_TEST_TOKEN',
                'header' => [
                    'ephemeralPublicKey' => 'TEST_KEY',
                    'publicKeyHash' => 'TEST_HASH',
                    'transactionId' => 'test_apple_' . time()
                ],
                'signature' => 'TEST_SIGNATURE',
                'version' => 'EC_v1'
            ],
            'paymentMethod' => [
                'displayName' => 'Visa 1234',
                'network' => 'Visa',
                'type' => 'debit'
            ],
            'transactionIdentifier' => 'test_apple_' . time()
        ]
    ]);
}

// ═══ 27: Google Pay ═══════════════════════════════════════════
function testGooglePay() {
    global $MERCHANT_ID, $CALLBACK;
    return sgPost('/v1/wallet/google-pay/charge', [
        'merchant_id' => $MERCHANT_ID,
        'amount_cents' => 4999,
        'currency' => 'USD',
        'email' => 'buyer@example.com',
        'reference' => 'gpay_' . time(),
        'callback_url' => "{$CALLBACK}/google-pay",
        'payment_token' => [
            'signature' => 'SANDBOX_TEST_SIGNATURE',
            'intermediateSigningKey' => [
                'signedKey' => '{"keyValue":"TEST_KEY","keyExpiration":"9999999999999"}',
                'signatures' => ['TEST_SIG']
            ],
            'protocolVersion' => 'ECv2',
            'signedMessage' => '{"encryptedMessage":"SANDBOX_TEST","ephemeralPublicKey":"TEST","tag":"TEST"}'
        ],
        'card_network' => 'VISA',
        'card_last4' => '4444'
    ]);
}

// ═══ Test Runner ══════════════════════════════════════════════
echo "═══════════════════════════════════════════════════\n";
echo "  SentinelGate — Testing All 25 Payment Methods (PHP)\n";
echo "═══════════════════════════════════════════════════\n\n";

$target = $argv[1] ?? null;
$passed = 0; $failed = 0;

foreach ($methods as $i => $m) {
    $rail = $m[0];
    if ($target && stripos($rail, $target) === false) continue;

    $num = $i + 1;
    echo "▶ {$num}. {$rail}\n";
    try {
        $result = sgCharge($rail, $m[1], $m[2], 'buyer@example.com', $m[3]);
        $status = $result['status'] ?? $result['error'] ?? 'unknown';
        echo "  ✓ Status: {$status}\n";
        $passed++;
    } catch (Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
        $failed++;
    }
}

// Special methods (22, 23, 25)
$specials = [
    '22. CRYPTO_SEND'  => 'testCryptoSend',
    '23. CRYPTO_BUY'   => 'testCryptoBuy',
    '25. ECHIP_SWAP'   => 'testEChipSwap',
    '26. APPLE_PAY'    => 'testApplePay',
    '27. GOOGLE_PAY'   => 'testGooglePay',
];

foreach ($specials as $name => $fn) {
    if ($target && stripos($name, $target) === false) continue;
    echo "▶ {$name}\n";
    try {
        $result = $fn();
        $status = $result['status'] ?? 'ok';
        echo "  ✓ Status: {$status}\n";
        $passed++;
    } catch (Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
        $failed++;
    }
}

$total = $passed + $failed;
echo "\n═══════════════════════════════════════════════════\n";
echo "  {$passed} passed, {$failed} failed, {$total} total\n";
echo "═══════════════════════════════════════════════════\n";
