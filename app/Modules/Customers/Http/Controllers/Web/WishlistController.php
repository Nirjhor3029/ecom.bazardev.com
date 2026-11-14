<?php

namespace App\Modules\Customers\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Services\WishlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(WishlistService $wishlist): View
    {
        $customer = Auth::guard('customer')->user();
        $ids = $wishlist->ids($customer);

        $products = Product::query()
            ->whereIn('id', $ids)->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media'])
            ->get();

        return view('storefront.wishlist', compact('products'));
    }

    public function toggle(Product $product, WishlistService $wishlist): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        $wishlist->toggle($customer, $product->id);

        return back();
    }
}
