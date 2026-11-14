# PRD: Configurable API-First Single-Vendor E-commerce Platform

| | |
|---|---|
| **Status** | Draft v0.1 |
| **Stack** | Laravel, MySQL, Tailwind CSS (web UI), Redis (cache/queue) |
| **Deployment model** | Model A: প্রতি client-এর আলাদা deployment (আলাদা server/DB/`.env`) |
| **Phase order** | Core -> **Honey & Nuts** -> **Grocery** -> Growth |
| **Next doc** | SRS (এই PRD approve হলে) |

---

## 1. Vision ও Problem

### Vision
একটা reusable e-commerce **product** যেটা একই codebase থেকে বিভিন্ন ধরনের single-vendor store (honey-nuts, grocery, ভবিষ্যতে fashion) চালাতে পারে। Store type, theme ও feature **configuration দিয়ে** বদলায়, code বদলাতে হয় না।

### Problem
- Bangladesh-এ ছোট/মাঝারি seller-দের প্রয়োজন: COD, bKash/Nagad, courier integration, fake-order control, SMS notification। Generic tool (Shopify, WooCommerce) এগুলো ঠিকভাবে দেয় না।
- প্রতি client-এর জন্য নতুন করে code লিখলে খরচ ও maintenance বাড়ে।
- Vertical ভেদে product structure আলাদা (honey: weight/batch, grocery: unit/expiry/slot, fashion: size×color)।

### Strategic Principle: API-First
**সব business logic শুধু API-র মাধ্যমে expose হবে।** Web storefront, admin panel, ভবিষ্যতের React frontend ও mobile app, সবাই একই API ব্যবহার করবে। কোনো business rule UI-তে লেখা হবে না।

---

## 2. Goals ও Non-Goals

### Goals
1. Honey-nuts store-এর জন্য **end-to-end কাজ করা MVP** (browse -> order -> deliver -> return)।
2. একই codebase-এ grocery vertical যোগ করা যাবে, core না ভেঙে।
3. সব feature **versioned REST API** দিয়ে ব্যবহারযোগ্য, যাতে React/mobile app পরে বানানো যায়।
4. Store type, feature ও theme admin panel/`.env` থেকে configurable।
5. COD-কেন্দ্রিক BD বাস্তবতা (courier, fraud control, partial return) প্রথম থেকেই built-in।

### Non-Goals (এখন নয়)
- Multi-vendor marketplace (একাধিক seller একই store-এ)
- Multi-tenant SaaS (একটা DB-তে অনেক store); তবে design এটাকে আটকাবে না
- Native mobile app বানানো (শুধু API ready রাখা হবে)
- Fashion vertical (Phase 3+)
- Accounting/ERP/POS পূর্ণ সমাধান

---

## 3. Users ও Personas

| Persona | বর্ণনা | মূল চাহিদা |
|---|---|---|
| **Platform Owner (আপনি)** | Product বিক্রি করেন, প্রতি client-এর জন্য deploy করেন | সহজ setup, preset, কম custom code |
| **Store Owner / Admin** | Client, দোকান চালান | Order, stock, product, report, offer সহজে পরিচালনা |
| **Store Staff** | Order handler, packer, content editor | সীমিত role-based access |
| **Customer** | Mobile-first BD ক্রেতা | দ্রুত site, সহজ checkout, COD, trust |
| **API Consumer (Developer)** | React frontend / mobile app | Stable, documented, versioned API |

---

## 4. Scope ও Phase Plan

| Phase | নাম | লক্ষ্য |
|---|---|---|
| **0** | Foundation | API skeleton, auth, settings/feature-flag, attribute system, theme engine, admin base |
| **1** | **Honey & Nuts MVP** | প্রথম client-এর পূর্ণ store চালু |
| **2** | **Grocery** | Delivery slot, weight billing, batch/expiry, dense theme |
| **3** | Growth | Loyalty, subscription, recommendation, PWA/React storefront, fashion vertical |

---

## 5. Configurability Model

### 5.1 Layer
```
5. Content        (banner, logo, text)       <- Admin
4. Theme          (layout, color, font)      <- Admin / preset
3. Feature Flags  (কোনটা on/off)             <- Preset + Admin override
2. Product Schema (attribute set)            <- Preset + Admin custom
1. Core Engine    (cart, order, payment...)  <- সবার এক
```

### 5.2 Config Priority (উপর থেকে নিচে override)
1. Admin panel setting (DB, runtime)
2. Store preset (`honey_nuts`, `grocery`)
3. `.env` (deploy-level: `STORE_TYPE`, `APP_LOCALE`, `DEFAULT_THEME`, keys)
4. Code default

### 5.3 Requirements
| ID | Requirement | Priority |
|---|---|---|
| CFG-1 | `STORE_TYPE` env দিয়ে প্রথম setup-এ preset load হবে | P0 |
| CFG-2 | Feature flag admin থেকে on/off করা যাবে; off থাকলে route, menu, query load হবে না | P0 |
| CFG-3 | Settings cache থাকবে (Redis), admin save-এ invalidate হবে | P0 |
| CFG-4 | Terminology/label customize করা যাবে ("Add to cart" -> "ঝুড়িতে নিন") | P1 |
| CFG-5 | Preset import/export (JSON) | P2 |

---

## 6. API-First Requirements

| ID | Requirement | Priority |
|---|---|---|
| API-1 | সব endpoint `/api/v1/...` এর অধীনে, versioned | P0 |
| API-2 | তিনটি আলাদা surface: **Storefront API** (public + customer), **Admin API** (staff), **Webhook** (payment/courier inbound) | P0 |
| API-3 | Auth: Laravel Sanctum token (SPA/mobile), customer login via phone OTP/email | P0 |
| API-4 | Consistent JSON envelope, error format (code, message, field errors), HTTP status সঠিক | P0 |
| API-5 | Pagination, filter, sort, include (sparse) সব list endpoint-এ standard | P0 |
| API-6 | OpenAPI (Swagger) spec auto-generate ও publish | P0 |
| API-7 | Business logic **Service/Action layer-এ**, Controller শুধু request/response; যেন যেকোনো client-এর ফল একই হয় | P0 |
| API-8 | Rate limiting (login, OTP, checkout, search) | P0 |
| API-9 | Idempotency key (checkout/payment retry-তে duplicate order রোধ) | P0 |
| API-10 | Localization header (`Accept-Language: bn/en`), সব response-এ translated field | P1 |
| API-11 | Public catalog endpoint HTTP cache/ETag সমর্থন করবে | P1 |
| API-12 | Deprecation policy ও changelog | P1 |
| API-13 | Postman/Insomnia collection ও SDK-friendly naming | P2 |

### Frontend Strategy (মূল সিদ্ধান্ত)
- **Admin panel:** Admin API-র client (Tailwind-based UI)।
- **Storefront (web, v1):** Tailwind CSS দিয়ে বানানো। SEO দরকার বলে server-rendered হবে, কিন্তু **একই Service/Action layer ও API Resource class** ব্যবহার করবে। নিজের API-তে HTTP call করবে না।
- **React/Mobile (ভবিষ্যৎ):** সরাসরি `/api/v1` ব্যবহার করবে, কোনো backend পরিবর্তন লাগবে না।
- **Rule:** Blade/UI-তে কোনো price, stock, discount, tax calculation থাকবে না।

---

## 7. Functional Requirements

Priority: **P0** = MVP-তে অবশ্যই, **P1** = MVP-র কাছাকাছি/পরে শীঘ্র, **P2** = পরে।
Phase কলাম: কোন phase-এ ডেলিভারি।

### 7.1 Catalog ও Product

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| CAT-1 | Category (multi-level), brand, tag | P0 | 1 |
| CAT-2 | Product: নাম, slug, description, ছবি/ভিডিও, SEO field, status (draft/active) | P0 | 1 |
| CAT-3 | **Attribute Set system:** attribute type (text/number/select/date/boolean), `is_variant`, `is_filterable`, `is_required` | P0 | 0 |
| CAT-4 | **Variant:** option-ভিত্তিক (honey: weight; grocery: unit/pack)। প্রতি variant-এর SKU, price, sale price, stock | P0 | 1 |
| CAT-5 | Bulk import/export (CSV/Excel) | P1 | 1 |
| CAT-6 | Product bundle / combo / gift pack | P1 | 1 |
| CAT-7 | Related, cross-sell, upsell | P1 | 1 |
| CAT-8 | Product comparison | P2 | 3 |

**Honey-nuts preset attribute:** origin/source, purity/lab-test, harvest date, shelf life, expiry, batch no., ingredients, storage instruction, certification (BSTI ইত্যাদি)।
**Grocery preset attribute (Phase 2):** expiry, unit type, per-kg/per-unit price, perishable flag, brand, origin।

### 7.2 Search ও Discovery

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| SRC-1 | Keyword search (বাংলা + English), autocomplete | P0 | 1 |
| SRC-2 | Filter: category, price, brand, rating, filterable attribute; sort | P0 | 1 |
| SRC-3 | Typo tolerance, search suggestion, popular search | P1 | 2 |
| SRC-4 | Recently viewed, trending, best sellers, new arrivals | P1 | 1 |
| SRC-5 | Search engine (Meilisearch/Scout) integration | P1 | 2 |

### 7.3 Cart ও Checkout

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| CHK-1 | Cart: guest ও logged-in, persistent, merge on login | P0 | 1 |
| CHK-2 | Guest checkout + phone OTP verification | P0 | 1 |
| CHK-3 | Address: division -> district -> upazila/area, saved address book | P0 | 1 |
| CHK-4 | Delivery charge rule: area/weight/threshold-ভিত্তিক, checkout-এর আগেই দেখানো | P0 | 1 |
| CHK-5 | Coupon apply | P0 | 1 |
| CHK-6 | Order summary, stock re-validation, price recalculation server-side | P0 | 1 |
| CHK-7 | Idempotent order placement | P0 | 1 |
| CHK-8 | Abandoned cart capture ও recovery (SMS/email) | P1 | 2 |
| CHK-9 | **Delivery slot selection** | P0 | **2 (grocery)** |
| CHK-10 | Minimum order amount rule | P1 | 2 |
| CHK-11 | Weight-based final billing (আনুমানিক ওজন, পরে adjust) | P1 | 2 |

### 7.4 Payment

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| PAY-1 | Cash on Delivery | P0 | 1 |
| PAY-2 | bKash, Nagad, Rocket (বা aggregator: SSLCommerz/aamarPay) | P0 | 1 |
| PAY-3 | Card (gateway-র মাধ্যমে) | P1 | 1 |
| PAY-4 | Webhook দিয়ে payment verification ও auto order update; signature verify | P0 | 1 |
| PAY-5 | Partial/advance payment (ঝুঁকিপূর্ণ COD-এর জন্য) | P1 | 1 |
| PAY-6 | Refund tracking (manual + gateway) | P1 | 1 |
| PAY-7 | Payment gateway **adapter pattern** (নতুন gateway সহজে যোগ) | P0 | 0 |
| PAY-8 | Invoice PDF | P0 | 1 |

### 7.5 Order ও Fulfillment

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| ORD-1 | Lifecycle: `pending -> confirmed -> processing -> packed -> shipped -> delivered`; branch: `cancelled`, `returned`, `refunded`, `failed` | P0 | 1 |
| ORD-2 | State transition rule enforce (অবৈধ transition নিষিদ্ধ) ও history log | P0 | 1 |
| ORD-3 | Admin order list: filter, search, bulk status change, bulk print | P0 | 1 |
| ORD-4 | Packing slip, shipping label, invoice print | P0 | 1 |
| ORD-5 | **Courier integration** (Pathao/Steadfast/RedX): auto booking, tracking sync, COD reconciliation | P0 | 1 |
| ORD-6 | Courier adapter pattern | P0 | 0 |
| ORD-7 | Customer order tracking (status timeline), guest tracking by phone+order no. | P0 | 1 |
| ORD-8 | Partial shipment, partial cancel/return | P1 | 1 |
| ORD-9 | Return/exchange request ও approval flow | P0 | 1 |
| ORD-10 | Order notes (internal/customer), tags | P1 | 1 |
| ORD-11 | Manual order creation by admin (phone/Facebook order) | P0 | 1 |

### 7.6 Inventory

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| INV-1 | Variant-level stock, real-time decrement on order, restore on cancel/return | P0 | 1 |
| INV-2 | Stock reservation (checkout চলাকালীন) ও overselling প্রতিরোধ (DB lock/transaction) | P0 | 1 |
| INV-3 | Low stock alert, out-of-stock behavior (hide/show/pre-order) | P0 | 1 |
| INV-4 | Stock adjustment log (কে, কখন, কেন) | P0 | 1 |
| INV-5 | **Batch/lot ও expiry tracking** | P1 | 1 (honey) |
| INV-6 | FEFO (first-expire-first-out) allocation | P1 | 2 |
| INV-7 | Pre-order/backorder | P1 | 1 |
| INV-8 | Multi-warehouse/area-wise stock | P2 | 2 |
| INV-9 | Supplier ও purchase record | P2 | 3 |

### 7.7 Marketing ও Promotion

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| MKT-1 | Coupon: %, fixed, free shipping, first-order, usage limit, date range, min cart | P0 | 1 |
| MKT-2 | Auto discount/sale price scheduling, flash sale (timer) | P1 | 1 |
| MKT-3 | Facebook Pixel + Conversions API, GA4, GTM | P0 | 1 |
| MKT-4 | SMS/email campaign, newsletter | P1 | 2 |
| MKT-5 | Review & rating (verified purchase), photo review, admin moderation | P0 | 1 |
| MKT-6 | Wishlist, back-in-stock notify | P1 | 1 |
| MKT-7 | Loyalty points/wallet/cashback | P2 | 3 |
| MKT-8 | Referral/affiliate | P2 | 3 |
| MKT-9 | Subscription/repeat order | P2 | 3 |

### 7.8 Risk ও Fraud Control (BD-র জন্য গুরুত্বপূর্ণ)

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| FRD-1 | Phone/IP/device-ভিত্তিক order limit, blacklist | P0 | 1 |
| FRD-2 | Customer-wise delivery success/return rate score | P1 | 1 |
| FRD-3 | Courier success-rate check (বাইরের API, থাকলে) | P2 | 2 |
| FRD-4 | ঝুঁকিপূর্ণ order-এ advance payment বাধ্যতামূলক rule | P1 | 1 |
| FRD-5 | Duplicate order detection | P1 | 1 |

### 7.9 Customer Account ও Notification

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| CUS-1 | Register/login: phone OTP, email, social login | P0 | 1 |
| CUS-2 | Profile, address book, order history, reorder | P0 | 1 |
| CUS-3 | SMS notification (order placed/confirmed/shipped/delivered) | P0 | 1 |
| CUS-4 | Email notification; WhatsApp (optional) | P1 | 1 |
| CUS-5 | Notification template admin থেকে edit | P1 | 1 |
| CUS-6 | Support ticket/contact form, WhatsApp/Messenger chat link | P1 | 1 |

### 7.10 Admin ও Operation

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| ADM-1 | Dashboard: sales, order, revenue, top product, low stock, pending order | P0 | 1 |
| ADM-2 | Role-based access (Admin, Manager, Order handler, Content editor) | P0 | 0 |
| ADM-3 | Activity/audit log | P0 | 0 |
| ADM-4 | CMS: banner, homepage section (drag & drop), static page, menu, blog | P0 | 1 |
| ADM-5 | Report: sales, product, customer, coupon, payment, courier, tax; CSV export | P0 | 1 |
| ADM-6 | Settings: store info, currency, tax/VAT, delivery, payment, SMS, courier, SEO | P0 | 0 |
| ADM-7 | Customer management, segment | P1 | 1 |
| ADM-8 | Admin 2FA | P1 | 1 |
| ADM-9 | Onboarding wizard (store type বেছে preset apply) | P1 | 1 |

### 7.11 Theme ও Storefront UI

| ID | Requirement | Priority | Phase |
|---|---|---|---|
| THM-1 | Theme = folder, `theme.json` (settings schema), Tailwind design token (CSS variable) | P0 | 0 |
| THM-2 | Section-based homepage (Hero, CategoryGrid, ProductCarousel, Banner, Story, Testimonial, TrustBar) | P0 | 1 |
| THM-3 | **Theme: `honey-warm`** (organic, story-driven, বড় ছবি) | P0 | 1 |
| THM-4 | **Theme: `grocery-fresh`** (dense, fast, quick-add, quantity stepper) | P0 | 2 |
| THM-5 | Color/font/logo/header/footer style admin থেকে customize | P1 | 1 |
| THM-6 | Mobile-first, বাংলা + English toggle, বাংলা font | P0 | 1 |
| THM-7 | Theme-এ business logic থাকবে না | P0 | 0 |

---

## 8. Vertical Feature Matrix

| Feature | Core | Honey & Nuts (P1) | Grocery (P2) |
|---|:-:|:-:|:-:|
| Catalog, cart, checkout, order, payment, courier | ✅ | ✅ | ✅ |
| Weight variant (250g/500g/1kg) | | ✅ | ✅ |
| Batch & expiry tracking | | ✅ | ✅ (+FEFO) |
| Purity/origin/lab-test display | | ✅ | |
| Gift pack/combo | | ✅ | ✅ |
| Delivery slot | | | ✅ |
| Weight-based final billing | | | ✅ |
| Quantity stepper / quick add | | | ✅ |
| Per-unit price display | | | ✅ |
| Area-wise stock | | | ✅ |
| Repeat order / smart basket | | | ✅ |
| Subscription | | Phase 3 | Phase 3 |
| Return policy | Configurable | Sealed only | Damaged/missing only |

---

## 9. Non-Functional Requirements

| Area | Requirement |
|---|---|
| **Performance** | Mobile LCP < 2.5s; API p95 < 300ms (cached catalog), < 800ms (checkout); image WebP/CDN |
| **Scalability** | Sale-এ 10x traffic সামলানো: Redis cache, queue (Horizon), DB index |
| **Reliability** | 99.5%+ uptime; daily backup; restore test; staging environment |
| **Security** | HTTPS, hashed password, CSRF/XSS/SQLi protection, rate limit, signed webhook, admin 2FA, PCI scope gateway-তে রাখা, secrets শুধু `.env` |
| **Data integrity** | Order/stock-এ DB transaction ও locking; money integer (paisa) বা decimal, float নয় |
| **Observability** | Error tracking (Sentry), structured log, payment/courier failure alert, queue monitor |
| **SEO** | SSR storefront, clean slug, sitemap, schema.org Product markup, OG tag |
| **i18n** | বাংলা + English, Unicode সঠিক, BDT currency, বাংলা সংখ্যা optional |
| **Accessibility** | Contrast, alt text, keyboard navigable (basic WCAG AA) |
| **Compliance** | BD Digital Commerce guideline, VAT invoice, Privacy/Terms/Return policy page |
| **Maintainability** | Module structure, automated test (feature test প্রতি API), CI, code style |
| **Upgradability** | Per-client deployment-এ migration-safe update path |

---

## 10. Technical Constraints ও Architecture Principles

- **Laravel + MySQL**, Tailwind CSS, Redis, Queue worker, Scheduler।
- **Modular monolith:** Core + optional modules (`DeliverySlots`, `BatchExpiry`, `Subscription`...)। Disabled module-এর route/migration/menu active হবে না।
- **Service/Action layer** সব business logic রাখে; Controller পাতলা; API Resource দিয়ে output।
- **Adapter pattern:** Payment, Courier, SMS, Search, Storage।
- **Settings-driven:** hardcode নয়; DB `settings` + cache।
- **Store-scoped design:** ভবিষ্যতে multi-tenant সম্ভব রাখতে settings ও config-কে একটি "store" ধারণার সাথে যুক্ত রাখা (এখন একটি store)।
- **Event-driven side-effect:** order placed -> notification, inventory, analytics event/queue দিয়ে।
- **Money, tax, discount calculation** একটি মাত্র জায়গায় (Pricing service)।

---

## 11. Success Metrics

| Metric | Target (প্রথম ৩ মাস, honey-nuts client) |
|---|---|
| Checkout completion rate | > 45% of cart-started sessions |
| Mobile page speed (LCP) | < 2.5s |
| Order auto-booking with courier | > 90% manual ছাড়া |
| Overselling incident | 0 |
| COD return rate | নিচে নামানো (baseline ধরে ১০–১৫% কমানো) |
| Order processing time (confirm -> ship) | < 24 ঘণ্টা |
| API availability | > 99.5% |
| নতুন client onboarding (preset থেকে চালু) | < 1 দিন |

---

## 12. Risks

| Risk | প্রভাব | Mitigation |
|---|---|---|
| Over-engineering configurability | দেরি | আগে honey-nuts শেষ করুন; grocery-র সময়ে common টেনে বের করুন |
| Variant/attribute model ভুল | পরে ভাঙা কঠিন | Phase 0-তে schema review ও prototype |
| bKash/courier API বদল বা approval delay | সময় | Adapter pattern, sandbox আগে, merchant account আগে শুরু |
| COD fraud/return | লোকসান | Fraud rule MVP-তেই (FRD-1,4,5) |
| API ও web আলাদা logic | inconsistency | Service layer বাধ্যতামূলক, API-ই contract |
| Per-client deployment-এ upgrade ঝামেলা | maintenance | Semantic versioning, migration discipline, deploy script |
| Grocery-র জটিলতা (weight billing, slot) | Phase 2 delay | Phase 1-এ data model এমন রাখা যাতে এটা যোগ করা যায় |

---

## 13. Dependencies ও Assumptions

- Payment gateway merchant account ও courier API credential client থেকে/আগে নেওয়া হবে।
- SMS provider (BD local) বেছে নেওয়া হবে।
- Hosting: VPS/cloud, CDN, backup storage।
- প্রথম client-এর content (product ছবি, বর্ণনা, policy) client দেবে।
- Assumption: প্রথম client একটি দোকান, একটি warehouse, শুধু BD-তে delivery।

---

## 14. Open Questions

1. Storefront web (v1) কি Blade + Tailwind SSR, নাকি শুরু থেকেই আলাদা SPA? **সুপারিশ: Blade + Tailwind SSR (SEO), একই service layer।**
2. Admin panel কি custom Tailwind UI, নাকি Filament/Livewire দিয়ে দ্রুত? (Admin API থাকতেই হবে; UI client হিসেবে)
3. কোন payment aggregator (SSLCommerz/aamarPay) ও SMS provider?
4. Search: শুরুতে MySQL fulltext, নাকি Meilisearch?
5. প্রথম client-এর VAT/tax নিয়ম ও invoice format?
6. ভবিষ্যতে multi-tenant-এ যাওয়ার সম্ভাবনা কতটা, শুরুতেই `store_id` রাখব কি?
7. Return policy ও refund প্রক্রিয়ার client-নির্দিষ্ট নিয়ম?

---

## 15. Next Steps

1. এই PRD review ও open question-এর উত্তর
2. **SRS** লেখা: module-wise rule, state machine, ERD, API list, NFR বিস্তারিত
3. **Technical design:** Laravel module structure, settings/feature-flag, attribute schema, theme engine
4. Phase 0 কাজ ভাগ করে ticket বানানো
