# Changelog

All notable changes to SentinelGate are documented here.

---

## [2026-03-04] — Session 6

### Added
- **Merchant Portal: Payment Link Generator** — Merchants can now create payment links directly from the portal (Payment Links → New Payment Link) with title, type, amount, currency, description, and expiry fields
- **POST /v1/merchant/payment-links/create** — New merchant-scoped API endpoint for creating payment links
- **GET /v1/merchant/activity** — New endpoint for recent transaction feed on merchant dashboard
- **PaymentIntent tracking columns** — Added `reference`, `updated_at`, `provider_ref`, and `metadata` columns to PaymentIntent table
- **Database indexes** — Added `idx_pi_reference` and `idx_pi_merchant_status` for faster lookups
- **Apex Labs** — New merchant onboarded with card processing (USD)
- **MLOPS Consulting** — New merchant onboarded with card and mobile money processing (GHS)

### Fixed
- **Payment status tracking** — PaymentIntents no longer get stuck in PENDING; reference-based callback matching ensures correct status updates
- **Status case inconsistency** — Fixed 17 records with lowercase "pending" to uppercase "PENDING"
- **Provider ref type mismatch** — Fixed silent Prisma failure when numeric provider references were written to String columns
- **Admin dashboard metrics** — Regenerated admin API keys; all CRM and analytics endpoints now return data correctly
- **Card-only checkout** — Checkout redirects now go directly to the card entry form, skipping the payment method selection page

### Changed
- **Callback matching** — Switched from fragile merchant_id + PENDING lookup to reference-based matching with fallback
- **Orchestrator error handling** — Added error logging with stack traces and proper type casting
- **Payment links page** — Rewrote from read-only list to full CRUD with create form

---

## [2026-02-11] — Session 5

### Added
- **Brooks merchant routing** — Three merchant profiles with USD routing, daily limits, and provider failover
- **6 new provider adapters** — Card, mobile money, and alternative payment method adapters
- **Provider health checks** — Health method added to all provider adapters

### Fixed
- **Adapter health method duplicates** — Resolved sed-induced duplication across adapter files

---

## [2026-02-10] — Session 4

### Added
- **Card Processing Guide** — Comprehensive documentation for card payment flows
- **GitHub documentation repository** — Set up SentinelGateLC/SentinelgateDocumentation

---

## [2026-02-08] — Session 3

### Added
- **Shopify PSP middleware** — Full Shopify integration with HMAC verification, BullMQ worker, Redis idempotency, AES-256-GCM encryption, and rate limiting
- **Shopify tenant management** — /v1/tenants endpoint for onboarding Shopify stores
- **ShopifyTenantConfig and ShopifyCaptureLog** — Prisma models for Shopify integration

---

## [2026-02-06] — Session 2

### Added
- **Payment Links system** — Create, list, disable payment links with QR code generation
- **Hosted checkout** — /v1/hosted/create and /v1/hosted/pay/:session endpoints
- **Payment orchestrator** — Provider routing with priority-based selection and failover
- **M-Pesa STK push** — Mobile money integration for Kenya (KES)
- **WooCommerce plugin** — sentinelgate-psp plugin with Direct/Redirect/iFrame modes

---

## [2026-02-04] — Session 1

### Added
- **Core platform** — svc-rails, api-gateway, web-admin, web-merchant services
- **Prisma schema** — Merchant, Partner, ProviderConfig, ProviderMidProfile, PaymentIntent, PaymentLink models
- **BackOffice authentication** — PBKDF2 + JWT with role-based dashboards
- **Admin console** — Platform administration with metrics and merchant management
- **Merchant portal** — Next.js dashboard with login, transactions, and settings

---

© 2026 SentinelGate — Whyte AG Group
