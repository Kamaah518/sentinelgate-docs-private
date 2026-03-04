"""
SentinelGate PSP — Python Examples
All 25 Payment Methods

Setup:
    pip install requests flask python-dotenv

Usage:
    python test_all_methods.py           # Run all tests
    python test_all_methods.py UPI       # Run single test
    python test_all_methods.py --server  # Start webhook server

Environment variables:
    SG_API_KEY=sk_test_your_key
    MERCHANT_ID=your-merchant-id
"""

import os
import sys
import time
import json
import hmac
import hashlib
import requests
from datetime import datetime

API_KEY     = os.environ.get('SG_API_KEY', 'sk_test_your_key')
MERCHANT_ID = os.environ.get('MERCHANT_ID', 'your-merchant-id')
BASE_URL    = 'https://sentinelgate.biz'
CALLBACK    = os.environ.get('CALLBACK_URL', 'https://yoursite.com/webhooks')

HEADERS = {
    'x-api-key': API_KEY,
    'Content-Type': 'application/json'
}


# ─── Helpers ────────────────────────────────────────────────
def sg_post(endpoint, payload):
    """POST to SentinelGate API"""
    resp = requests.post(f'{BASE_URL}{endpoint}', json=payload, headers=HEADERS, timeout=30)
    data = resp.json()
    if not resp.ok:
        raise Exception(data.get('error', f'HTTP {resp.status_code}'))
    return data


def sg_charge(rail, currency, amount_cents, email, metadata=None):
    """Create a charge for any rail"""
    ref = f'{rail.lower()}_{int(time.time())}'
    return sg_post('/v1/charge', {
        'amount_cents': amount_cents,
        'currency': currency,
        'merchant_id': MERCHANT_ID,
        'rail': rail,
        'email': email,
        'reference': ref,
        'callback_url': f'{CALLBACK}/{rail.lower()}',
        'metadata': metadata or {}
    })


# ═══════════════════════════════════════════════════════════════
# All 25 Test Methods
# ═══════════════════════════════════════════════════════════════

def test_01_upi():
    """1. UPI — Unified Payments Interface (India)"""
    return sg_charge('UPI', 'INR', 100000, 'buyer@example.com', {
        'vpa': 'success@upi', 'customer_name': 'Raj Sharma'
    })

def test_02_ach_us():
    """2. ACH — Automated Clearing House (USA)"""
    return sg_charge('ACH_US', 'USD', 25000, 'buyer@example.com', {
        'account_number': '1234567890', 'routing_number': '021000021',
        'account_type': 'checking', 'account_holder': 'John Doe'
    })

def test_03_ach_gh():
    """3. ACH (Ghana)"""
    return sg_charge('ACH_GH', 'GHS', 50000, 'buyer@example.com', {
        'account_number': '1234567890123', 'bank_code': 'GCB',
        'account_name': 'Kwame Asante'
    })

def test_04_echeck():
    """4. eCheck (USA)"""
    return sg_charge('ECHECK_US', 'USD', 75000, 'buyer@example.com', {
        'account_number': '1234567890', 'routing_number': '021000021',
        'check_number': '1001', 'account_holder': 'Jane Doe',
        'account_type': 'checking'
    })

def test_05_eft_ca():
    """5. EFT (Canada)"""
    return sg_charge('EFT_CA', 'CAD', 15000, 'buyer@example.com', {
        'transit_number': '12345', 'institution_number': '001',
        'account_number': '1234567', 'account_holder': 'Sarah Chen'
    })

def test_06_interac():
    """6. Interac e-Transfer (Canada)"""
    return sg_charge('INTERAC', 'CAD', 10000, 'success@test.com', {
        'customer_name': 'Alex Thompson', 'message': 'Payment for Order #1234'
    })

def test_07_sepa():
    """7. SEPA (Europe)"""
    return sg_charge('SEPA', 'EUR', 50000, 'buyer@example.com', {
        'iban': 'DE89370400440532013000', 'bic': 'COBADEFFXXX',
        'account_holder': 'Hans Mueller', 'mandate_ref': 'MNDT-2026-001'
    })

def test_08_pix():
    """8. PIX (Brazil)"""
    return sg_charge('PIX', 'BRL', 100000, 'buyer@example.com', {
        'pix_key': 'success@test.com', 'pix_key_type': 'EMAIL',
        'customer_name': 'Maria Silva', 'customer_cpf': '12345678901'
    })

def test_09_cup():
    """9. China UnionPay"""
    return sg_charge('CUP', 'CNY', 500000, 'buyer@example.com', {
        'card_type': 'DEBIT', 'customer_name': 'Wei Zhang'
    })

def test_10_snipe():
    """10. Snipe Móvil"""
    return sg_charge('SNIPE', 'MXN', 100000, 'buyer@example.com', {
        'phone': '+5215500000001', 'customer_name': 'Carlos Rodriguez'
    })

def test_11_b2b():
    """11. Bank-to-Bank Transfer Collections"""
    return sg_charge('B2B_COLLECT', 'USD', 100000, 'buyer@example.com', {
        'source_country': 'US', 'source_bank_code': '021000021',
        'account_number': '1234567890', 'account_holder': 'John Doe',
        'purpose': 'GOODS_PAYMENT'
    })

def test_12_nip():
    """12. NIP — NIBSS Instant Payment (Nigeria)"""
    return sg_charge('NIP_NG', 'NGN', 5000000, 'buyer@example.com', {
        'account_number': '0123456789', 'bank_code': '058',
        'account_name': 'Emeka Obi'
    })

def test_13_gip():
    """13. GIP — GhIPSS Instant Pay (Ghana)"""
    return sg_charge('GIP_GH', 'GHS', 100000, 'buyer@example.com', {
        'account_number': '1234567890123', 'bank_code': 'GCB',
        'account_name': 'Ama Mensah'
    })

def test_14_mmi():
    """14. MMI — Mobile Money Interoperability (Ghana)"""
    return sg_charge('MMI_GH', 'GHS', 50000, 'buyer@example.com', {
        'wallet_number': '0241234567', 'network': 'MTN',
        'customer_name': 'Kwesi Adu'
    })

def test_15_pesalink():
    """15. PesaLink (Kenya)"""
    return sg_charge('PESALINK', 'KES', 500000, 'buyer@example.com', {
        'account_number': '1234567890', 'bank_code': 'KCB',
        'account_name': 'James Mwangi'
    })

def test_16_rndps():
    """16. RNDPS (Rwanda)"""
    return sg_charge('RNDPS_RW', 'RWF', 5000000, 'buyer@example.com', {
        'account_number': '100012345678', 'bank_code': 'BK',
        'account_name': 'Jean Uwimana', 'payment_type': 'BANK'
    })

def test_17_tiss():
    """17. TISS (Tanzania)"""
    return sg_charge('TISS_TZ', 'TZS', 10000000, 'buyer@example.com', {
        'account_number': '0123456789012', 'bank_code': 'CRDB',
        'account_name': 'Joseph Mwamba'
    })

def test_18_sinpe():
    """18. SINPE Móvil (Costa Rica)"""
    return sg_charge('SINPE_CR', 'CRC', 5000000, 'buyer@example.com', {
        'phone': '+50600000001', 'customer_name': 'Ana Ramirez'
    })

def test_19_sipard():
    """19. SIPARD / LBTR (Dominican Republic)"""
    return sg_charge('SIPARD_DO', 'DOP', 500000, 'buyer@example.com', {
        'account_number': '1234567890', 'bank_code': 'BPD',
        'account_name': 'Maria Gonzalez'
    })

def test_20_ach_do():
    """20. ACH (Dominican Republic)"""
    return sg_charge('ACH_DO', 'DOP', 200000, 'buyer@example.com', {
        'account_number': '1234567890', 'bank_code': 'BPD',
        'account_holder': 'Carlos Perez'
    })

def test_21_crypto_pay():
    """21. Crypto Processing — Collect Payments"""
    return sg_charge('CRYPTO_PAY', 'USD', 10000, 'buyer@example.com', {
        'accepted_coins': ['BTC', 'ETH', 'USDT', 'USDC'],
        'settlement_currency': 'USD', 'expiry_minutes': 30
    })

def test_22_crypto_send():
    """22. Crypto Transfer — Merchant to Anyone"""
    return sg_post('/v1/crypto/send', {
        'merchant_id': MERCHANT_ID,
        'coin': 'USDT', 'network': 'TRC20',
        'to_address': 'TJYtVbr1Kf7qVtWquEEDPzBPNGGaqGfLjq',
        'amount': '250.00',
        'reference': f'crypto_send_{int(time.time())}',
        'callback_url': f'{CALLBACK}/crypto-send'
    })

def test_23_crypto_buy():
    """23. Buy Crypto — For Merchants"""
    # Step 1: Quote
    quote = sg_post('/v1/crypto/quote', {
        'merchant_id': MERCHANT_ID, 'action': 'BUY',
        'fiat_currency': 'USD', 'fiat_amount': 1000.00, 'crypto_coin': 'BTC'
    })
    print(f"    Quote: $1000 = {quote['crypto_amount']} BTC @ {quote['rate']}")

    # Step 2: Confirm
    return sg_post('/v1/crypto/buy', {
        'merchant_id': MERCHANT_ID,
        'quote_id': quote['quote_id'],
        'reference': f'buy_btc_{int(time.time())}'
    })

def test_24_echip_pay():
    """24. eChip Token Processing — Collect eChips"""
    return sg_charge('ECHIP_PAY', 'ECHIP', 50000, 'gamer@example.com', {
        'gamer_tag': 'ProGamer2026',
        'wallet_id': 'ew_abc123def456',
        'item_type': 'GAME_CREDIT',
        'item_name': '500 Gold Coins',
        'game_id': 'clash-kingdoms'
    })

def test_25_echip_swap():
    """25. eChip Exchange — Swap eChips for Crypto or Fiat"""
    # Step 1: Quote
    quote = sg_post('/v1/echip/quote', {
        'merchant_id': MERCHANT_ID, 'direction': 'SELL',
        'echip_amount': 10000, 'target_currency': 'USDT'
    })
    print(f"    Quote: 10000 eCHIP = {quote['target_amount']} USDT")

    # Step 2: Swap
    return sg_post('/v1/echip/swap', {
        'merchant_id': MERCHANT_ID,
        'quote_id': quote['quote_id'],
        'reference': f'swap_{int(time.time())}'
    })


def test_26_apple_pay():
    """26. Apple Pay — Tokenized card via Face ID / Touch ID"""
    return sg_post('/v1/wallet/apple-pay/charge', {
        'merchant_id': MERCHANT_ID,
        'amount_cents': 4999,
        'currency': 'USD',
        'email': 'buyer@example.com',
        'reference': f'apple_{int(time.time())}',
        'callback_url': f'{CALLBACK}/apple-pay',
        'payment_token': {
            'paymentData': {
                'data': 'SANDBOX_TEST_TOKEN',
                'header': {
                    'ephemeralPublicKey': 'TEST_KEY',
                    'publicKeyHash': 'TEST_HASH',
                    'transactionId': f'test_apple_{int(time.time())}'
                },
                'signature': 'TEST_SIGNATURE',
                'version': 'EC_v1'
            },
            'paymentMethod': {
                'displayName': 'Visa 1234',
                'network': 'Visa',
                'type': 'debit'
            },
            'transactionIdentifier': f'test_apple_{int(time.time())}'
        }
    })


def test_27_google_pay():
    """27. Google Pay — Tokenized card via Google account"""
    return sg_post('/v1/wallet/google-pay/charge', {
        'merchant_id': MERCHANT_ID,
        'amount_cents': 4999,
        'currency': 'USD',
        'email': 'buyer@example.com',
        'reference': f'gpay_{int(time.time())}',
        'callback_url': f'{CALLBACK}/google-pay',
        'payment_token': {
            'signature': 'SANDBOX_TEST_SIGNATURE',
            'intermediateSigningKey': {
                'signedKey': '{"keyValue":"TEST_KEY","keyExpiration":"9999999999999"}',
                'signatures': ['TEST_SIG']
            },
            'protocolVersion': 'ECv2',
            'signedMessage': '{"encryptedMessage":"SANDBOX_TEST","ephemeralPublicKey":"TEST","tag":"TEST"}'
        },
        'card_network': 'VISA',
        'card_last4': '4444'
    })


# ═══════════════════════════════════════════════════════════════
# Webhook Server (Flask)
# ═══════════════════════════════════════════════════════════════
def run_webhook_server():
    from flask import Flask, request, jsonify
    app = Flask(__name__)
    SECRET = os.environ.get('SG_WEBHOOK_SECRET', 'your_webhook_secret')

    def verify(body_bytes, sig):
        digest = hmac.new(SECRET.encode(), body_bytes, hashlib.sha512).hexdigest()
        return f'sha512={digest}' == sig

    @app.route('/webhooks/<rail>', methods=['POST'])
    def webhook(rail):
        sig = request.headers.get('x-sentinel-signature', '')
        if sig and not verify(request.data, sig):
            return jsonify(error='Invalid signature'), 401

        data = request.json
        print(f"\n[{rail.upper()}] {data.get('event')} | {data.get('transaction_id')} | {data.get('status')}")

        if data.get('event') == 'payment.captured':
            print(f"  ✓ Captured — fulfill order")
        elif data.get('event') == 'payment.failed':
            print(f"  ✗ Failed — notify customer")

        return jsonify(received=True), 200

    print('SentinelGate Webhook Server — http://localhost:4000')
    app.run(port=4000)


# ═══════════════════════════════════════════════════════════════
# Test Runner
# ═══════════════════════════════════════════════════════════════
ALL_TESTS = [
    ('1.  UPI',          test_01_upi),
    ('2.  ACH_US',       test_02_ach_us),
    ('3.  ACH_GH',       test_03_ach_gh),
    ('4.  ECHECK_US',    test_04_echeck),
    ('5.  EFT_CA',       test_05_eft_ca),
    ('6.  INTERAC',      test_06_interac),
    ('7.  SEPA',         test_07_sepa),
    ('8.  PIX',          test_08_pix),
    ('9.  CUP',          test_09_cup),
    ('10. SNIPE',        test_10_snipe),
    ('11. B2B_COLLECT',  test_11_b2b),
    ('12. NIP_NG',       test_12_nip),
    ('13. GIP_GH',       test_13_gip),
    ('14. MMI_GH',       test_14_mmi),
    ('15. PESALINK',     test_15_pesalink),
    ('16. RNDPS_RW',     test_16_rndps),
    ('17. TISS_TZ',      test_17_tiss),
    ('18. SINPE_CR',     test_18_sinpe),
    ('19. SIPARD_DO',    test_19_sipard),
    ('20. ACH_DO',       test_20_ach_do),
    ('21. CRYPTO_PAY',   test_21_crypto_pay),
    ('22. CRYPTO_SEND',  test_22_crypto_send),
    ('23. CRYPTO_BUY',   test_23_crypto_buy),
    ('24. ECHIP_PAY',    test_24_echip_pay),
    ('25. ECHIP_SWAP',   test_25_echip_swap),
    ('26. APPLE_PAY',    test_26_apple_pay),
    ('27. GOOGLE_PAY',   test_27_google_pay),
]

def run_tests(target=None):
    print('═' * 55)
    print('  SentinelGate — Testing All 25 Payment Methods (Python)')
    print('═' * 55)

    passed = failed = 0
    results = []

    for name, fn in ALL_TESTS:
        if target and target.upper() not in name.upper():
            continue

        print(f'\n▶ {name} — {fn.__doc__}')
        try:
            data = fn()
            status = data.get('status', data.get('ok', 'unknown'))
            print(f'  ✓ Status: {status}')
            results.append((name, 'PASS'))
            passed += 1
        except Exception as e:
            print(f'  ✗ Error: {e}')
            results.append((name, 'FAIL'))
            failed += 1

    total = passed + failed
    print(f'\n{"═" * 55}')
    print(f'  RESULTS: {passed} passed, {failed} failed, {total} total')
    for name, status in results:
        icon = '✓' if status == 'PASS' else '✗'
        print(f'  {icon} {name}')
    print('═' * 55)


if __name__ == '__main__':
    if len(sys.argv) > 1:
        if sys.argv[1] == '--server':
            run_webhook_server()
        else:
            run_tests(sys.argv[1])
    else:
        run_tests()
