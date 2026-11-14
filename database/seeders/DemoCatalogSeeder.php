<?php

namespace Database\Seeders;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Catalog\Models\ProductMedia;
use App\Modules\Core\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Demo storefront data (Madhuban honey & nuts). Idempotent: keyed on slug / sku.
 * Prices are stored in paisa (minor unit). Run: php artisan db:seed --class=DemoCatalogSeeder
 * Requires the honey attribute set — `php artisan store:install --type=honey_nuts` first.
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

        // [slug, category, bn name, en name, short bn, price, sale price|null, grams, sku, stock, image]
        $products = [
            ['sundarban-khalisha-honey', 'honey', 'সুন্দরবনের খলিশা মধু', 'Sundarban Khalisha Honey', '১০০% খাঁটি র খলিশা মধু', 850, 750, 500, 'HNY-KHA-500', 40, 'khalisha-honey'],
            ['mustard-blossom-honey', 'honey', 'সরিষা ফুলের প্রাকৃতিক মধু', 'Mustard Blossom Honey', 'সরিষা ফুলের সুগন্ধি মধু', 650, 580, 500, 'HNY-MUS-500', 35, 'mustard-honey'],
            ['litchi-blossom-honey', 'honey', 'লিচু ফুলের খাঁটি মধু', 'Litchi Blossom Honey', 'দিনাজপুরের লিচু বাগানের মধু', 700, 620, 500, 'HNY-LIT-500', 25, 'litchi-honey'],
            ['roasted-cashew-w240', 'nuts', 'প্রিমিয়াম রোস্টেড কাজুবাদাম', 'Premium Roasted Cashew (W240)', 'ক্রিস্পি রোস্টেড কাজু', 450, null, 250, 'NUT-CSH-250', 60, 'cashew'],
            ['california-almonds', 'nuts', 'ক্যালিফোর্নিয়া প্রিমিয়াম কাঠবাদাম', 'California Premium Almonds', 'আমদানি করা ফ্রেশ কাঠবাদাম', 380, null, 250, 'NUT-ALM-250', 50, 'almonds'],
            ['walnut-halves', 'nuts', 'আখরোট (হাফ)', 'Walnut Halves', 'আলোকিত রঙের নরম আখরোট', 520, 480, 250, 'NUT-WAL-250', 30, 'walnuts'],
            ['honey-nuts-combo', 'combo', 'হানি অ্যান্ড নাটস কম্বো', 'Honey & Nuts Combo', 'মধু ও বাদামের পারিবারিক প্যাক', 1450, 1290, 1000, 'CMB-HNN-1K', 20, 'combo-bundle'],
            ['festive-gift-hamper', 'gift-box', 'উৎসব গিফট হ্যাম্পার', 'Festive Gift Hamper', 'কাঠের বাক্সে মধু ও বাদামের উপহার', 2400, 2150, 1500, 'GFT-HMP-01', 15, 'gift-hamper'],
            ['five-seeds-mix', 'nuts', 'ফাইভ সিডস মিক্স', 'Five Seeds Mix', 'কুমড়া, সূর্যমুখী ও চিয়া সহ পাঁচ বীজের মিশ্রণ', 420, null, 250, 'NUT-SDS-250', 45, 'seeds-mix'],
        ];

        foreach ($products as [$slug, $cat, $bn, $en, $short, $price, $sale, $grams, $sku, $stock, $img]) {
            $product = Product::query()->updateOrCreate(['slug' => $slug], [
                'attribute_set_id' => $set->id,
                'category_id' => $categories[$cat]->id,
                'name' => ['bn' => $bn, 'en' => $en],
                'short_desc' => ['bn' => $short, 'en' => $short],
                'description' => ['bn' => $short.' — সরাসরি বিশ্বস্ত উৎস থেকে সংগৃহীত।', 'en' => $en],
                'type' => 'simple',
                'status' => 'active',
                'published_at' => now(),
            ]);

            $variant = $product->variants()->updateOrCreate(['sku' => $sku], [
                'price' => $price * 100,
                'sale_price' => $sale ? $sale * 100 : null,
                'weight_grams' => $grams,
                'is_default' => true,
                'is_active' => true,
            ]);

            InventoryItem::query()->updateOrCreate(['variant_id' => $variant->id], ['on_hand' => $stock]);

            ProductMedia::query()->updateOrCreate(
                ['product_id' => $product->id, 'media_id' => $this->media($img, $bn)->id],
                ['sort' => 0, 'is_primary' => true],
            );
        }
    }

    /** Copy a bundled demo image onto the public disk and register it in the media library. */
    private function media(string $name, string $alt): Media
    {
        $path = "media/demo/{$name}.jpg";
        $source = database_path("seeders/demo-images/{$name}.jpg");

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        return Media::query()->firstOrCreate(
            ['disk' => 'public', 'path' => $path],
            ['original_name' => "{$name}.jpg", 'mime' => 'image/jpeg', 'size' => filesize($source), 'alt' => $alt],
        );
    }
}
