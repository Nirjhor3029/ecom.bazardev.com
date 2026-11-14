# SRS: Configurable API-First Single-Vendor E-commerce Platform

| | |
|---|---|
| **Version** | Draft v0.1 |
| **Based on** | [PRD.md](PRD.md) (requirement ID যেমন `CAT-4`, `ORD-5` এখানে traceability-র জন্য ব্যবহৃত) |
| **Scope of this SRS** | Phase 0 (Foundation) + Phase 1 (Honey & Nuts) বিস্তারিত; Phase 2 (Grocery) extension point ও rule সহ |
| **Stack** | Laravel (সর্বশেষ stable LTS), MySQL 8, Redis, Tailwind CSS |

> **Assumptions (PRD open question-এর উত্তর না আসায় ধরে নেওয়া):** এগুলো বদলালে এই SRS-এর শুধু নির্দিষ্ট অংশ বদলাবে।
> - A1: Web storefront = Blade + Tailwind SSR, একই Service layer ব্যবহার করে।
> - A2: Admin UI একটি Admin API-র client; UI টেকনোলজি আলাদা সিদ্ধান্ত (SRS এতে নির্ভর করে না)।
> - A3: Search শুরুতে MySQL FULLTEXT, `SearchEngine` adapter রাখা হবে (পরে Meilisearch)।
> - A4: একটি store, একটি currency (BDT), কিন্তু `stores` table ও `store_id` রাখা হবে না; settings key-value দিয়ে global। (Multi-tenant পরে হলে migration লাগবে, গ্রহণযোগ্য ঝুঁকি।)
> - A5: Payment: একটি aggregator (SSLCommerz বা aamarPay) + COD; adapter pattern।

---

## 1. Introduction

### 1.1 উদ্দেশ্য
Developer ও QA-র জন্য সুনির্দিষ্ট আচরণ, নিয়ম, ডেটা মডেল ও API contract বর্ণনা করা।

### 1.2 Definitions
| শব্দ | অর্থ |
|---|---|
| **Store Type / Preset** | `honey_nuts`, `grocery`, `general`; default feature, attribute set, theme-এর সংগ্রহ |
| **Feature Flag** | নির্দিষ্ট feature on/off করার setting |
| **Attribute Set** | Product-এর জন্য attribute-এর তালিকা (যেমন Honey set) |
| **Variant** | Option-ভিত্তিক বিক্রয়যোগ্য একক (যেমন Honey 500g) |
| **SKU** | Variant-এর unique stock code |
| **Minor unit** | Money paisa-তে integer (৳১২৫.৫০ = 12550) |
| **Shipment** | একটি order-এর এক বা একাধিক courier consignment |

### 1.3 Actors
Guest, Customer, Admin (Super Admin), Manager, Order Handler, Content Editor, API Consumer (React/mobile), System (scheduler/queue), External (payment gateway, courier, SMS)।

---

## 2. System Architecture

### 2.1 Layering (বাধ্যতামূলক)
```
Client (Blade storefront / Admin UI / React / Mobile)
        │ HTTP JSON  (Blade storefront সরাসরি Action call করে)
┌───────▼─────────────────────────────┐
│ Http Layer: Controller + FormRequest │  শুধু validate, authorize, delegate
│            + API Resource            │  শুধু output shape
├──────────────────────────────────────┤
│ Application Layer: Actions/Services  │  সব business rule এখানে
├──────────────────────────────────────┤
│ Domain: Models, Enums, State machines│
├──────────────────────────────────────┤
│ Infra: Adapters (Payment, Courier,   │
│ SMS, Search, Storage), Repositories  │
└──────────────────────────────────────┘
```

### 2.2 Module structure (modular monolith)
```
app/Modules/
  Core/            (Settings, Features, Auth, Roles, Audit, Media)
  Catalog/         (Category, Brand, Product, Attribute, Variant, Bundle)
  Inventory/       (Stock, Reservation, Batch)
  Cart/
  Checkout/        (Order placement, Pricing, Coupon, Delivery charge)
  Orders/          (Order, State machine, Return, Shipment)
  Payments/        (Payment, Gateway adapters, Webhooks)
  Shipping/        (Courier adapters, Zones, Rates)
  Customers/       (Account, Address, Wishlist, Review)
  Marketing/       (Coupon, Banner, Pixel events, Campaign)
  Risk/            (Fraud rules, Blacklist)
  Notifications/   (SMS/Email templates)
  Cms/             (Page, Section, Menu, Blog, Theme)
  Reports/
  Optional (feature-flag, Phase 2+): DeliverySlots, WeightBilling, Subscription
```
- প্রতি module-এর নিজস্ব routes, migrations, policies, tests, `module.json` (name, version, requires, feature_flag)।
- **Optional module disabled হলে:** route register হবে না, menu দেখা যাবে না, scheduler job চলবে না, API 404 দেবে।

### 2.3 Cross-cutting rules
| ID | Rule |
|---|---|
| ARC-1 | Controller-এ business logic নিষিদ্ধ; Action class এক কাজ করে (`PlaceOrderAction`) |
| ARC-2 | Price/discount/tax/delivery charge গণনা শুধু `PricingService`-এ |
| ARC-3 | Order status পরিবর্তন শুধু `OrderStateMachine`-এর মাধ্যমে |
| ARC-4 | Stock পরিবর্তন শুধু `InventoryService`-এর মাধ্যমে (সবসময় `stock_movements` লগ সহ) |
| ARC-5 | বাইরের সিস্টেম (payment/courier/sms/search) interface-এর পেছনে; config দিয়ে driver নির্বাচন |
| ARC-6 | Side-effect (SMS, email, analytics, courier booking) Domain Event -> Queue Listener; request-এ synchronous নয় |
| ARC-7 | Money: `BIGINT` minor unit; কোথাও float নয় |
| ARC-8 | সব datetime UTC-তে সংরক্ষণ, display `Asia/Dhaka` |
| ARC-9 | Soft delete product/category/customer-এ; order, payment, stock_movement কখনো delete হয় না |

---

## 3. API Conventions (PRD API-1 … API-13)

### 3.1 Base ও Surface
| Surface | Prefix | Auth |
|---|---|---|
| Storefront (public) | `/api/v1/store/...` | none; rate limited |
| Storefront (customer) | `/api/v1/me/...` | Sanctum customer token |
| Admin | `/api/v1/admin/...` | Sanctum staff token + permission |
| Webhook | `/api/v1/webhooks/{provider}` | provider signature |

### 3.2 Request headers
`Accept: application/json`, `Accept-Language: bn|en`, `Authorization: Bearer <token>`, `Idempotency-Key: <uuid>` (POST checkout/payment/refund-এ বাধ্যতামূলক), `X-Cart-Token: <uuid>` (guest cart)।

### 3.3 Response envelope
Success:
```json
{ "data": { }, "meta": { "pagination": { "page":1,"per_page":20,"total":120,"last_page":6 } }, "links": { } }
```
Error:
```json
{ "error": { "code": "STOCK_INSUFFICIENT", "message": "…", "details": { "variant_id": 12, "available": 3 } } }
```
Validation (422):
```json
{ "error": { "code": "VALIDATION_FAILED", "message": "…", "fields": { "phone": ["…"] } } }
```
| HTTP | ব্যবহার |
|---|---|
| 200/201/204 | সফল |
| 401 / 403 | unauthenticated / unauthorized |
| 404 | পাওয়া যায়নি / disabled feature |
| 409 | conflict (idempotency, state transition, stock) |
| 422 | validation |
| 429 | rate limit |
| 5xx | server |

### 3.4 List endpoint standard
`?page=&per_page=(max 100)&sort=-created_at,price&filter[category]=…&filter[price_min]=…&include=variants,images&q=…`

### 3.5 Rate limits (default, configurable)
| Endpoint | Limit |
|---|---|
| OTP send | 3 / 10 min / phone, 10 / ঘণ্টা / IP |
| Login | 5 / min / IP+identifier |
| Checkout | 10 / min / IP |
| Search | 60 / min / IP |
| Public catalog | 120 / min / IP |

### 3.6 Versioning ও documentation
- Breaking change = নতুন `/v2`; `/v1` অন্তত ৬ মাস সমর্থিত, `Deprecation` ও `Sunset` header সহ।
- OpenAPI 3.1 spec auto-generated, `/api/docs` এ publish; CI-তে spec ও আসল response contract test।

---

## 4. Data Model

### 4.1 ERD (মূল entity)
```mermaid
erDiagram
  settings { string key PK string value json group string }
  attribute_sets ||--o{ attributes : has
  attribute_sets ||--o{ products : defines
  categories ||--o{ products : contains
  brands ||--o{ products : has
  products ||--o{ product_variants : has
  products ||--o{ product_media : has
  products }o--o{ attribute_values : "described by"
  product_variants ||--|| inventory_items : tracks
  inventory_items ||--o{ inventory_batches : "lots"
  inventory_items ||--o{ stock_movements : logs
  inventory_items ||--o{ stock_reservations : holds
  customers ||--o{ addresses : has
  customers ||--o{ orders : places
  carts ||--o{ cart_items : has
  orders ||--o{ order_items : has
  orders ||--o{ order_status_history : logs
  orders ||--o{ payments : paid_by
  orders ||--o{ shipments : shipped_as
  orders ||--o{ order_returns : may_have
  coupons ||--o{ coupon_redemptions : used
  orders ||--o{ coupon_redemptions : applies
  shipments ||--o{ shipment_items : contains
  users ||--o{ activity_logs : performs
  products ||--o{ reviews : receives
```

### 4.2 Table বিবরণ (মূল column)

**Core**
| Table | মূল column |
|---|---|
| `users` (staff) | id, name, email, phone, password, is_active, last_login_at; role via spatie-style `roles/permissions` |
| `settings` | `key` (unique), `value` (JSON), `group`, `is_public` (storefront bootstrap-এ দেখানো যাবে কি না), timestamps |
| `feature_flags` | `key`, `enabled` (bool), `source` (`preset|admin`), `config` JSON |
| `activity_logs` | actor_type/id, action, subject_type/id, before JSON, after JSON, ip, user_agent, created_at |
| `media` | id, disk, path, mime, size, width, height, alt, created_by |

**Catalog**
| Table | মূল column |
|---|---|
| `categories` | id, parent_id, name(i18n JSON), slug (unique), image_id, sort, is_active, seo JSON |
| `brands` | id, name, slug, logo_id |
| `attribute_sets` | id, code (`honey`,`grocery_item`), name |
| `attributes` | id, attribute_set_id, code, label(i18n), type (`text|number|select|multiselect|date|boolean`), options JSON, unit, `is_variant`, `is_filterable`, `is_required`, `show_on_pdp`, sort |
| `products` | id, attribute_set_id, category_id, brand_id, name(i18n), slug, short_desc, description(i18n), status (`draft|active|archived`), type (`simple|variable|bundle`), `attributes` JSON (non-variant attribute value), tags, seo JSON, published_at, deleted_at |
| `product_variants` | id, product_id, sku (unique), options JSON (`{"weight":"500g"}`), price (minor), sale_price, sale_starts_at, sale_ends_at, cost_price, weight_grams, barcode, is_default, is_active, sort |
| `product_media` | product_id, variant_id?, media_id, sort, is_primary |
| `bundle_items` | bundle_product_id, variant_id, qty |
| `product_relations` | product_id, related_id, type (`related|upsell|cross_sell`) |

**Inventory**
| Table | মূল column |
|---|---|
| `inventory_items` | variant_id (unique), `on_hand`, `reserved`, low_stock_threshold, track_stock (bool), backorder_policy (`deny|allow|preorder`) ; `available = on_hand - reserved` (computed) |
| `inventory_batches` | id, inventory_item_id, batch_no, qty_on_hand, mfg_date, expiry_date, cost, received_at |
| `stock_movements` | id, inventory_item_id, batch_id?, type (`receive|sale|ship|cancel_restore|return_restore|adjust|damage`), qty (signed), reference_type/id, reason, actor_id, created_at (immutable) |
| `stock_reservations` | id, inventory_item_id, order_id?, cart_id?, qty, expires_at, status (`active|committed|released|expired`) |

**Cart ও Order**
| Table | মূল column |
|---|---|
| `carts` | id, token (uuid), customer_id?, coupon_code?, currency, expires_at |
| `cart_items` | cart_id, variant_id, qty, unit_price_snapshot |
| `customers` | id, name, phone (unique, normalized `+8801XXXXXXXXX`), email?, password?, phone_verified_at, is_blocked, risk_score, total_orders, delivered_orders, returned_orders |
| `addresses` | id, customer_id?, name, phone, division_id, district_id, upazila_id, area, address_line, postcode?, is_default |
| `geo_divisions/districts/upazilas` | BD location master (seeded) |
| `orders` | id, order_no (unique, e.g. `ORD-2611-000123`), customer_id?, guest snapshot (name/phone/email), `status`, `payment_status`, `fulfillment_status`, subtotal, discount_total, delivery_fee, tax_total, grand_total, paid_total, due_total, currency, payment_method, coupon_code, shipping_address JSON (snapshot), billing JSON?, note_customer, note_internal, source (`web|admin|api|app`), risk_flags JSON, ip, user_agent, placed_at, idempotency_key (unique) |
| `order_items` | id, order_id, product_id, variant_id, name_snapshot, sku, options JSON, unit_price, qty, discount, tax, line_total, weight_grams, batch_id? |
| `order_status_history` | order_id, from, to, actor_type/id, note, created_at |

**Payment / Shipping / Returns**
| Table | মূল column |
|---|---|
| `payments` | id, order_id, method (`cod|bkash|nagad|rocket|card|…`), gateway, status (`initiated|pending|paid|failed|cancelled|refunded|partially_refunded`), amount, currency, gateway_txn_id, gateway_ref, payload JSON, paid_at |
| `payment_refunds` | id, payment_id, amount, reason, status, gateway_ref, actor_id |
| `webhook_events` | id, provider, event_id (unique per provider), payload, signature_valid, processed_at, status |
| `shipping_zones` | id, name, match (districts/upazilas JSON) |
| `shipping_rates` | id, zone_id, method, min_weight, max_weight, min_subtotal, max_subtotal, fee, est_days_min, est_days_max, free_over |
| `shipments` | id, order_id, courier, consignment_id, tracking_code, status, cod_amount, cod_collected, fee, label_url, booked_at, delivered_at, payload |
| `shipment_items` | shipment_id, order_item_id, qty |
| `order_returns` | id, order_id, type (`return|exchange|damaged|missing`), reason, status (`requested|approved|rejected|received|refunded|closed`), items JSON, media, refund_amount, actor_id |

**Marketing / Customer / Risk / CMS**
| Table | মূল column |
|---|---|
| `coupons` | code (unique), type (`percent|fixed|free_shipping|bogo`), value, max_discount, min_cart, starts_at, ends_at, usage_limit, per_customer_limit, first_order_only, applies_to JSON, is_active |
| `coupon_redemptions` | coupon_id, order_id, customer_id?, phone, amount |
| `reviews` | id, product_id, customer_id, order_item_id (verified), rating 1-5, title, body, media, status (`pending|approved|rejected`) |
| `wishlists` | customer_id, product_id |
| `stock_alerts` | variant_id, phone/email, notified_at |
| `risk_rules` / `blacklist` | type (`phone|ip|device|address`), value, reason, expires_at |
| `notification_templates` | key, channel, locale, subject, body, is_active |
| `notification_logs` | channel, to, template, status, provider_ref, error |
| `cms_pages`, `cms_menus`, `cms_sections` | page, slug, `sections` JSON (type + settings + sort), `theme_id` |
| `banners` | id, placement, image, link, starts_at, ends_at, sort |

### 4.3 Index/Constraint গুরুত্বপূর্ণ
- `product_variants.sku` unique; `orders.order_no`, `orders.idempotency_key` unique; `webhook_events (provider, event_id)` unique।
- `inventory_items.variant_id` unique; `CHECK (reserved >= 0)`, `CHECK (on_hand >= 0)` যদি backorder deny।
- Search: `products (name, short_desc)` FULLTEXT; `categories.slug`, `products.slug` unique।
- `customers.phone` unique।

---

## 5. Functional Requirements (বিস্তারিত)

### 5.1 Settings, Feature Flag ও Preset (CFG-1…5)

**FR-CFG-1 Settings resolution**
1. `Setting::get($key)` প্রথমে Redis cache, না পেলে DB `settings`, না পেলে preset default, না পেলে `config/` default।
2. Admin save করলে DB আপডেট + cache key invalidate + `SettingChanged` event।
3. `is_public = true` key গুলো `GET /store/bootstrap`-এ যাবে (store name, logo, currency, theme tokens, enabled feature list, locale, contact, payment method list)। Secret কখনো public নয়।

**FR-CFG-2 Preset apply**
- `php artisan store:install --type=honey_nuts` (ও admin onboarding wizard) নিচের কাজ idempotent ভাবে করবে: attribute set তৈরি, feature flag default, theme সেট, default shipping zone, default notification template, default CMS section, default risk rule।
- Preset `database/presets/{type}.php` (বা JSON); version আছে। পুনরায় apply করলে admin-এর পরিবর্তন overwrite করবে না (merge, `source=admin` রক্ষা)।

**FR-CFG-3 Feature flags (Phase 1 তালিকা)**
| Key | Honey | Grocery | Description |
|---|:-:|:-:|---|
| `batch_expiry` | on | on | Batch/expiry tracking |
| `delivery_slots` | off | on | Delivery slot |
| `weight_billing` | off | on | Final weight billing |
| `preorder` | on | off | Pre-order |
| `reviews` | on | on | |
| `wishlist` | on | on | |
| `coupons` | on | on | |
| `bundles` | on | on | |
| `quick_add` | off | on | Listing-এ quantity stepper |
| `guest_checkout` | on | on | |
| `cod` | on | on | |
| `online_payment` | on | on | |
| `abandoned_cart` | off(P2) | on | |

**FR-CFG-4** Feature flag off হলে: relevant API 404 (`error.code=FEATURE_DISABLED`), bootstrap-এ flag false, admin menu hidden।

**FR-CFG-5** Terminology: `lang/{locale}/store.php` + `settings` override (`terms.add_to_cart` ইত্যাদি) -> `GET /store/bootstrap`-এ `terms` object।

**Acceptance:** `STORE_TYPE=honey_nuts` দিয়ে fresh install করলে admin panel-এ Honey attribute set, honey theme ও উপরের flag সেট; একটি flag admin থেকে বদলালে ১ সেকেন্ডের মধ্যে API-তে প্রতিফলিত।

### 5.2 Auth ও Roles (ADM-2, CUS-1)

**Customer auth**
| Flow | Rule |
|---|---|
| OTP register/login | `POST /store/auth/otp/send {phone}` -> 6-digit, validity 5 মিনিট, max 3 attempt verify, 3 send/10min। Verify সফল হলে customer upsert + token। |
| Email+password (optional) | phone অবশ্যই verify করতে হবে (COD ঝুঁকির কারণে) |
| Social login | Google/Facebook; phone পরে চাওয়া হবে প্রথম checkout-এ |
| Phone normalize | `01XXXXXXXXX`, `8801…`, `+8801…` -> `+8801XXXXXXXXX`; operator prefix validate (013–019) |
| Token | Sanctum; expiry configurable (default 30 দিন, mobile refresh) |
| Guest-> customer | Guest order-এর phone দিয়ে পরে account verify করলে পুরনো order যুক্ত হবে |

**Staff auth:** email+password, optional 2FA (TOTP, ADM-8), brute-force lock (5 ব্যর্থ -> 15 মিনিট)।

**Roles/Permissions (default):**
| Role | Permission সারসংক্ষেপ |
|---|---|
| Super Admin | সব |
| Manager | সব ব্যতীত settings/roles/payment key |
| Order Handler | order view/update status/print/shipment, customer view; price/product edit নয় |
| Content Editor | product, category, CMS, banner, blog, review moderate; order নয় |
Permission নাম `module.resource.action` (যেমন `orders.order.update_status`)। সব admin API endpoint policy দিয়ে সুরক্ষিত।

### 5.3 Catalog (CAT-1…7)

**FR-CAT-1 Category:** multi-level (সর্বোচ্চ ৪ স্তর), ডিলিটে সন্তান বা product থাকলে বাধা; slug auto-generate, বাংলা নাম থেকেও transliterate/ID fallback।

**FR-CAT-2 Product validation**
- `name`, `category_id`, `attribute_set_id`, অন্তত ১টি variant, অন্তত ১টি image (active করতে)।
- `status=active` করতে হলে: সব `is_required` attribute পূরণ, default variant আছে, price > 0।
- Slug unique; পরিবর্তন করলে পুরনো slug 301 redirect table-এ।

**FR-CAT-3 Attribute system**
- `is_variant=true` attribute (যেমন weight) variant `options`-এ থাকে; বাকিগুলো `products.attributes` JSON-এ।
- `is_filterable` attribute থেকে filter facet auto তৈরি: `GET /store/products/filters?category=…` ফেরত দেয় facet (attribute code, label, type, options+count)।
- `show_on_pdp` attribute গুলো spec তালিকায় order অনুযায়ী যায়।
- Attribute type পরিবর্তন করা যাবে না যদি value থাকে।

**Honey preset attribute set (`honey`)**
| code | label | type | variant | filterable | required |
|---|---|---|:-:|:-:|:-:|
| `weight` | ওজন | select (250g,500g,1kg…) | ✅ | ✅ | ✅ |
| `origin` | উৎস/অঞ্চল (সুন্দরবন, মৌলভীবাজার…) | select | | ✅ | |
| `honey_type` | মধুর ধরন (খলিশা, সরিষা, লিচু…) | select | | ✅ | |
| `purity_cert` | ল্যাব টেস্ট/সার্টিফিকেট | boolean + media | | | |
| `harvest_date` | সংগ্রহ তারিখ | date | | | |
| `shelf_life_months` | মেয়াদ (মাস) | number | | | |
| `ingredients` | উপাদান | text | | | |
| `storage` | সংরক্ষণ নির্দেশনা | text | | | |
| `nut_type` | বাদামের ধরন (Nuts-এর জন্য) | select | | ✅ | |
| `is_organic` | অর্গানিক | boolean | | ✅ | |

**FR-CAT-4 Variant**
- Product type `variable` হলে variant option combination unique; `simple` হলে ঠিক ১টি implicit variant।
- Price `sale_price < price` এবং sale window বৈধ হলে effective price = sale price (Pricing service নির্ধারণ করে)।
- Variant delete: যদি order_items-এ ব্যবহৃত, সেক্ষেত্রে `is_active=false` (soft)।

**FR-CAT-5 Bulk import/export:** CSV/XLSX; async job; ফলাফল রিপোর্ট (সারি-wise error); dry-run mode; SKU দিয়ে upsert; ছবি URL থেকে fetch (queued)।

**FR-CAT-6 Bundle:** bundle product `type=bundle`; উপাদান variant-এর stock থেকে available = min(component available / qty); order-এ bundle component হিসেবে stock কমে; bundle price নির্দিষ্ট (সংযোজনের চেয়ে কম হতে পারে)।

**FR-CAT-7 Relations:** related/upsell/cross-sell manual set; fallback: একই category-র best seller।

**Storefront API (catalog)**
| Method & Path | বিবরণ |
|---|---|
| `GET /store/bootstrap` | store config, flag, theme token, menu, terms |
| `GET /store/categories` | tree |
| `GET /store/products` | list+filter+sort+search+pagination |
| `GET /store/products/filters` | facet |
| `GET /store/products/{slug}` | detail (variants, attribute, media, related, rating summary, availability) |
| `GET /store/products/{slug}/reviews` | |
| `GET /store/brands` | |
| `GET /store/collections/{slug}` | (best sellers, new, deals) |
| `GET /store/search/suggest?q=` | autocomplete |

**Product API response (summary):**
```json
{ "data": { "id": 10, "slug": "sundarban-khalisha-honey", "name": "সুন্দরবনের খলিশা মধু",
  "price_range": {"min": 45000, "max": 160000}, "rating": {"avg": 4.7, "count": 128},
  "attributes": [{"code":"origin","label":"উৎস","value":"সুন্দরবন"}],
  "variants": [{"id":55,"sku":"HNY-KH-500","options":{"weight":"500g"},
    "price":80000,"sale_price":72000,"effective_price":72000,
    "availability":{"status":"in_stock","qty_available":42,"max_per_order":10}}],
  "media": [], "seo": {} } }
```
`availability.status` ∈ `in_stock | low_stock | out_of_stock | preorder`; সঠিক সংখ্যা প্রকাশ configurable (`show_exact_stock`)।

### 5.4 Search (SRC-1…5)
- FR-SRC-1: `q` বাংলা ও English সমর্থিত; নাম, SKU, tag, category নাম, attribute value-তে অনুসন্ধান; MySQL FULLTEXT + `LIKE` fallback ছোট query-তে।
- FR-SRC-2: Filter facet প্রতিটি `is_filterable` attribute থেকে; price range; rating; in-stock only; sort: `relevance|price_asc|price_desc|newest|best_selling|rating`।
- FR-SRC-3: `SearchEngine` interface (`MysqlSearch` default, `MeilisearchSearch` পরে)।
- FR-SRC-4: Autocomplete সর্বোচ্চ ৮ ফলাফল, 150ms debounce client-এ; zero-result query লগ হবে (admin report)।

### 5.5 Cart (CHK-1)

**Rules**
| ID | Rule |
|---|---|
| CRT-1 | Guest cart `X-Cart-Token` দিয়ে; login করলে merge (qty যোগ, সীমা মেনে) |
| CRT-2 | `POST /store/cart/items {variant_id, qty}`: variant active ও product active হতে হবে; `qty` ≥1; max per order (default 10, per-variant override) |
| CRT-3 | Add/update-এ stock check; backorder/pre-order policy অনুযায়ী অনুমোদন; অপর্যাপ্ত হলে 409 `STOCK_INSUFFICIENT` |
| CRT-4 | Cart response সবসময় **server-computed pricing** (subtotal, discount, delivery estimate, total)। Client price পাঠালেও উপেক্ষিত |
| CRT-5 | Price পরিবর্তন হলে cart response-এ `price_changed: true` warning |
| CRT-6 | Cart inactive ৩০ দিন পর expire (scheduler) |
| CRT-7 | Cart add-এ stock **reserve করে না**; reserve হয় শুধু checkout শুরু/order place-এ |

**Endpoints:** `GET /store/cart`, `POST /store/cart/items`, `PATCH /store/cart/items/{id}`, `DELETE /store/cart/items/{id}`, `POST /store/cart/coupon`, `DELETE /store/cart/coupon`, `POST /store/cart/merge`.

### 5.6 Pricing (ARC-2) — একক সত্য

`PricingService::calculate(CartContext)` ক্রম:
1. Line price = variant effective price × qty।
2. Bundle/volume rule (থাকলে)।
3. Coupon/auto-discount প্রয়োগ (নিচে 5.12)।
4. Delivery fee নির্ধারণ (zone, weight, subtotal, free-over)।
5. Tax/VAT (setting: `tax.inclusive` true/false, rate; inclusive হলে price-এর ভেতর ধরা, শুধু invoice-এ breakdown)।
6. Rounding: প্রতি line পূর্ণ paisa; মোট ৳১ এ round **না** (setting `rounding=none|nearest_taka`)।
7. আউটপুট: `subtotal, discount_total, delivery_fee, tax_total, grand_total` + line-wise breakdown + `applied_rules[]`।

Order place-এ pricing **পুনরায়** গণনা হয় এবং snapshot order-এ সংরক্ষিত; client-দেওয়া `expected_total` মিলতে হবে, নাহলে 409 `PRICE_CHANGED` (নতুন total সহ)।

### 5.7 Checkout (CHK-2…7)

**Flow (API)**
1. `POST /store/checkout/quote` — {address, delivery_method?, payment_method?, coupon?} -> pricing, available delivery/payment options, delivery estimate (stateless, cacheable নয়)।
2. `POST /store/checkout/place` — header `Idempotency-Key`; body: customer/guest info, address, payment_method, coupon, note, `expected_total`, `otp_token` (guest হলে verified phone proof)।
3. COD হলে -> order `pending` (বা auto-confirm setting) ফেরত। Online payment হলে -> order `pending`, payment `initiated`, response-এ `payment.redirect_url`/`payment.session`।
4. Gateway থেকে callback/webhook -> payment `paid` -> order `confirmed` (বা আগের ধাপ)।
5. `GET /store/orders/{order_no}?phone=` (guest) বা `/me/orders/{no}` -> status।

**Rules**
| ID | Rule |
|---|---|
| CHK-R1 | Phone বাধ্যতামূলক ও verified (OTP) guest-এও (setting: `checkout.require_phone_verification=true`) |
| CHK-R2 | Address-এ division/district/upazila + address_line বাধ্যতামূলক; delivery zone মিললেই কেবল place করা যাবে, নাহলে 422 `AREA_NOT_SERVICEABLE` |
| CHK-R3 | Place-এর transaction: (a) lock inventory rows (`SELECT … FOR UPDATE`), (b) available যাচাই, (c) reservation/decrement, (d) pricing snapshot, (e) order+items+history লিখ, (f) coupon redemption, (g) commit। যেকোনো ব্যর্থতায় rollback, কিছুই কমবে না |
| CHK-R4 | Idempotency: একই key পুনরায় এলে প্রথম response হুবহু (24 ঘণ্টা retention); একই key ভিন্ন payload -> 422 |
| CHK-R5 | Risk check (5.15) ফলাফল অনুযায়ী: `allow`, `require_advance`, `hold_for_review`, `block` |
| CHK-R6 | Max COD amount (setting) অতিক্রম করলে COD বন্ধ, online বাধ্যতামূলক |
| CHK-R7 | Min order amount (setting, default 0) |
| CHK-R8 | Order number sequential-সদৃশ, কিন্তু অনুমান করা কঠিন নয়, তাই guest tracking-এ phone ও order_no দুটোই লাগবে |
| CHK-R9 | Successful place -> `OrderPlaced` event -> SMS, email, analytics (server-side Pixel/CAPI purchase), courier-পূর্ব-কাজ |

### 5.8 Payment (PAY-1…8)

**Payment methods config:** admin settings-এ প্রতি method enable/disable, নাম, ছবি, min/max, applicable delivery zone।

**Gateway adapter interface:**
```php
interface PaymentGateway {
  public function initiate(Order $order, Payment $payment): InitiationResult; // redirect_url / session
  public function verify(array $payload): VerificationResult;                  // server-to-server verify
  public function refund(Payment $payment, int $amount, string $reason): RefundResult;
  public function parseWebhook(Request $req): ?WebhookEvent;                   // signature verify সহ
}
```

**Rules**
| ID | Rule |
|---|---|
| PAY-R1 | Payment status শুধু gateway **server-to-server verify**-এর পর `paid`; client redirect-এর success param বিশ্বাসযোগ্য নয় |
| PAY-R2 | Webhook endpoint: signature/IP যাচাই, `webhook_events` -এ (provider,event_id) unique দিয়ে idempotent, প্রক্রিয়া queue-এ, সবসময় দ্রুত 200 |
| PAY-R3 | Amount mismatch (paid ≠ expected) -> payment `paid` নয়, order `payment_review` flag, admin alert |
| PAY-R4 | Online payment `initiated` ১৫ মিনিটে (setting) paid না হলে: stock reservation release, order `cancelled` (reason `payment_timeout`) — যদি না gateway-তে late success আসে; late success এলে re-reserve চেষ্টা, ব্যর্থ হলে refund queue + admin alert |
| PAY-R5 | COD: order `delivered` ও courier COD collection confirm-এ payment `paid` |
| PAY-R6 | Advance + COD (partial): `paid_total < grand_total`, `due_total` = COD collect amount, courier-এ `cod_amount = due_total` |
| PAY-R7 | Refund: admin `POST /admin/payments/{id}/refunds`; gateway সমর্থিত হলে auto, নাহলে manual mark; refund ≤ paid − already_refunded |
| PAY-R8 | Payment retry: ব্যর্থ হলে নতুন `payments` row, order একই থাকে (সর্বোচ্চ ৩ বার/order) |
| PAY-R9 | সব gateway payload `payments.payload`-এ সংরক্ষণ (PII mask করে লগে) |

**Payment status machine:** `initiated -> pending -> paid`; `initiated|pending -> failed|cancelled`; `paid -> partially_refunded -> refunded`। COD: `pending (due) -> paid`।

**Endpoints:** `POST /store/orders/{no}/pay` (retry), `GET /store/payments/return/{provider}` (browser landing), `POST /webhooks/{provider}`, admin: `GET /admin/payments`, `POST /admin/payments/{id}/refunds`, `POST /admin/orders/{id}/mark-paid` (manual, audit লগ সহ)।

### 5.9 Order ও State Machine (ORD-1…11)

**Order status:** `pending`, `confirmed`, `processing`, `packed`, `shipped`, `delivered`, `cancelled`, `returned`, `failed`, `on_hold`।
**Payment status:** `unpaid`, `partial`, `paid`, `refunded`, `partially_refunded` (order-এর summary; payments table থেকে derive)।
**Fulfillment status (derived):** `unfulfilled`, `partial`, `fulfilled`।

**Allowed transitions**
| From | To | কে | শর্ত/প্রভাব |
|---|---|---|---|
| (new) | `pending` | system | order placed; stock reserved |
| `pending` | `confirmed` | staff / auto | COD: ফোন কনফার্ম বা auto-confirm setting; online: paid হলে auto |
| `pending` / `confirmed` | `on_hold` | staff/system(risk) | কারণ বাধ্যতামূলক; reservation থাকে (timeout setting) |
| `on_hold` | `confirmed` / `cancelled` | staff | |
| `confirmed` | `processing` | staff | picking শুরু |
| `processing` | `packed` | staff | সব item-এর batch নির্ধারিত (batch_expiry on হলে FEFO বা manual) |
| `packed` | `shipped` | staff/system | shipment তৈরি ও courier consignment আছে; reservation -> stock deduct (`on_hand--`, `reserved--`, movement `ship`) |
| `shipped` | `delivered` | courier webhook/staff | COD হলে `payment` paid; delivered_at |
| `shipped` | `returned` (RTO) | courier webhook/staff | stock restore সিদ্ধান্ত অনুযায়ী (`return_restore` বা `damage`) |
| `pending/confirmed/processing/packed` | `cancelled` | staff/customer(সীমিত) | reservation release; paid হলে refund flow trigger; coupon redemption revert (setting) |
| `delivered` | `returned` | staff | return request approve + received হলে (partial হতে পারে) |
| any non-terminal | `failed` | system | payment timeout/ফ্রড block |

Terminal: `cancelled`, `returned` (full), `failed`। অন্য কোনো transition 409 `INVALID_TRANSITION`। প্রতিটি transition `order_status_history`-তে (actor, note) এবং `OrderStatusChanged` event।

**Customer cancel rule:** শুধু `pending`/`confirmed` অবস্থায় এবং courier handover-এর আগে; setting `orders.customer_can_cancel_until=confirmed`।

**Admin order features**
| ID | Requirement |
|---|---|
| ORD-F1 | List: filter (status, payment, date, courier, district, risk flag, source), search (order_no, phone, name), sort, saved filter, CSV export |
| ORD-F2 | Bulk action: confirm, cancel, send to courier, print invoice/label/packing slip (PDF merged) |
| ORD-F3 | Order edit: confirmed-এর আগে item/qty/address/phone/delivery fee সম্পাদনযোগ্য (pricing পুনর্গণনা, stock সমন্বয়, history লগ); পরে শুধু note/address (courier-এর আগে) |
| ORD-F4 | Manual order (phone/Facebook): `POST /admin/orders` — customer খুঁজে/তৈরি, item, discount (permission-সাপেক্ষ), payment method, `source=admin` |
| ORD-F5 | Internal note + customer-visible note; tag |
| ORD-F6 | Duplicate order warning (একই phone + একই item ২৪ ঘণ্টায়) |
| ORD-F7 | Partial shipment: একাধিক shipment, item-wise; fulfillment status derive |

**Return/Exchange (ORD-9)**
- Customer: `POST /me/orders/{no}/returns {items[], reason, media[]}` — শুধু `delivered`-এর পর N দিনের মধ্যে (setting `returns.window_days`, honey default 3, sealed-only শর্ত, disclaimer)।
- Policy per category/product: `returnable` boolean (honey: খোলা জার returnable নয়; damaged/wrong item ছাড়া)।
- Admin flow: `requested -> approved|rejected -> received -> refunded|exchanged -> closed`। Approve হলে reverse pickup booking (courier adapter সমর্থন করলে) নতুবা manual।
- Received-এ inventory সিদ্ধান্ত: `restock` (batch/qty) বা `damage` -> stock_movements।
- Refund: original method-এ (online) বা manual (bKash/bank নম্বর নিয়ে, COD হলে)।

### 5.10 Shipping ও Courier (CHK-4, ORD-5,6)

**Delivery charge rule (data-driven):** `shipping_zones` (district/upazila সমষ্টি) × `shipping_rates` (weight-slab, subtotal-slab, fee, free_over, est_days)। প্রাধান্য: সবচেয়ে নির্দিষ্ট zone -> default zone। Order-এ একটিই delivery fee; কোনো zone না মিললে `AREA_NOT_SERVICEABLE`।

Example default (Honey preset): ঢাকা মহানগর ৳৬০ (1–2 দিন), ঢাকার বাইরে ৳১২০ (2–4 দিন), ৳১০০০+ কেনাকাটায় ঢাকায় free (configurable)।

**Courier adapter interface:**
```php
interface Courier {
  public function book(Shipment $s): BookingResult;           // consignment_id, tracking
  public function cancel(Shipment $s): bool;
  public function track(Shipment $s): TrackingResult;
  public function label(Shipment $s): ?string;
  public function parseWebhook(Request $r): ?CourierEvent;
  public function codReconcile(DateRange $r): array;
}
```
- Drivers: `steadfast`, `pathao`, `redx` (Phase 1: অন্তত একটি পূর্ণ; অন্যগুলো interface-ready)।
- Zone/city/area ID mapping (Pathao-র city/zone) cache ও sync job।

**Rules**
| ID | Rule |
|---|---|
| SHP-R1 | Auto-book setting: order `packed` হলে courier booking queue-এ; ব্যর্থ হলে retry (exponential, ৩ বার) তারপর admin alert + manual |
| SHP-R2 | COD amount = order `due_total` |
| SHP-R3 | Courier webhook/poll status -> normalized (`picked_up`, `in_transit`, `delivered`, `returned`, `failed`, `hold`)। `delivered` -> order delivered; `returned` -> RTO flow |
| SHP-R4 | Poll fallback scheduler প্রতি ৩০ মিনিটে (webhook মিস হলে) |
| SHP-R5 | COD reconciliation: courier-র settlement report import/pull -> `shipments.cod_collected` মেলানো; অমিল রিপোর্ট |
| SHP-R6 | Label/waybill PDF স্টোর ও print |
| SHP-R7 | Tracking public endpoint: `GET /store/orders/{no}/tracking?phone=` (timeline: placed, confirmed, packed, shipped, out for delivery, delivered) |

### 5.11 Inventory (INV-1…9)

**Quantities:** `on_hand` (ফিজিক্যাল), `reserved` (অর্ডার-এ আটকানো, এখনো shipped নয়), `available = on_hand − reserved`।

**State/Movement rules**
| Event | on_hand | reserved | movement type |
|---|:-:|:-:|---|
| Order placed | — | +qty | (reservation row `active`) |
| Order cancelled (before ship) | — | −qty | reservation `released` |
| Shipped | −qty | −qty | `ship` (reservation `committed`) |
| Return received & restock | +qty | — | `return_restore` |
| Return received & damaged | — | — | `damage` (লগ মাত্র) |
| Manual adjust | ±qty | — | `adjust` (reason বাধ্যতামূলক) |
| Purchase receive | +qty | — | `receive` (batch সহ) |

| ID | Rule |
|---|---|
| INV-R1 | সব পরিবর্তন DB transaction + row lock; `stock_movements` immutable লগ |
| INV-R2 | `track_stock=false` হলে (যেমন সেবা/সবসময় আছে) কোনো stock check নয় |
| INV-R3 | `backorder_policy`: `deny` (default), `allow` (negative available), `preorder` (PDP-তে preorder badge, আলাদা expected_date) |
| INV-R4 | Low stock: `available ≤ threshold` হলে `LowStock` event -> admin dashboard + notification (দিনে একবার digest) |
| INV-R5 | Out of stock হলে `stock_alerts` সাবস্ক্রাইবারকে restock-এ notify |
| INV-R6 | Online payment pending reservation ১৫ মিনিট TTL (PAY-R4); expiry job প্রতি মিনিটে |
| INV-R7 | **Batch/Expiry (`batch_expiry` on):** receive-এ batch_no, mfg/expiry বাধ্যতামূলক; allocation FEFO (সবচেয়ে কাছের expiry আগে, কিন্তু min remaining shelf-life setting এর কম হলে এড়িয়ে); batch-wise on_hand; expired batch auto `unavailable` (stock count থেকে বাদ) ও admin alert, নির্দিষ্ট দিন আগে near-expiry report |
| INV-R8 | Batch allocation order item-এ `batch_id` সংরক্ষণ (traceability/recall) |
| INV-R9 | Multi-warehouse (INV-8) Phase 2: `warehouse_id` যোগ, এখন schema-তে extension point (নেই) |

**Admin Inventory API:** `GET /admin/inventory`, `POST /admin/inventory/{variant}/adjust`, `POST /admin/inventory/receive`, `GET /admin/inventory/movements`, `GET /admin/inventory/low-stock`, `GET /admin/inventory/expiring`, bulk update (CSV)।

### 5.12 Coupons ও Promotion (MKT-1,2)

| ID | Rule |
|---|---|
| CPN-R1 | Coupon type: `percent` (+ max_discount), `fixed`, `free_shipping`, `bogo` (Phase 1 optional) |
| CPN-R2 | শর্ত: active window, min_cart, usage_limit (মোট), per_customer_limit (phone/ customer অনুযায়ী), first_order_only, নির্দিষ্ট category/product/brand, বাদ দেওয়া sale item |
| CPN-R3 | Stacking: default একটি coupon; setting `coupons.allow_stacking` |
| CPN-R4 | Auto-discount (rule-based, কুপন ছাড়া) ও coupon একত্রে কীভাবে কাজ করবে priority field দিয়ে |
| CPN-R5 | Redemption order place-এ atomic (race-safe `usage_count` update); order cancel হলে revert (setting) |
| CPN-R6 | Discount কখনো subtotal-এর বেশি নয়, grand_total ≥ 0 |
| CPN-R7 | Flash sale: variant sale price + window; countdown-এর জন্য `sale_ends_at` API-তে |

### 5.13 Customer: Account, Wishlist, Review, Notification

**Account API (`/me`):** profile, addresses CRUD (default), orders list/detail, reorder (`POST /me/orders/{no}/reorder` -> cart-এ available item যোগ, unavailable সম্পর্কে report), wishlist, returns, reviews।

**Review rules (MKT-5):** শুধু `delivered` order-এর item-এর জন্য verified; ১ item ১ review; rating 1–5; media সর্বোচ্চ ৩; default `pending` -> admin approve; PDP-তে average ও breakdown (cache)।

**Notification (CUS-3…5)**
| Event | Channel | Template key |
|---|---|---|
| OTP | SMS | `otp.code` |
| Order placed | SMS + email | `order.placed` |
| Confirmed | SMS | `order.confirmed` |
| Shipped (tracking সহ) | SMS | `order.shipped` |
| Out for delivery | SMS | `order.out_for_delivery` |
| Delivered | SMS (+review request ২৪ ঘণ্টা পর) | `order.delivered` |
| Cancelled/Refunded | SMS + email | `order.cancelled`, `payment.refunded` |
| Low stock/new order | Admin email/push | — |
- Template মুছলে/বদলালে placeholder `{customer_name} {order_no} {total} {tracking_url}`; বাংলা SMS Unicode (70 char/segment) সতর্কতা ও segment count দেখানো।
- `SmsProvider` adapter (BD provider); delivery report webhook; failure -> retry ও `notification_logs`।
- সর্বোচ্চ SMS cost control: দিনে প্রতি phone সীমা।

### 5.14 Marketing Tracking (MKT-3)
- Storefront-এ Meta Pixel, GA4, GTM id settings থেকে। Event: `PageView, ViewContent, AddToCart, InitiateCheckout, Purchase`।
- **Server-side (Conversions API)**: `Purchase`, `InitiateCheckout` order event থেকে queue-এ; `event_id` দিয়ে browser/server dedupe; PII hashed (SHA-256, phone/email normalized)।
- Cookie/consent banner setting (বাংলাদেশে বাধ্যতামূলক না হলেও ডিফল্ট off-able)।
- API consumer-এর জন্য `POST /store/tracking/event` (React/mobile থেকে সার্ভারে পাঠানো সম্ভব)।

### 5.15 Risk ও Fraud (FRD-1…5)

`RiskService::evaluate(OrderDraft)` rule-চেইন, প্রতিটি rule score ও action দেয়:
| Rule | Default |
|---|---|
| Blacklisted phone/IP/address | `block` |
| একই phone ২৪ ঘণ্টায় > N pending/COD order (default 3) | `on_hold` |
| একই IP থেকে ১ ঘণ্টায় > N order | `on_hold` |
| Customer return+cancel rate > 40% (≥ ৩টি order পরে) | `require_advance` |
| COD amount > threshold | `require_advance` |
| নতুন customer + উচ্চমূল্য + দূরবর্তী এলাকা | `on_hold` (review) |
| Duplicate (same phone+items ২৪ ঘণ্টা) | flag only |
- সব rule admin থেকে threshold বদলযোগ্য/অক্ষম; ফলাফল `orders.risk_flags`-এ ও admin list-এ দৃশ্যমান।
- `on_hold` order-এ admin ফোন করে confirm/cancel; কারণ লগ।
- Customer score আপডেট order delivered/returned/cancelled event-এ।
- Blacklist CRUD admin API; ঘোষিত কারণ ও মেয়াদ।
- নীতি: block হলে customer-কে সাধারণ বার্তা ("আমরা এই মুহূর্তে অর্ডার নিতে পারছি না, WhatsApp-এ যোগাযোগ করুন"), কারণ জানানো হয় না।

### 5.16 CMS ও Theme (THM-1…7, ADM-4)

**Theme package (`themes/{name}/`)**
```
theme.json   { name, version, supports:[features], tokens:{colors,fonts,radius}, sections:[…], settings_schema:{…} }
views/ (layouts, partials, pages)   // Blade
sections/ (hero, category-grid, product-carousel, banner, story, testimonial, trust-bar, …)
assets/ (Tailwind source)
```
- Tailwind CSS variable token (`--color-primary` ইত্যাদি) admin থেকে override; build-এ নয়, runtime CSS variable।
- Homepage = ordered `sections` JSON (type + settings + visible); admin drag-drop; API: `GET /store/pages/home` ফেরত দেয় resolved section data (React ও একই JSON ব্যবহার করে সাজাতে পারবে)।
- Static page, menu, footer, blog, banner (placement `home_hero`, `category_top`, …; schedule)।
- Theme-এ calculation নিষিদ্ধ; theme শুধু API/Service data render করে।

**Honey theme (`honey-warm`) UX সারসংক্ষেপ:** উষ্ণ রঙ (amber/cream/deep-brown), বড় ছবি, "আমাদের গল্প" section, purity/lab badge, উৎস মানচিত্র/কার্ড, weight selector pill, gift pack CTA, trust bar (খাঁটি, ডেলিভারি, ক্যাশ অন ডেলিভারি, রিটার্ন)।

**Storefront web (Blade) নির্দেশ:** সব data `Action`/`Query` service থেকে; যে payload API-র `Resource` দিয়ে যায় তা-ই view-এ ব্যবহার, যাতে দুই পথের output এক থাকে।

### 5.17 Admin: Dashboard, Reports, Audit (ADM-1,3,5,6)

**Dashboard KPI:** আজকের/৭/৩০ দিনের order, revenue, AOV, pending/on_hold, ready-to-ship, delivered %, return %, top products, low stock, expiring batches, payment failed, conversion funnel (cart->checkout->order)।

**Reports (CSV/PDF export):** sales (দিন/পণ্য/category), order status, payment (method-wise), courier performance ও COD settlement, coupon performance, customer (new/returning, LTV), inventory valuation, expiry, zero-result search, tax/VAT summary।

**Audit:** সব admin write (create/update/delete/status change/settings/permission) `activity_logs`-এ (before/after diff); দেখা যায় admin থেকে (filter by user/subject/date); immutable।

**Settings groups:** `store` (নাম, লোগো, ঠিকানা, contact, currency), `checkout`, `orders`, `returns`, `inventory`, `shipping`, `payments`, `notifications`, `tax`, `seo`, `tracking`, `risk`, `theme`, `features`, `integrations` (secret encrypted)।

### 5.18 Admin API Endpoint তালিকা (সারসংক্ষেপ)

| Resource | Endpoint |
|---|---|
| Auth | `POST /admin/auth/login`, `POST /admin/auth/logout`, `GET /admin/auth/me`, 2FA |
| Staff/Role | `GET/POST/PATCH/DELETE /admin/users`, `/admin/roles`, `GET /admin/permissions` |
| Settings | `GET /admin/settings/{group}`, `PATCH /admin/settings/{group}`, `GET/PATCH /admin/features`, `POST /admin/presets/apply` |
| Catalog | `/admin/categories`, `/admin/brands`, `/admin/attribute-sets`, `/admin/attributes`, `/admin/products` (+ `/variants`, `/media`, `/relations`), `POST /admin/products/import`, `GET /admin/products/export` |
| Inventory | (5.11) |
| Orders | `GET /admin/orders`, `POST /admin/orders`, `GET/PATCH /admin/orders/{id}`, `POST /admin/orders/{id}/transition {to,note}`, `POST /admin/orders/{id}/shipments`, `POST /admin/orders/bulk`, `GET /admin/orders/{id}/invoice` |
| Shipping | `/admin/shipping/zones`, `/admin/shipping/rates`, `POST /admin/shipments/{id}/book`, `/cancel`, `/label` |
| Returns | `GET /admin/returns`, `POST /admin/returns/{id}/approve|reject|receive|refund` |
| Payments | (5.8) |
| Customers | `/admin/customers` (+ block/unblock, notes, orders) |
| Marketing | `/admin/coupons`, `/admin/banners`, `/admin/campaigns` (P1) |
| Reviews | `/admin/reviews` (+ approve/reject/reply) |
| CMS | `/admin/pages`, `/admin/menus`, `/admin/sections`, `/admin/themes`, `POST /admin/themes/{name}/activate` |
| Risk | `/admin/risk/rules`, `/admin/risk/blacklist` |
| Notifications | `/admin/notification-templates`, `/admin/notification-logs` |
| Reports | `GET /admin/reports/{type}`, `GET /admin/dashboard` |
| Audit | `GET /admin/activity-logs` |
| Media | `POST /admin/media` (upload), `DELETE /admin/media/{id}` |

### 5.19 Storefront/Customer Endpoint তালিকা (সারসংক্ষেপ)

| Group | Endpoint |
|---|---|
| Bootstrap/CMS | `GET /store/bootstrap`, `/store/pages/{slug}`, `/store/menus/{key}`, `/store/banners?placement=` |
| Catalog | (5.3) |
| Cart | (5.5) |
| Checkout | `POST /store/checkout/quote`, `POST /store/checkout/place` |
| Auth | `POST /store/auth/otp/send`, `/otp/verify`, `/login`, `/register`, `/social/{provider}`, `/logout` |
| Geo | `GET /store/geo/divisions`, `/districts?division=`, `/upazilas?district=` |
| Delivery | `GET /store/delivery/estimate?district=&upazila=&cart=` |
| Order (guest) | `GET /store/orders/{no}?phone=`, `/tracking` |
| Me | `GET/PATCH /me`, `/me/addresses`, `/me/orders`, `/me/orders/{no}`, `/me/orders/{no}/cancel`, `/me/orders/{no}/returns`, `/me/orders/{no}/reorder`, `/me/wishlist`, `/me/reviews` |
| Reviews | `POST /me/reviews` |
| Misc | `POST /store/stock-alerts`, `POST /store/newsletter`, `POST /store/contact`, `POST /store/tracking/event` |

---

## 6. Phase 2 (Grocery) Extension Requirements — সংক্ষিপ্ত

| ID | Requirement | Data/Design Hook |
|---|---|---|
| GRC-1 Delivery Slot | Slot template (দিন, সময়-window, capacity, cut-off, zone); checkout-এ slot নির্বাচন; capacity atomic (slot_bookings); same-day cut-off | নতুন table `delivery_slots`, `slot_bookings`; Checkout quote-এ `slots[]` (flag `delivery_slots`) |
| GRC-2 Weight billing | Variant `sold_by=weight`, আনুমানিক ওজন; packing-এ actual ওজন লিখলে final price পুনর্গণনা; ±tolerance; COD amount হালনাগাদ; online paid হলে difference refund/adjust | `order_items.est_qty`, `actual_qty`, `price_per_unit`; Pricing service extension |
| GRC-3 FEFO + perishable | Batch alloc FEFO ডিফল্ট; perishable দিনের মধ্যে ডেলিভারি | INV-R7 বর্ধিত |
| GRC-4 Area-wise stock | `warehouse_id`/hub; zone->hub mapping; available per hub | `inventory_items` -> `inventory_stocks(warehouse_id)` migration |
| GRC-5 Quick-add UI/API | Cart API একই; listing item-এ `unit`, `price_per_unit`, `step` (০.৫kg) | variant `qty_step`, `unit` |
| GRC-6 Repeat order/smart basket | `reorder` ইতিমধ্যে; frequent items list | `/me/frequent-items` |
| GRC-7 Min order & delivery fee tier | CHK-R7, shipping rate subtotal slab | existing |
| GRC-8 Grocery theme | Dense grid, category sidebar, sticky cart, search-first | theme `grocery-fresh` |
| GRC-9 Return policy | শুধু damaged/missing/wrong | `returnable=false` ডিফল্ট, `order_returns.type` সীমিত |

> Phase 1 schema ও API ডিজাইনে এই hook-গুলোর জন্য জায়গা রাখা বাধ্যতামূলক (extension-friendly), কিন্তু কার্যকর implementation নয়।

---

## 7. Non-Functional Requirements (পরিমাপযোগ্য)

| ID | Requirement | যাচাই |
|---|---|---|
| NFR-1 | Public catalog API p95 < 300ms (cache hit < 80ms) @ 50 rps | load test (k6) |
| NFR-2 | Checkout place p95 < 800ms @ 10 rps, **০টি oversell** (১০০০ concurrent test) | concurrency test |
| NFR-3 | Mobile storefront LCP < 2.5s (4G, Moto G class), CLS < 0.1 | Lighthouse CI |
| NFR-4 | Availability ≥ 99.5%/মাস; RPO ≤ 24h (daily), RTO ≤ 4h | backup restore drill |
| NFR-5 | Sale spike 10x: queue backlog < 5 মিনিট, কোনো ডেটা হারানো নয় | load test |
| NFR-6 | Security: OWASP Top 10 নিরসন; dependency scan CI-তে; secrets encrypted at rest; admin 2FA; password Argon2id/bcrypt; CSP, HSTS | security review |
| NFR-7 | PII: phone/address রক্ষা, লগে mask; customer data export/delete অনুরোধ হ্যান্ডলিং | review |
| NFR-8 | Payment card ডেটা কখনো সিস্টেমে স্পর্শ নয় (hosted gateway) | design |
| NFR-9 | Observability: structured log, error tracking, health endpoint `/api/health`, queue/scheduler heartbeat, payment/courier failure alert | check |
| NFR-10 | Test: প্রতি API endpoint-এ feature test (সুখী পথ + অন্তত একটি ব্যর্থ পথ); state machine/pricing/inventory-তে unit test কভারেজ ≥ 90%; CI-তে এগুলো pass বাধ্যতামূলক | CI gate |
| NFR-11 | i18n: সব user-visible string translation key; বাংলা/English; তারিখ/সংখ্যা locale অনুযায়ী | review |
| NFR-12 | Accessibility: WCAG 2.2 AA (contrast, label, keyboard) storefront-এ | audit |
| NFR-13 | Upgrade: migration backward-safe (expand/contract), `php artisan store:upgrade` | process |
| NFR-14 | API contract স্থির: OpenAPI diff CI-তে breaking change ধরা | CI |
| NFR-15 | Image: upload-এ resize/WebP, CDN-ready, alt text প্রস্তাব | check |
| NFR-16 | Backup: DB + media দৈনিক, ৭ দিন + ৪ সাপ্তাহিক retention, off-server | ops |

---

## 8. Business Rules সারসংকলন (দ্রুত রেফারেন্স)

1. Server-ই price/stock/discount-এর একমাত্র সত্য; client সংখ্যা বিশ্বাসযোগ্য নয়।
2. Order place atomic: stock reserve + order + coupon একসাথে বা কিছুই না।
3. Payment "paid" শুধু gateway server verify-এর পর।
4. Stock পরিবর্তন সবসময় লগড ও immutable।
5. Order status শুধু অনুমোদিত transition-এ।
6. সব বাহ্যিক ইন্টিগ্রেশন adapter-এর পেছনে ও retry-সক্ষম।
7. Feature disabled = সম্পূর্ণ অনুপস্থিত (route/menu/job/API)।
8. সব admin write audit-লগড।
9. Risk rule order আটকাতে পারে কিন্তু কারণ ক্রেতাকে বলে না।
10. Theme কোনো business logic রাখে না।

---

## 9. Acceptance Criteria (Phase 1 সমাপ্তির শর্ত)

| # | Criterion |
|---|---|
| AC-1 | `store:install --type=honey_nuts` দিয়ে নতুন DB থেকে ১০ মিনিটে চলমান store |
| AC-2 | একজন guest মোবাইলে: পণ্য খুঁজে, weight variant বেছে, cart, OTP, COD অর্ডার; admin-এ order দেখা যায়, courier-এ book হয়, SMS যায়, delivered হলে payment paid |
| AC-3 | bKash/aggregator sandbox-এ online payment সফল/ব্যর্থ/timeout তিনটি পথ সঠিক; webhook duplicate নিরাপদ |
| AC-4 | ১০০০ concurrent অর্ডারে (stock=৫০) ঠিক ৫০টি সফল, ০ oversell |
| AC-5 | Batch/expiry: FEFO অনুযায়ী batch বরাদ্দ, expired batch বিক্রি হয় না |
| AC-6 | Return request-> approve -> receive -> restock/damage -> refund সম্পূর্ণ flow |
| AC-7 | Fraud rule: একই phone-এ ৪র্থ COD অর্ডার `on_hold` হয় |
| AC-8 | OpenAPI spec সম্পূর্ণ; একটি demo React অ্যাপ (শুধু API ব্যবহার করে) catalog->cart->checkout->tracking চালাতে পারে (প্রমাণ যে API-first কাজ করছে) |
| AC-9 | Admin role অনুযায়ী পারমিশন কার্যকর; সব write audit-এ |
| AC-10 | NFR-1,2,3,10 পূরণ |

---

## 10. Traceability (PRD -> SRS)

| PRD | SRS |
|---|---|
| CFG-* | 5.1 |
| API-* | 3, 2.3, 5.18, 5.19 |
| CAT-*, SRC-* | 5.3, 5.4 |
| CHK-* | 5.5, 5.6, 5.7 |
| PAY-* | 5.8 |
| ORD-* | 5.9, 5.10 |
| INV-* | 5.11 |
| MKT-* | 5.12, 5.13, 5.14 |
| FRD-* | 5.15 |
| CUS-* | 5.2, 5.13 |
| ADM-*, THM-* | 5.16, 5.17, 5.18 |
| NFR (PRD §9) | 7 |
| Grocery matrix | 6 |

---

## 11. Open Items (SRS finalize-এর আগে)

1. Payment aggregator ও SMS provider চূড়ান্ত (5.8, 5.13)।
2. Courier কোনটি Phase 1-এ পূর্ণ (সুপারিশ: Steadfast বা Pathao একটি) (5.10)।
3. VAT/tax নিয়ম ও invoice format (5.6)।
4. Honey-nuts client-এর return policy (window, শর্ত) (5.9)।
5. COD thresholds (max COD amount, order/phone limit) (5.15)।
6. Multi-tenant ভবিষ্যৎ ধরলে `store_id` শুরুতে রাখা হবে কি (Assumption A4)।
7. Admin UI টেকনোলজি (A2)।

## 12. পরবর্তী ধাপ
1. এই SRS review ও open item সমাধান।
2. **Technical design:** migration খসড়া (ERD অনুযায়ী), Module skeleton, Settings/Feature/Preset ইঞ্জিন, Theme ইঞ্জিন, OpenAPI skeleton।
3. Phase 0 ticket তালিকা ও estimation।
