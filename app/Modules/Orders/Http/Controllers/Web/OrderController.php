<?php

namespace App\Modules\Orders\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function success(string $orderNo): View
    {
        $order = Order::query()->where('order_no', $orderNo)->with('items')->firstOrFail();

        return view('storefront.orders.success', compact('order'));
    }

    public function track(Request $request): View
    {
        $order = null;
        $notFound = false;

        if ($request->filled('order_no') && $request->filled('phone')) {
            $order = Order::query()
                ->where('order_no', $request->string('order_no')->toString())
                ->whereJsonContains('shipping_address->phone', $request->string('phone')->toString())
                ->with(['items', 'statusHistory'])
                ->first();

            $notFound = ! $order;
        }

        return view('storefront.orders.track', compact('order', 'notFound'));
    }
}
