<?php

namespace Database\Seeders;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductMedia;
use App\Modules\Catalog\Models\Review;
use App\Modules\Core\Models\Media;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Demo storefront data (Madhuban honey & nuts) — categories, products with real
 * weight variants + per-variant stock, photo galleries, a demo video, PDP storytelling
 * content and demo customer reviews. Idempotent: keyed on slug / sku / phone.
 * Prices are paisa (minor unit) — written below in taka and ×100.
 *
 * Run: php artisan db:seed --class=DemoCatalogSeeder
 * Needs the honey attribute set: php artisan store:install --type=honey_nuts
 */
class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $set = AttributeSet::query()->firstOrCreate(['code' => 'honey'], ['name' => 'Honey']);

        $categories = [];
        foreach ([
            ['honey', 'খাঁটি মধু', 'Pure Honey', 1, 'cat-honey'],
            ['nuts', 'প্রিমিয়াম বাদাম', 'Premium Nuts', 2, 'cat-nuts'],
            ['combo', 'হানি কম্বো', 'Honey Combo', 3, 'cat-combo'],
            ['gift-box', 'গিফট বক্স', 'Gift Box', 4, 'cat-gift'],
        ] as [$slug, $bn, $en, $sort, $img]) {
            $categories[$slug] = Category::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => ['bn' => $bn, 'en' => $en], 'sort' => $sort, 'is_active' => true, 'image_id' => $this->media($img, $bn)->id],
            );
        }

        $video = $this->media('demo-video', 'মধুবন — মধু আহরণের ভিডিও', 'video/mp4', 'demo-video.mp4');

        foreach ($this->catalog() as $p) {
            $product = Product::query()->updateOrCreate(['slug' => $p['slug']], [
                'attribute_set_id' => $set->id,
                'category_id' => $categories[$p['cat']]->id,
                'name' => ['bn' => $p['bn'], 'en' => $p['en']],
                'short_desc' => ['bn' => $p['short'], 'en' => $p['short']],
                'description' => ['bn' => $p['desc'], 'en' => $p['en']],
                'type' => count($p['variants']) > 1 ? 'variable' : 'simple',
                'status' => 'active',
                'content' => $p['content'] ?? null,
                'published_at' => now(),
            ]);

            foreach ($p['variants'] as $i => [$sku, $grams, $price, $sale, $stock, $tag]) {
                $variant = $product->variants()->updateOrCreate(['sku' => $sku], [
                    'options' => array_filter(['weight' => $this->weightLabel($grams), 'tag' => $tag]),
                    'price' => $price * 100,
                    'sale_price' => $sale ? $sale * 100 : null,
                    'weight_grams' => $grams,
                    'is_default' => $tag === 'জনপ্রিয়' || (count($p['variants']) === 1),
                    'is_active' => true,
                    'sort' => $i,
                ]);
                InventoryItem::query()->updateOrCreate(['variant_id' => $variant->id], ['on_hand' => $stock]);
            }

            // Gallery: photos first (first one is primary), then the video.
            $product->media()->delete();
            foreach ($p['images'] as $n => $img) {
                ProductMedia::query()->create([
                    'product_id' => $product->id, 'media_id' => $this->media($img, $p['bn'])->id,
                    'sort' => $n, 'is_primary' => $n === 0,
                ]);
            }
            if (! empty($p['video'])) {
                ProductMedia::query()->create(['product_id' => $product->id, 'media_id' => $video->id, 'sort' => 99, 'is_primary' => false]);
            }

            $this->reviews($product, $p['reviews'] ?? []);
        }
    }

    private function weightLabel(int $grams): string
    {
        return $grams >= 1000 ? rtrim(rtrim(number_format($grams / 1000, 1), '0'), '.').'kg' : $grams.'g';
    }

    /** Copy a bundled demo file onto the public disk and register it in the media library. */
    private function media(string $name, string $alt, string $mime = 'image/jpeg', ?string $file = null): Media
    {
        $file ??= str_ends_with($name, '.jpg') ? $name : "{$name}.jpg";
        $path = 'media/demo/'.basename($file);
        $source = $this->source($file);

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        return Media::query()->firstOrCreate(
            ['disk' => 'public', 'path' => $path],
            ['original_name' => basename($file), 'mime' => $mime, 'size' => filesize($source), 'alt' => $alt],
        );
    }

    /** Bundled assets live in demo-images/ and demo-images/pdp/. */
    private function source(string $file): string
    {
        foreach (['', 'pdp/'] as $dir) {
            $path = database_path("seeders/demo-images/{$dir}{$file}");
            if (is_file($path)) {
                return $path;
            }
        }
        throw new \RuntimeException("Demo asset not found: {$file}");
    }

    /** Public-disk path for a bundled photo that is referenced by path (review photos, story art). */
    private function publicPath(string $file): string
    {
        $path = 'media/demo/'.$file;
        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($this->source($file)));
        }

        return $path;
    }

    private function reviews(Product $product, array $items): void
    {
        $product->reviews()->delete();
        foreach ($items as $i => [$name, $phone, $location, $rating, $body, $daysAgo, $photo]) {
            $customer = Customer::query()->firstOrCreate(['phone' => $phone], ['name' => $name, 'phone_verified_at' => now()]);
            $review = Review::query()->create([
                'product_id' => $product->id, 'customer_id' => $customer->id, 'rating' => $rating, 'body' => $body,
                'location' => $location, 'status' => 'approved', 'is_verified' => true,
                'media' => $photo ? [$this->publicPath($photo)] : null,
            ]);
            $review->forceFill(['created_at' => now()->subDays($daysAgo), 'updated_at' => now()->subDays($daysAgo)])->save();
        }
    }

    // ─────────────────────────────── catalog data ───────────────────────────────

    /** @return list<array<string, mixed>> */
    private function catalog(): array
    {
        $people = [
            ['রাকিবুল হাসান', '+8801700000001', 'ধানমন্ডি, ঢাকা'],
            ['তানজিলা আহমেদ', '+8801700000002', 'পাঁচলাইশ, চট্টগ্রাম'],
            ['মাহমুদুল করিম', '+8801700000003', 'উত্তরা, ঢাকা'],
            ['সুমাইয়া ইসলাম', '+8801700000004', 'সিলেট সদর'],
            ['আরিফুর রহমান', '+8801700000005', 'বোয়ালিয়া, রাজশাহী'],
            ['নাজমুন নাহার', '+8801700000006', 'সোনাডাঙ্গা, খুলনা'],
            ['ফারহান সাদিক', '+8801700000007', 'মিরপুর, ঢাকা'],
            ['শারমিন সুলতানা', '+8801700000008', 'বগুড়া সদর'],
        ];
        $r = fn (int $who, int $rating, string $body, int $days, ?string $photo = null) => [...$people[$who], $rating, $body, $days, $photo];

        $khalishaReviews = [
            $r(0, 5, 'মধুবনের খলিশা মধুর গন্ধটা অসাধারণ! অন্যান্য মধুর মতো মুখে কড়া মিষ্টি লাগে না, খুব হালকা এবং একটা বুনো স্নিগ্ধ ঘ্রাণ আছে। কাঁচের বয়ামটাও খুব সুরক্ষিত অবস্থায় পেয়েছি।', 3, 'review-1.jpg'),
            $r(1, 5, 'আমি দ্বিতীয়বার ১ কেজি প্যাক অর্ডার করলাম। আব্বুর ডায়াবেটিস থাকায় চিকিৎসকের পরামর্শে পরিমিত মধু খান। এই মধুর টেস্টে শতভাগ খাঁটি হওয়ার প্রমাণ স্পষ্ট। অনেক ধন্যবাদ মধুবন টিমকে।', 7, 'review-2.jpg'),
            $r(2, 4, 'ডেলিভারি পেয়েছি মাত্র ২৪ ঘণ্টায়। প্যাকেজিং চমৎকার ছিল, বাবল র‍্যাপে মোড়ানো। ল্যাব টেস্ট রিপোর্ট কিউআর কোড স্ক্যান করেও দেখে নিয়েছি। শতভাগ প্রাকৃতিক প্রোডাক্ট।', 14, 'review-3.jpg'),
            $r(3, 5, 'গরম পানিতে লেবুর সাথে প্রতিদিন সকালে খাই। স্বাদ ও ঘ্রাণ দুটোই দারুণ, বাজারের ভেজাল মধুর সাথে তুলনাই চলে না।', 21),
            $r(4, 5, 'মায়ের জন্য কাশির সময় অর্ডার করেছিলাম, খুব ভালো কাজ করেছে। পরের মাসে আবার নেব ইনশাআল্লাহ।', 28),
            $r(5, 5, 'প্যাকেজিং অনেক সুন্দর, গিফট দেওয়ার মতো। মধুর রং ও ঘনত্ব একদম আসল মধুর মতো।', 35),
            $r(6, 5, 'ক্রিস্টালাইজ হয়নি, একদম পরিষ্কার ও সুগন্ধি। দাম একটু বেশি হলেও মানের তুলনায় ঠিকই আছে।', 42),
            $r(7, 5, 'দ্রুত ডেলিভারি আর খাঁটি মধু—এই দুটোই পেয়েছি। ধন্যবাদ মধুবন।', 50),
        ];

        $honeyWeights = fn (string $k, array $p) => [
            ["HNY-{$k}-250", 250, $p[0], $p[1], 60, null],
            ["HNY-{$k}-500", 500, $p[2], $p[3], 45, 'জনপ্রিয়'],
            ["HNY-{$k}-1000", 1000, $p[4], $p[5], 25, 'পারিবারিক'],
            ["HNY-{$k}-2000", 2000, $p[6], null, 0, 'বাল্ক জার'],
        ];
        $nutWeights = fn (string $k, array $p) => [
            ["NUT-{$k}-250", 250, $p[0], $p[1], 60, null],
            ["NUT-{$k}-500", 500, $p[2], $p[3], 40, 'জনপ্রিয়'],
            ["NUT-{$k}-1000", 1000, $p[4], $p[5], 20, 'পারিবারিক'],
        ];

        return [
            [
                'slug' => 'sundarban-khalisha-honey', 'cat' => 'honey', 'bn' => 'সুন্দরবনের খলিশা মধু', 'en' => 'Sundarbans Raw Khalisha Floral Honey',
                'short' => '১০০% খাঁটি র খলিশা মধু',
                'desc' => 'সুন্দরবনের গভীর ম্যানগ্রোভ বনের খলিশা ফুলের প্রাকৃতিক নেকটার থেকে মৌয়ালদের দ্বারা সংগৃহীত কাঁচা ও অপ্রক্রিয়াজাত খাঁটি মধু। এতে রয়েছে সজীব প্রাকৃতিক এনজাইম, অমূল্য পরাগরেণু ও হালকা মনোহর সুবাস।',
                'variants' => $honeyWeights('KHA', [480, 420, 850, 750, 1650, 1450, 2800]),
                'images' => ['khalisha-honey.jpg', 'khalisha-jar.jpg', 'honey-texture.jpg', 'honey-pouring.jpg', 'mangrove.jpg'],
                'video' => true,
                'reviews' => $khalishaReviews,
                'content' => $this->honeyContent([
                    'grade' => 'র হানি • প্রিমিয়াম গ্রেড', 'subtitle' => 'Sundarbans Raw Khalisha Floral Honey', 'origin' => 'সাতক্ষীরা রেঞ্জ', 'batch' => '#MDH-KH-2024-B3',
                    'moisture' => '১৯.২', 'pollen' => '৯৪.৮% খাঁটি', 'hmf' => '< ১৪ mg/kg', 'flower' => 'খলিশা', 'botanical' => 'খলিশা বৃক্ষ (Aegiceras corniculatum) নেকটার',
                    'forest' => 'সুন্দরবন ম্যানগ্রোভ বন', 'season' => 'চৈত্র ও বৈশাখ (প্রথম লট)', 'harvest' => 'চৈত্র ১৪৩০ / এপ্রিল ২০২৪',
                    'sweet' => [65, 'হালকা থেকে মাঝারি (৬.৫/১০)'], 'aroma' => [90, 'উচ্চ ও মৃদু মিষ্টি (৯.০/১০)'], 'visc' => [75, 'স্বাভাবিক মসৃণ তরল (৭.৫/১০)'],
                    'intro' => 'প্রকৃতিতে খলিশা ফুল ফোটে বসন্তের শুরুতে। এই মধুর হালকা সোনালী বর্ণ ও অনন্য হালকা মিষ্টি স্বাদ এটিকে অন্যান্য মধুর চেয়ে স্বতন্ত্র করে তোলে।',
                    'story_title' => 'সুন্দরবনের মৌয়াল ও খাঁটি খলিশা মধুর ঐতিহ্য', 'story_eyebrow' => 'প্রকৃতির কোল থেকে আপনার ঘরে',
                    'story' => ['প্রতি বছর বসন্তের বিদায় ও গ্রীষ্মের আগমনী লগ্নে সুন্দরবনের খলিশা গাছে সুগন্ধি সাদা ফুল ফোটে। ঐতিহ্যবাহী মৌয়ালরা বন বিভাগের অনুমতি নিয়ে জীবন বাজি রেখে গভীর অরণ্যে প্রবেশ করেন এবং সংগ্রহ করেন এই অমূল্য মধু।', 'মধুবন সরাসরি এই স্থানীয় মৌয়াল সম্প্রদায়ের সাথে কাজ করে। কোনো মধ্যস্বত্বভোগী ছাড়া আমরা তাদের ন্যায্য মূল্য প্রদান করি। চাক থেকে সংগ্রহ করা মধু কোনো উত্তাপ বা কেমিক্যাল ছাড়া কেবল সুতি কাপড়ে ছেঁকে কাঁচের জারে বোতলজাত করা হয়।'],
                    'faq' => [
                        ['খাঁটি মধু ক্রিস্টালাইজ (জমাট) হয় কেন?', 'কাঁচা ও অপ্রক্রিয়াজাত মধুতে প্রাকৃতিক গ্লুকোজ থাকে, যা ঠান্ডা আবহাওয়ায় ধীরে ধীরে জমাট বাঁধতে পারে। এটি খাঁটি হওয়ার লক্ষণ। জারটি কুসুম গরম পানির পাত্রে কিছুক্ষণ রাখলেই আবার তরল হয়ে যায়।'],
                        ['সুন্দরবনের খলিশা মধু অন্যান্য মধু থেকে কীভাবে আলাদা?', 'খলিশা মধুর রং হালকা সোনালী, স্বাদ পরিমিত মিষ্টি ও ফুলের মৃদু সুবাসযুক্ত। এতে কড়া ঝাঁজ নেই, তাই শিশু থেকে বয়স্ক সবাই সহজে খেতে পারেন।'],
                        ['কাঁচা (Raw) মধু বলতে কী বোঝায়?', 'যে মধু পাস্তুরিত বা উচ্চ তাপে প্রক্রিয়াজাত করা হয় না, শুধু ছেঁকে বোতলজাত করা হয়—সেটিই কাঁচা মধু। এতে এনজাইম, পরাগরেণু ও অ্যান্টিঅক্সিডেন্ট অটুট থাকে।'],
                        ['মধু কীভাবে সংরক্ষণ করব?', 'স্বাভাবিক কক্ষ তাপমাত্রায়, সরাসরি রোদ থেকে দূরে রাখুন। ফ্রিজে রাখবেন না এবং সবসময় শুকনো চামচ ব্যবহার করুন। প্যাকিং থেকে ২ বছর পর্যন্ত ভালো থাকে।'],
                        ['পণ্যের বিশুদ্ধতা কীভাবে যাচাই করব?', 'প্রতিটি বয়ামের লেবেলে একটি QR কোড আছে। স্ক্যান করলেই সেই ব্যাচের ল্যাব টেস্ট ও আহরণের তথ্য দেখতে পারবেন। এছাড়া ক্যাশ অন ডেলিভারিতে পণ্য দেখে নিয়ে মূল্য পরিশোধ করতে পারেন।'],
                    ],
                ]),
            ],
            [
                'slug' => 'mustard-blossom-honey', 'cat' => 'honey', 'bn' => 'সরিষা ফুলের প্রাকৃতিক মধু', 'en' => 'Mustard Blossom Honey',
                'short' => 'সরিষা ফুলের সুগন্ধি মধু',
                'desc' => 'শীতের সরিষা খেতের হলুদ ফুল থেকে সংগৃহীত ক্রিমি, সুগন্ধি কাঁচা মধু। স্বাভাবিকভাবেই কিছুটা জমাট বাঁধে, যা এর খাঁটি হওয়ার প্রমাণ।',
                'variants' => $honeyWeights('MUS', [360, 330, 650, 580, 1250, 1100, 2150]),
                'images' => ['mustard-honey.jpg', 'mustard-jar.jpg', 'honey-texture.jpg', 'honey-pouring.jpg'],
                'video' => true,
                'reviews' => [
                    $r(1, 5, 'সরিষার মধুর ক্রিমি টেক্সচারটা দারুণ। রুটির সাথে খেতে অসাধারণ লাগে।', 5),
                    $r(3, 5, 'বাচ্চারা খুব পছন্দ করেছে। প্যাকেজিংও সুন্দর ছিল।', 18),
                    $r(5, 4, 'মধু ভালো, তবে জমাট বাঁধায় প্রথমে একটু ঘাবড়ে গিয়েছিলাম—পরে বুঝলাম এটাই খাঁটির লক্ষণ।', 30),
                    $r(7, 5, 'দাম ও মান দুটোই ঠিক আছে। আবার নেব।', 44),
                ],
                'content' => $this->honeyContent([
                    'grade' => 'র হানি • সিজনাল স্পেশাল', 'subtitle' => 'Mustard Blossom Raw Honey', 'origin' => 'ফরিদপুর অঞ্চল', 'batch' => '#MDH-MS-2024-B1',
                    'moisture' => '১৭.৮', 'pollen' => '৯০% খাঁটি', 'hmf' => '< ১২ mg/kg', 'flower' => 'সরিষা', 'botanical' => 'সরিষা ফুল (Brassica campestris) নেকটার',
                    'forest' => 'ফরিদপুর-মানিকগঞ্জ সরিষা খেত', 'season' => 'পৌষ ও মাঘ (শীতকাল)', 'harvest' => 'মাঘ ১৪৩০ / জানুয়ারি ২০২৪',
                    'sweet' => [80, 'মাঝারি থেকে বেশি (৮.০/১০)'], 'aroma' => [70, 'মাঝারি ও মৃদু (৭.০/১০)'], 'visc' => [85, 'ঘন ও ক্রিমি (৮.৫/১০)'],
                    'intro' => 'শীতের সকালে হলুদ সরিষা খেতের মৌমাছিদের আনাগোনায় তৈরি হয় এই ক্রিমি মধু। স্বাভাবিকভাবে কিছুটা জমাট বাঁধা এর খাঁটি হওয়ার প্রমাণ।',
                    'story_title' => 'সরিষা খেত থেকে আপনার টেবিলে', 'story_eyebrow' => 'মাঠের মধু',
                    'story' => ['প্রতি শীতে হাজার হাজার বিঘা সরিষা খেত হলুদে ছেয়ে যায়। আমাদের সহযোগী চাষি ও মৌপালকরা মৌ-বাক্স নিয়ে খেতের পাশে অবস্থান করেন, যাতে মৌমাছিরা সরাসরি ফুল থেকে নেকটার সংগ্রহ করতে পারে।', 'সংগ্রহের পর কোনো তাপ বা রাসায়নিক ছাড়াই শুধু ছেঁকে জারে ভরা হয়, তাই এর প্রাকৃতিক স্বাদ ও পুষ্টিগুণ অটুট থাকে।'],
                    'faq' => [
                        ['সরিষার মধু জমাট বাঁধে কেন?', 'সরিষার মধুতে গ্লুকোজের পরিমাণ বেশি, তাই এটি দ্রুত ক্রিস্টালাইজ হয়। এটি খাঁটি হওয়ার লক্ষণ, কোনো সমস্যা নয়।'],
                        ['জমাট মধু কীভাবে আবার তরল করব?', 'বন্ধ জারটি কুসুম গরম পানিতে ১০–১৫ মিনিট রাখুন। সরাসরি আগুনে গরম করবেন না।'],
                    ],
                ]),
            ],
            [
                'slug' => 'litchi-blossom-honey', 'cat' => 'honey', 'bn' => 'লিচু ফুলের খাঁটি মধু', 'en' => 'Litchi Blossom Honey',
                'short' => 'দিনাজপুরের লিচু বাগানের মধু',
                'desc' => 'বসন্তে ফোটা লিচু ফুলের নেকটার থেকে তৈরি হালকা সোনালী, ফলের মতো মিষ্টি সুবাসযুক্ত মধু।',
                'variants' => $honeyWeights('LIT', [400, 360, 700, 620, 1350, 1200, 2300]),
                'images' => ['litchi-honey.jpg', 'honey-texture.jpg', 'honey-pouring.jpg'],
                'video' => true,
                'reviews' => [
                    $r(0, 5, 'লিচুর মধুর ঘ্রাণটা একদম আলাদা, খুব হালকা আর মিষ্টি।', 9),
                    $r(2, 5, 'চায়ে মিশিয়ে খাই, চিনির বিকল্প হিসেবে দারুণ।', 22),
                    $r(4, 4, 'ভালো মধু। ডেলিভারি একটু দেরিতে এসেছে, তবে পণ্যের মান নিয়ে অভিযোগ নেই।', 39),
                ],
                'content' => $this->honeyContent([
                    'grade' => 'র হানি • ফ্রুটি ফ্লোরাল', 'subtitle' => 'Litchi Blossom Raw Honey', 'origin' => 'দিনাজপুর অঞ্চল', 'batch' => '#MDH-LT-2024-B2',
                    'moisture' => '১৮.১', 'pollen' => '৯২% খাঁটি', 'hmf' => '< ১৩ mg/kg', 'flower' => 'লিচু', 'botanical' => 'লিচু ফুল (Litchi chinensis) নেকটার',
                    'forest' => 'দিনাজপুর ও ঠাকুরগাঁও লিচু বাগান', 'season' => 'ফাল্গুন ও চৈত্র', 'harvest' => 'চৈত্র ১৪৩০ / এপ্রিল ২০২৪',
                    'sweet' => [75, 'মাঝারি (৭.৫/১০)'], 'aroma' => [85, 'ফলের মতো সুবাস (৮.৫/১০)'], 'visc' => [60, 'হালকা ও মসৃণ (৬.০/১০)'],
                    'intro' => 'লিচু ফুলের হালকা ফলের মতো সুবাস আর সোনালী রং এই মধুকে করেছে অনন্য।',
                    'story_title' => 'লিচু বাগানের সুবাসে গড়া মধু', 'story_eyebrow' => 'বাগানের মধু',
                    'story' => ['বসন্তের শেষে উত্তরবঙ্গের লিচু বাগানগুলো ফুলে ভরে ওঠে। এই সময় বাগানের পাশে মৌ-বাক্স বসিয়ে মৌমাছিদের স্বাভাবিকভাবে মধু সংগ্রহ করতে দেওয়া হয়।', 'প্রতিটি ব্যাচ ল্যাবে পরীক্ষা করার পর আমরা বোতলজাত করি, যাতে আপনি পান নিশ্চিন্তে খাওয়ার মতো খাঁটি মধু।'],
                    'faq' => [
                        ['লিচুর মধু কি চিনির বিকল্প হিসেবে ব্যবহার করা যায়?', 'হ্যাঁ, চা বা লেবু পানিতে চিনির বদলে ব্যবহার করা যায়। তবে ডায়াবেটিস থাকলে অবশ্যই চিকিৎসকের পরামর্শ নিন।'],
                    ],
                ]),
            ],
            [
                'slug' => 'kalijira-flower-honey', 'cat' => 'honey', 'bn' => 'কালোজিরা ফুলের মধু', 'en' => 'Black Seed (Kalijira) Flower Honey',
                'short' => 'গাঢ় রঙের ঔষধি গুণসমৃদ্ধ মধু',
                'desc' => 'কালোজিরা ফুলের নেকটার থেকে তৈরি গাঢ় রঙের, ঝাঁঝালো ও ঔষধি গুণসমৃদ্ধ বিরল মধু।',
                'variants' => [
                    ['HNY-KAL-250', 250, 480, 440, 30, null],
                    ['HNY-KAL-500', 500, 880, 800, 25, 'জনপ্রিয়'],
                    ['HNY-KAL-1000', 1000, 1700, null, 12, 'পারিবারিক'],
                ],
                'images' => ['kalijira-honey.jpg', 'honey-texture.jpg', 'honey-pouring.jpg'],
                'reviews' => [
                    $r(1, 5, 'সর্দি-কাশির সময় সকালে এক চামচ খাই। স্বাদে একটু ঝাঁঝালো, তবে খুব উপকারী।', 12),
                    $r(6, 5, 'গাঢ় রং আর ঘন টেক্সচার—একদম আসল মধুর মতো।', 33),
                ],
            ],
            [
                'slug' => 'roasted-cashew-w240', 'cat' => 'nuts', 'bn' => 'প্রিমিয়াম রোস্টেড কাজুবাদাম', 'en' => 'Premium Roasted Cashew (W240)',
                'short' => 'ক্রিস্পি রোস্টেড কাজু',
                'desc' => 'ওভেনে ধীরে ভাজা ডব্লিউ২৪০ গ্রেডের আস্ত কাজুবাদাম। ক্রিস্পি, নরম মিষ্টি স্বাদ, ভ্যাকুয়াম প্যাকে সতেজ।',
                'variants' => $nutWeights('CSH', [450, null, 860, 820, 1650, null]),
                'images' => ['cashew.jpg', 'cashew-bowl.jpg'],
                'reviews' => [
                    $r(0, 5, 'কাজুগুলো খুব ফ্রেশ আর ক্রিস্পি। সব একই সাইজের, কোনো ভাঙা নেই।', 6),
                    $r(2, 5, 'ভ্যাকুয়াম প্যাকিং চমৎকার। অফিসে স্ন্যাকস হিসেবে রাখি।', 20),
                    $r(5, 4, 'ভালো কোয়ালিটি। দামটা একটু কমালে আরও ভালো হতো।', 41),
                ],
                'content' => $this->nutContent('কাজুবাদাম', 'W240 গ্রেড', 'ভিয়েতনাম ও বাংলাদেশ', 'ডিপ-রোস্টেড, লবণমুক্ত', '৬ মাস'),
            ],
            [
                'slug' => 'california-almonds', 'cat' => 'nuts', 'bn' => 'ক্যালিফোর্নিয়া প্রিমিয়াম কাঠবাদাম', 'en' => 'California Premium Almonds',
                'short' => 'আমদানি করা ফ্রেশ কাঠবাদাম',
                'desc' => 'ক্যালিফোর্নিয়া থেকে সরাসরি আমদানি করা, সমান আকারের বাছাইকৃত কাঠবাদাম। প্রাকৃতিক লালচে-বাদামি খোসাসহ।',
                'variants' => $nutWeights('ALM', [380, null, 720, 690, 1380, null]),
                'images' => ['almonds.jpg', 'cashew-bowl.jpg'],
                'reviews' => [
                    $r(3, 5, 'বাদামগুলো বড় ও ফ্রেশ। ভিজিয়ে খাই প্রতিদিন।', 8),
                    $r(4, 5, 'কোনো পোকা বা পচা বাদাম পাইনি, দারুণ মান।', 26),
                ],
                'content' => $this->nutContent('কাঠবাদাম', 'প্রিমিয়াম গ্রেড', 'ক্যালিফোর্নিয়া, যুক্তরাষ্ট্র', 'র (কাঁচা), অপ্রক্রিয়াজাত', '৮ মাস'),
            ],
            [
                'slug' => 'walnut-halves', 'cat' => 'nuts', 'bn' => 'আখরোট (হাফ)', 'en' => 'Walnut Halves',
                'short' => 'আলোকিত রঙের নরম আখরোট',
                'desc' => 'হালকা রঙের, নরম ও কচকচে আখরোটের হাফ। ওমেগা-৩ সমৃদ্ধ, বেকিং ও সরাসরি খাওয়ার জন্য আদর্শ।',
                'variants' => [
                    ['NUT-WAL-250', 250, 520, 480, 30, null],
                    ['NUT-WAL-500', 500, 990, 920, 20, 'জনপ্রিয়'],
                ],
                'images' => ['walnuts.jpg'],
                'reviews' => [$r(6, 5, 'আখরোট একদম ফ্রেশ, তিতকুটে নয়। প্যাকিং ভালো।', 15)],
                'content' => $this->nutContent('আখরোট', 'লাইট হাফ', 'চিলি ও যুক্তরাষ্ট্র', 'র, অপ্রক্রিয়াজাত', '৬ মাস'),
            ],
            [
                'slug' => 'five-seeds-mix', 'cat' => 'nuts', 'bn' => 'ফাইভ সিডস মিক্স', 'en' => 'Five Seeds Mix',
                'short' => 'কুমড়া, সূর্যমুখী ও চিয়া সহ পাঁচ বীজের মিশ্রণ',
                'desc' => 'কুমড়ার বীজ, সূর্যমুখীর বীজ, চিয়া, তিসি ও তরমুজের বীজের পুষ্টিকর মিশ্রণ—সালাদ, স্মুদি বা নাশতায়।',
                'variants' => [
                    ['NUT-SDS-250', 250, 420, null, 45, null],
                    ['NUT-SDS-500', 500, 790, 740, 25, 'জনপ্রিয়'],
                ],
                'images' => ['seeds-mix.jpg'],
                'reviews' => [$r(7, 5, 'সকালের ওটসে মিশিয়ে খাই। পাঁচ রকম বীজ একসাথে পেয়ে সুবিধা হয়েছে।', 11)],
                'content' => $this->nutContent('বীজের মিশ্রণ', 'মিক্সড গ্রেড', 'বাংলাদেশ ও ভারত', 'র ও হালকা রোস্টেড', '৬ মাস'),
            ],
            [
                'slug' => 'honey-nuts-combo', 'cat' => 'combo', 'bn' => 'হানি অ্যান্ড নাটস কম্বো', 'en' => 'Honey & Nuts Combo',
                'short' => 'মধু ও বাদামের পারিবারিক প্যাক',
                'desc' => '৫০০ গ্রাম সুন্দরবনের খলিশা মধু, ২৫০ গ্রাম কাজুবাদাম ও ২৫০ গ্রাম কাঠবাদামের সমন্বয়ে সাজানো পারিবারিক কম্বো প্যাক।',
                'variants' => [['CMB-HNN-1K', 1000, 1450, 1290, 20, null]],
                'images' => ['combo-bundle.jpg', 'combo-box.jpg'],
                'reviews' => [$r(2, 5, 'পরিবারের সবার জন্য পারফেক্ট। দামও সাশ্রয়ী।', 13), $r(4, 5, 'প্যাকেজিং খুব সুন্দর, উপহার দেওয়ার জন্য নিয়েছিলাম।', 29)],
            ],
            [
                'slug' => 'festive-gift-hamper', 'cat' => 'gift-box', 'bn' => 'উৎসব গিফট হ্যাম্পার', 'en' => 'Festive Gift Hamper',
                'short' => 'কাঠের বাক্সে মধু ও বাদামের উপহার',
                'desc' => 'হাতে তৈরি কাঠের বাক্সে খলিশা মধু, ব্রাস হানি-ডিপার, কাজু ও কাঠবাদামের রুচিশীল উপহার সংগ্রহ।',
                'variants' => [['GFT-HMP-01', 1500, 2400, 2150, 15, null]],
                'images' => ['gift-hamper.jpg', 'gift-box.jpg'],
                'reviews' => [$r(0, 5, 'ঈদে আত্মীয়দের উপহার দিয়েছি, সবাই খুব খুশি।', 17)],
            ],
        ];
    }

    /** Rich Khalisha-style PDP content for a honey product (see resources/views/storefront/products/partials). */
    private function honeyContent(array $h): array
    {
        return [
            'grade' => $h['grade'], 'subtitle' => $h['subtitle'], 'badge' => '১০০% র ও খাঁটি', 'origin_tag' => $h['origin'],
            'batch' => $h['batch'], 'moisture_note' => "আর্দ্রতা {$h['moisture']}% (ল্যাব সার্টিফাইড)",
            'metrics' => [
                ['icon' => 'water_drop', 'label' => 'প্রাকৃতিক আর্দ্রতা', 'value' => "{$h['moisture']}%"],
                ['icon' => 'local_florist', 'label' => 'পরাগকণা ঘনত্ব', 'value' => $h['pollen']],
                ['icon' => 'verified', 'label' => 'এইচএমএফ লেভেল', 'value' => $h['hmf']],
            ],
            'highlights' => [
                'eyebrow' => 'খাঁটি ও প্রাকৃতিক বৈশিষ্ট্য', 'title' => "{$h['flower']} মধুর বিশেষত্ব", 'intro' => $h['intro'],
                'cards' => [
                    ['icon' => 'forest', 'tone' => 'primary', 'label' => 'আহরণের উৎস ও রেঞ্জ', 'title' => $h['forest'], 'text' => 'প্রাকৃতিক পরিবেশে মৌমাছির স্বাভাবিক চাক থেকে সরাসরি সংগৃহীত, কোনো কৃত্রিম খাবার ছাড়া।'],
                    ['icon' => 'spa', 'tone' => 'tertiary', 'label' => 'ফুলের প্রজাতি', 'title' => "একক ফুলের {$h['flower']} নেকটার", 'text' => "মনোফ্লোরাল বৈশিষ্ট্যযুক্ত; {$h['flower']} ফুলের পরাগ ঘনত্ব বেশি হওয়ায় এর স্বাদ ও সুবাস স্বতন্ত্র।"],
                    ['icon' => 'science', 'tone' => 'secondary', 'label' => 'গুণমান যাচাই', 'title' => 'ল্যাব টেস্ট সার্টিফাইড', 'text' => 'প্রতিটি ব্যাচ আর্দ্রতা, এইচএমএফ ও কৃত্রিম চিনির জন্য পরীক্ষিত হয়ে তবেই বোতলজাত হয়।'],
                    ['icon' => 'calendar_month', 'tone' => 'primary', 'label' => 'আহরণের মৌসুম', 'title' => $h['season'], 'text' => 'সীমিত সময়ের ফলন, তাই প্রতিটি ব্যাচ সংখ্যায় কম ও সতেজ।'],
                    ['icon' => 'hourglass_bottom', 'tone' => 'tertiary', 'label' => 'সংরক্ষণ মেয়াদ', 'title' => '২ বছর (স্বাভাবিক তাপমাত্রায়)', 'text' => 'কোনো প্রিজারভেটিভ ছাড়াই প্রাকৃতিকভাবে দীর্ঘদিন ভালো থাকে।'],
                    ['icon' => 'diversity_3', 'tone' => 'secondary', 'label' => 'সোর্সিং নীতি', 'title' => 'নৈতিক ও জীববৈচিত্র্যবান্ধব', 'text' => 'মৌচাকের ক্ষতি না করে সংগ্রহ এবং সরাসরি মৌয়ালদের ন্যায্য মূল্য প্রদান।'],
                ],
            ],
            'overview' => [
                'title' => "প্রকৃতির অতুলনীয় অমৃত: খাঁটি {$h['flower']} মধু",
                'paragraphs' => [
                    "{$h['botanical']} থেকে পাওয়া যায় এই অমূল্য মধু। মুখে নেওয়ার সাথে সাথেই এক অপূর্ব প্রাকৃতিক ফুলের সুবাস ছড়িয়ে পড়ে এবং কোনো কৃত্রিম স্বাদ পাওয়া যায় না।",
                    'যেহেতু মধুটি পাস্তুরিত বা অতিরিক্ত তাপে উত্তপ্ত করা হয় না, তাই এর ভেতরের অ্যাক্টিভ এনজাইম, পলিফেনল অ্যান্টিঅক্সিডেন্ট ও প্রাকৃতিক ভিটামিন সম্পূর্ণ অটুট থাকে—যা রোগ প্রতিরোধ ক্ষমতা বাড়াতে ও হজমশক্তি উন্নত করতে ভূমিকা রাখে।',
                ],
                'sensory' => [
                    ['label' => 'মিষ্টতা (Sweetness)', 'pct' => $h['sweet'][0], 'note' => $h['sweet'][1]],
                    ['label' => 'ফুলের সুবাস (Floral Aroma)', 'pct' => $h['aroma'][0], 'note' => $h['aroma'][1]],
                    ['label' => 'ঘনত্ব ও টেক্সচার (Viscosity)', 'pct' => $h['visc'][0], 'note' => $h['visc'][1]],
                ],
                'usage_title' => 'সেবনবিধি ও আয়ুর্বেদিক পরামর্শ',
                'usage' => [
                    ['title' => 'সকালের এনার্জি ড্রিংক:', 'text' => 'প্রতিদিন সকালে এক গ্লাস কুসুম গরম পানির সাথে ১ চামচ মধু ও অর্ধেক লেবুর রস মিশিয়ে খালি পেটে পান করুন।'],
                    ['title' => 'কাশি ও শ্বাসকষ্টে:', 'text' => 'তুলসী পাতার রস বা আদার রসের সাথে আধ চা চামচ মধু মিশিয়ে দিনে ২ বার সেবন করুন।'],
                    ['title' => 'উষ্ণ দুধে:', 'text' => 'রাতে ঘুমানোর আগে হালকা গরম দুধে এক চা চামচ মধু অনিদ্রা দূর করতে সহায়ক।'],
                ],
                'warning' => 'কখনই ফুটন্ত গরম পানিতে বা ফুটন্ত দুধে মধু মেশাবেন না, এতে প্রাকৃতিক এনজাইম নষ্ট হয়।',
            ],
            'specs' => [
                ['label' => 'পণ্যের সাধারণ নাম', 'value' => "{$h['flower']} ফুলের কাঁচা মধু ({$h['subtitle']})"],
                ['label' => 'উদ্ভিদতাত্ত্বিক উৎস', 'value' => $h['botanical']],
                ['label' => 'আহরণের ভৌগোলিক অবস্থান', 'value' => "{$h['origin']}, বাংলাদেশ"],
                ['label' => 'প্রক্রিয়াজাতকরণ ধরন', 'value' => 'সম্পূর্ণ তাপহীন (Raw & Unpasteurized), মাইক্রো-কটন ফিল্টারেশন'],
                ['label' => 'বর্তমান ব্যাচ নম্বর', 'value' => $h['batch']],
                ['label' => 'আহরণের মাস ও সন', 'value' => $h['harvest']],
                ['label' => 'প্যাকেজিং উপাদান', 'value' => 'ফুড-গ্রেড বোরোসিলিকেট এয়ারটাইট গ্লাস জার (লেড-মুক্ত মেটাল ক্যাপ)'],
                ['label' => 'সংরক্ষণ নির্দেশনা', 'value' => 'স্বাভাবিক কক্ষ তাপমাত্রায় রাখুন। ফ্রিজে রাখবেন না। আর্দ্র চামচ ব্যবহার করবেন না।'],
            ],
            'lab' => [
                'title' => 'ল্যাব পরীক্ষিত বিশুদ্ধতার নিশ্চয়তা',
                'intro' => 'মধুবন প্রতিটি ব্যাচের মধু সংগ্রহের পরপরই অনুমোদিত ল্যাবে রাসায়নিক ও মাইক্রোবায়োলজিক্যাল টেস্ট সম্পন্ন করে। সুক্রোজ, ফ্রুক্টোজ ও গ্লুকোজের অনুপাত প্রকৃতি প্রদত্ত আদর্শ মানে বিদ্যমান।',
                'tests' => [
                    ['label' => 'আর্দ্রতার মান (Moisture)', 'value' => "{$h['moisture']}%", 'note' => 'মানদণ্ড: < ২০% (খাঁটি কাঁচা মধুর আদর্শ)'],
                    ['label' => 'কৃত্রিম চিনি / সি-৪ সুগার', 'value' => '০% (নেগেটিভ)', 'note' => 'আইসোটোপ রেশিও মাস স্পেকট্রোমেট্রি টেস্ট'],
                    ['label' => 'এইচএমএফ মান (HMF)', 'value' => $h['hmf'], 'note' => 'আন্তর্জাতিক সর্বোচ্চ সীমা: ৮০ mg/kg'],
                    ['label' => 'পরাগকণা ঘনত্ব', 'value' => $h['pollen'], 'note' => 'মেলিসোপ্যালিনোলজি মাইক্রোস্কোপ টেস্ট'],
                ],
                'file_note' => "ব্যাচ {$h['batch']}",
            ],
            'story' => [
                'image' => $this->publicPath('story-mouyal.jpg'), 'eyebrow' => $h['story_eyebrow'], 'title' => $h['story_title'],
                'paragraphs' => $h['story'], 'place' => $h['origin'], 'trade' => '১০০% ফেয়ার ট্রেড ও সরাসরি সংগ্রহ',
            ],
            'faq' => $h['faq'],
        ];
    }

    /** Lighter PDP content for nuts & seeds (no harvest/lab data). */
    private function nutContent(string $item, string $grade, string $origin, string $process, string $shelf): array
    {
        return [
            'grade' => "প্রিমিয়াম {$item}", 'subtitle' => null, 'badge' => 'ফ্রেশ ও বাছাইকৃত', 'origin_tag' => $origin,
            'highlights' => [
                'eyebrow' => 'গুণমান ও বৈশিষ্ট্য', 'title' => "{$item}-এর বিশেষত্ব", 'intro' => 'হাতে বাছাই করা, সমান আকারের ও সতেজ—প্রতিটি দানায় পুষ্টি আর স্বাদের নিশ্চয়তা।',
                'cards' => [
                    ['icon' => 'public', 'tone' => 'primary', 'label' => 'উৎস', 'title' => $origin, 'text' => 'নির্ভরযোগ্য সরবরাহকারীর কাছ থেকে সরাসরি সংগৃহীত ও আমাদের গুদামে মান যাচাইকৃত।'],
                    ['icon' => 'workspace_premium', 'tone' => 'tertiary', 'label' => 'গ্রেড', 'title' => $grade, 'text' => 'ভাঙা, পোকা-ধরা বা বিবর্ণ দানা বাদ দিয়ে সমান আকারে বাছাই করা।'],
                    ['icon' => 'local_fire_department', 'tone' => 'secondary', 'label' => 'প্রক্রিয়া', 'title' => $process, 'text' => 'কোনো কৃত্রিম রং, প্রিজারভেটিভ বা ফ্লেভার যোগ করা হয় না।'],
                    ['icon' => 'inventory_2', 'tone' => 'primary', 'label' => 'প্যাকেজিং', 'title' => 'ভ্যাকুয়াম সিল্ড পাউচ', 'text' => 'ফুড-গ্রেড এয়ারটাইট পাউচে প্যাক করা, যাতে সতেজতা ও কচকচে ভাব বজায় থাকে।'],
                    ['icon' => 'hourglass_bottom', 'tone' => 'tertiary', 'label' => 'সংরক্ষণ মেয়াদ', 'title' => "{$shelf} (প্যাকিং থেকে)", 'text' => 'ঠান্ডা ও শুকনো স্থানে সংরক্ষণ করুন। খোলার পর ঢাকনা শক্ত করে লাগান।'],
                    ['icon' => 'favorite', 'tone' => 'secondary', 'label' => 'পুষ্টিগুণ', 'title' => 'প্রোটিন ও স্বাস্থ্যকর ফ্যাট', 'text' => 'ভিটামিন, খনিজ ও স্বাস্থ্যকর ফ্যাটে ভরপুর—প্রতিদিনের নাশতায় আদর্শ।'],
                ],
            ],
            'overview' => [
                'title' => "প্রতিদিনের পুষ্টিতে খাঁটি {$item}",
                'paragraphs' => ["মধুবনের {$item} সরাসরি নির্ভরযোগ্য উৎস থেকে আনা, হাতে বাছাই করা এবং ভ্যাকুয়াম প্যাকে সতেজ রেখে আপনার কাছে পৌঁছে দেওয়া হয়।", 'ঘুম থেকে উঠে নাশতায়, সালাদে, দুধ বা দইয়ের সাথে, কিংবা মধুর সাথে মিশিয়ে—যেভাবেই খান, পাবেন প্রাকৃতিক স্বাদ ও পুষ্টি।'],
                'sensory' => [],
                'usage_title' => 'খাওয়ার পরামর্শ',
                'usage' => [
                    ['title' => 'প্রতিদিন:', 'text' => 'সকালের নাশতায় এক মুঠো (২৫–৩০ গ্রাম) খাওয়া যথেষ্ট।'],
                    ['title' => 'ভিজিয়ে খেলে:', 'text' => 'রাতে পানিতে ভিজিয়ে সকালে খেলে হজম সহজ হয়।'],
                ],
            ],
            'specs' => [
                ['label' => 'পণ্যের নাম', 'value' => $item],
                ['label' => 'গ্রেড', 'value' => $grade],
                ['label' => 'উৎস', 'value' => $origin],
                ['label' => 'প্রক্রিয়া', 'value' => $process],
                ['label' => 'প্যাকেজিং', 'value' => 'ভ্যাকুয়াম সিল্ড ফুড-গ্রেড পাউচ'],
                ['label' => 'সংরক্ষণ', 'value' => "ঠান্ডা ও শুকনো স্থানে রাখুন। মেয়াদ: {$shelf}।"],
            ],
            'faq' => [
                ['এটি কি সরাসরি খাওয়ার জন্য প্রস্তুত?', 'হ্যাঁ, পণ্যটি সরাসরি খাওয়ার জন্য প্রস্তুত। চাইলে ভিজিয়েও খেতে পারেন।'],
                ['খোলার পর কীভাবে সংরক্ষণ করব?', 'পাউচ শক্ত করে বন্ধ করে বা এয়ারটাইট কৌটায় ঠান্ডা, শুকনো জায়গায় রাখুন।'],
            ],
        ];
    }
}
