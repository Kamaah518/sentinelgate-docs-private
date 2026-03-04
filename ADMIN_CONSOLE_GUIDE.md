# Security

SentinelGate is built with security as a core requirement. This document describes the security practices and architecture.

---

## Encryption

### In Transit
- All API traffic is encrypted with **TLS 1.3**
- HTTPS is enforced on all endpoints — HTTP requests are redirected
- HSTS headers are set on all responses

### At Rest
- Sensitive provider credentials are encrypted with **AES-256-GCM**
- Encryption keys are stored separately from encrypted data
- Database credentials are not stored in source code

---

## Authentication

### Merchant API Keys
- Keys are generated using `crypto.randomBytes(24)` (192-bit entropy)
- Keys are stored as **bcrypt hashes** — plaintext is only shown once at creation
- Each key is scoped to a specific merchant and tenant
- Keys can be revoked immediately via the admin console

### Admin API Keys
- Admin keys use **SHA-256** hashing with optional pepper
- Role-based access control: SUPER_ADMIN, PLATFORM_OPS, PARTNER_ADMIN, SUPPORT_AGENT
- Each role has specific endpoint permissions

### BackOffice Authentication
- Password hashing: **PBKDF2** with salt
- Session management: **JWT** tokens with expiration
- Separate auth system from API key authentication

---

## Webhook Security

- All outgoing webhooks include an **HMAC-SHA512** signature
- Signature is computed over the JSON payload using the merchant's webhook secret
- Merchants must verify signatures before processing webhooks
- Invalid signatures should be rejected with HTTP 401

---

## Payment Data

- **No card storage** — Card details are entered on PCI-compliant hosted forms
- SentinelGate servers never see, transmit, or store raw card numbers
- Only tokenized references and transaction IDs are stored
- **PCI DSS** compliance is maintained by the upstream payment processors

---

## Infrastructure

- **Reverse proxy:** Apache with TLS termination
- **Process isolation:** Each service runs as a separate PM2 process
- **Database:** PostgreSQL with restricted access (localhost only)
- **Cache:** Redis with no external access
- **No root SSH:** Service accounts with minimal permissions (except for initial setup)

---

## Idempotency

- All webhook handlers use idempotency keys to prevent duplicate processing
- Shopify integration uses **Redis-backed idempotency** with automatic expiration
- Payment intent IDs are unique and cannot be reused

---

## Rate Limiting

All API endpoints are rate-limited to prevent abuse:

| Endpoint Type | Limit |
|--------------|-------|
| Payment creation | 100/min per merchant |
| Transaction lookup | 300/min per merchant |
| Payment link creation | 50/min per merchant |
| Admin endpoints | 300/min per admin key |

Exceeding the rate limit returns HTTP 429 with a `Retry-After` header.

---

## Monitoring

- PM2 process monitoring with automatic restart on failure
- Application-level error logging with stack traces
- Transaction status tracking with timestamps
- Provider health monitoring via admin console

---

## Reporting Vulnerabilities

If you discover a security vulnerability, please report it to:

**Email:** security@sentinelgate.biz

We take all reports seriously and will respond within 24 hours.

---

© 2026 SentinelGate — Whyte AG Group
