/**
 * SentinelGate Webhook Server
 * Handles callbacks for all 25 payment methods
 *
 * Run: node webhook-server.js
 * Requires: npm install express dotenv
 */

require('dotenv').config();
const express = require('express');
const crypto = require('crypto');
const app = express();

const WEBHOOK_SECRET = process.env.SG_WEBHOOK_SECRET || 'your_webhook_secret';
const PORT = process.env.WEBHOOK_PORT || 4000;

app.use(express.json());

// ─── Signature Verification ──────────────────────────────────
function verifySignature(body, signature) {
  const hash = crypto
    .createHmac('sha512', WEBHOOK_SECRET)
    .update(JSON.stringify(body))
    .digest('hex');
  return `sha512=${hash}` === signature;
}

// ─── Generic Webhook Handler ─────────────────────────────────
function handleWebhook(rail) {
  return (req, res) => {
    const sig = req.headers['x-sentinel-signature'];

    if (sig && !verifySignature(req.body, sig)) {
      console.log(`[${rail}] ✗ Invalid signature — rejected`);
      return res.status(401).json({ error: 'Invalid signature' });
    }

    const { event, transaction_id, status, amount_cents, currency, metadata } = req.body;

    console.log(`\n[${rail}] Webhook received:`);
    console.log(`  Event:      ${event}`);
    console.log(`  Txn ID:     ${transaction_id}`);
    console.log(`  Status:     ${status}`);
    console.log(`  Amount:     ${amount_cents} ${currency}`);
    if (metadata) console.log(`  Metadata:   ${JSON.stringify(metadata)}`);

    switch (event) {
      case 'payment.captured':
        console.log(`  ✓ Payment captured — fulfill order`);
        // YOUR LOGIC: Mark order as paid, deliver goods, etc.
        break;
      case 'payment.failed':
        console.log(`  ✗ Payment failed — notify customer`);
        // YOUR LOGIC: Mark order as failed, retry, etc.
        break;
      case 'payment.pending':
        console.log(`  ⏳ Payment pending — awaiting confirmation`);
        break;
      case 'payment.refunded':
        console.log(`  ↩ Payment refunded`);
        break;
      default:
        console.log(`  ? Unknown event: ${event}`);
    }

    res.status(200).json({ received: true });
  };
}

// ─── Register routes for all 25 methods ──────────────────────
const RAILS = [
  'upi', 'ach_us', 'ach_gh', 'echeck_us', 'eft_ca', 'interac',
  'sepa', 'pix', 'cup', 'snipe', 'b2b_collect',
  'nip_ng', 'gip_gh', 'mmi_gh', 'pesalink', 'rndps_rw', 'tiss_tz',
  'sinpe_cr', 'sipard_do', 'ach_do',
  'crypto_pay', 'crypto-send', 'crypto-buy',
  'echip_pay', 'echip_swap',
  'apple-pay', 'google-pay'
];

RAILS.forEach(rail => {
  app.post(`/webhooks/${rail}`, handleWebhook(rail.toUpperCase()));
});

// Catch-all for any rail
app.post('/webhooks/:rail', (req, res) => {
  console.log(`[${req.params.rail.toUpperCase()}] Unregistered rail webhook`);
  handleWebhook(req.params.rail.toUpperCase())(req, res);
});

// Health check
app.get('/health', (req, res) => res.json({ status: 'ok', rails: RAILS.length }));

app.listen(PORT, () => {
  console.log(`SentinelGate Webhook Server listening on port ${PORT}`);
  console.log(`Registered ${RAILS.length} webhook endpoints`);
  console.log(`Health check: http://localhost:${PORT}/health`);
});
