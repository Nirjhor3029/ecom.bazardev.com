<?php

namespace App\Modules\Orders\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Http\Resources\Store\OrderResource;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** CHK-R8/SHP-R7: guest tracking always needs order_no + phone together (order_no alone is guessable by design). */
    public function show(Request $request, string $orderNo): JsonResponse
    {
        $request->validate(['phone' => ['required', 'string']]);

        $order = Order::query()
            ->where('order_no', $orderNo)
            ->whereJsonContains('shipping_address->phone', $request->string('phone')->toString())
            ->with('items')
            ->firstOrFail();

        return response()->json(['data' => new OrderResource($order)]);
    }
}
