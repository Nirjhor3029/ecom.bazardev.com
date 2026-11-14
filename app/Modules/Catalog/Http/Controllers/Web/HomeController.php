<?php

namespace App\Modules\Catalog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')->where('is_active', true)
            ->orderBy('sort')->limit(6)->get();

        $products = Product::query()
            ->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media'])
            ->latest()->limit(8)->get();

        return view('storefront.home', compact('categories', 'products'));
    }
}
