<?php

namespace App\Modules\Orders\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Actions\BookShipmentAction;
use App\Modules\Orders\Http\Resources\Admin\OrderResource;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\OrderStateMachine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('orders.order.view');

        $query = Order::query()->latest();

        if ($status = $request->string('filter.status')->toString()) {
            $query->where('status', $status);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where(fn ($q2) => $q2->where('order_no', 'like', "%{$q}%")
                ->orWhereJsonContains('shipping_address->phone', $q));
        }

        return $this->paginated($query->paginate($request->integer('per_page', 20)), OrderResource::class);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('orders.order.view');

        return response()->json(['data' => new OrderResource($order->load(['items', 'statusHistory']))]);
    }

    public function transition(Request $request, Order $order, OrderStateMachine $machine): JsonResponse
    {
        $this->authorize('orders.order.update_status');

        $request->validate(['to' => ['required', 'string'], 'note' => ['nullable', 'string']]);

        $order = $machine->transition(
            $order,
            $request->string('to')->toString(),
            $request->string('note')->toString() ?: null,
            'staff',
            $request->user()->id,
        );

        return response()->json(['data' => new OrderResource($order)]);
    }

    public function bookShipment(Order $order, BookShipmentAction $action): JsonResponse
    {
        $this->authorize('shipping.shipment.book');

        $shipment = $action->execute($order);

        return response()->json(['data' => $shipment], 201);
    }
}
