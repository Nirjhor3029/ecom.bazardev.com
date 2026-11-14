<?php

/*
|--------------------------------------------------------------------------
| Store presets (PRD §5, SRS FR-CFG-2/3)
|--------------------------------------------------------------------------
| Applied by `php artisan store:install --type=<key>`. Re-running is
| idempotent: feature flags whose current `source` is already `admin`
| are left untouched, and settings already present in the DB are skipped.
*/

return [

    'honey_nuts' => [
        'theme' => 'honey-warm',

        'feature_flags' => [
            'batch_expiry' => true,
            'delivery_slots' => false,
            'weight_billing' => false,
            'preorder' => true,
            'reviews' => true,
            'wishlist' => true,
            'coupons' => true,
            'bundles' => true,
            'quick_add' => false,
            'guest_checkout' => true,
            'cod' => true,
            'online_payment' => true,
            'abandoned_cart' => false,
        ],

        'settings' => [
            'store.name' => ['group' => 'store', 'is_public' => true, 'value' => 'Honey & Nuts'],
            'store.currency' => ['group' => 'store', 'is_public' => true, 'value' => 'BDT'],
            'checkout.require_phone_verification' => ['group' => 'checkout', 'is_public' => false, 'value' => true],
            'returns.window_days' => ['group' => 'returns', 'is_public' => true, 'value' => 3],
            'rounding' => ['group' => 'checkout', 'is_public' => false, 'value' => 'none'],
            'tax.inclusive' => ['group' => 'tax', 'is_public' => false, 'value' => true],
        ],

        'attribute_set' => [
            'code' => 'honey',
            'name' => 'Honey & Nuts',
            'attributes' => [
                ['code' => 'weight', 'label' => ['bn' => 'ওজন', 'en' => 'Weight'], 'type' => 'select', 'options' => ['250g', '500g', '1kg'], 'is_variant' => true, 'is_filterable' => true, 'is_required' => true, 'sort' => 1],
                ['code' => 'origin', 'label' => ['bn' => 'উৎস', 'en' => 'Origin'], 'type' => 'select', 'options' => ['সুন্দরবন', 'মৌলভীবাজার', 'অন্যান্য'], 'is_filterable' => true, 'sort' => 2],
                ['code' => 'honey_type', 'label' => ['bn' => 'মধুর ধরন', 'en' => 'Honey type'], 'type' => 'select', 'options' => ['খলিশা', 'সরিষা', 'লিচু'], 'is_filterable' => true, 'sort' => 3],
                ['code' => 'purity_cert', 'label' => ['bn' => 'ল্যাব টেস্ট/সার্টিফিকেট', 'en' => 'Lab tested'], 'type' => 'boolean', 'sort' => 4],
                ['code' => 'harvest_date', 'label' => ['bn' => 'সংগ্রহ তারিখ', 'en' => 'Harvest date'], 'type' => 'date', 'sort' => 5],
                ['code' => 'shelf_life_months', 'label' => ['bn' => 'মেয়াদ (মাস)', 'en' => 'Shelf life (months)'], 'type' => 'number', 'sort' => 6],
                ['code' => 'ingredients', 'label' => ['bn' => 'উপাদান', 'en' => 'Ingredients'], 'type' => 'text', 'sort' => 7],
                ['code' => 'storage', 'label' => ['bn' => 'সংরক্ষণ নির্দেশনা', 'en' => 'Storage instructions'], 'type' => 'text', 'sort' => 8],
                ['code' => 'nut_type', 'label' => ['bn' => 'বাদামের ধরন', 'en' => 'Nut type'], 'type' => 'select', 'options' => ['কাজু', 'পেস্তা', 'আমন্ড', 'আখরোট'], 'is_filterable' => true, 'sort' => 9],
                ['code' => 'is_organic', 'label' => ['bn' => 'অর্গানিক', 'en' => 'Organic'], 'type' => 'boolean', 'is_filterable' => true, 'sort' => 10],
            ],
        ],

        'shipping_zones' => [
            [
                'name' => 'Dhaka Metro',
                'match' => ['keyword' => 'dhaka'],
                'priority' => 10,
                'rates' => [
                    ['method' => 'standard', 'fee' => 6000, 'free_over' => 100000, 'est_days_min' => 1, 'est_days_max' => 2],
                ],
            ],
            [
                'name' => 'Outside Dhaka',
                'match' => ['default' => true],
                'priority' => 0,
                'rates' => [
                    ['method' => 'standard', 'fee' => 12000, 'est_days_min' => 2, 'est_days_max' => 4],
                ],
            ],
        ],
    ],

    // Phase 2 — extension points only, not yet implemented (GRC-* in SRS §6).
    'grocery' => [
        'theme' => 'grocery-fresh',
        'feature_flags' => [
            'batch_expiry' => true,
            'delivery_slots' => true,
            'weight_billing' => true,
            'preorder' => false,
            'reviews' => true,
            'wishlist' => true,
            'coupons' => true,
            'bundles' => true,
            'quick_add' => true,
            'guest_checkout' => true,
            'cod' => true,
            'online_payment' => true,
            'abandoned_cart' => true,
        ],
        'settings' => [
            'store.name' => ['group' => 'store', 'is_public' => true, 'value' => 'Grocery'],
            'store.currency' => ['group' => 'store', 'is_public' => true, 'value' => 'BDT'],
        ],
        'attribute_set' => [
            'code' => 'grocery_item',
            'name' => 'Grocery Item',
            'attributes' => [
                ['code' => 'unit_type', 'label' => ['bn' => 'একক', 'en' => 'Unit'], 'type' => 'select', 'options' => ['kg', 'g', 'liter', 'pcs'], 'is_variant' => true, 'is_required' => true, 'sort' => 1],
                ['code' => 'perishable', 'label' => ['bn' => 'পচনশীল', 'en' => 'Perishable'], 'type' => 'boolean', 'is_filterable' => true, 'sort' => 2],
                ['code' => 'expiry_date', 'label' => ['bn' => 'মেয়াদ', 'en' => 'Expiry date'], 'type' => 'date', 'sort' => 3],
            ],
        ],
        'shipping_zones' => [],
    ],

];
