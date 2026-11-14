<?php

namespace App\Modules\Customers\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Customers\Http\Resources\Admin\CustomerResource;
use App\Modules\Customers\Models\Customer;
use App\Modules\Orders\Http\Resources\Admin\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('customers.customer.view');

        $query = Customer::query()->withCount('orders')->latest();

        if ($q = $request->string('q')->toString()) {
            $query->where(fn ($q2) => $q2->where('phone', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"));
        }

        if ($request->boolean('blocked_only')) {
            $query->where('is_blocked', true);
        }

        return $this->paginated($query->paginate($request->integer('per_page', 20)), CustomerResource::class);
    }

    public function show(Customer $customer): JsonResponse
    {
        $this->authorize('customers.customer.view');

        $customer->loadCount('orders');

        return response()->json([
            'data' => array_merge(
                (new CustomerResource($customer))->toArray(request()),
                [
                    'addresses' => $customer->addresses,
                    'orders' => OrderResource::collection($customer->orders()->latest()->limit(20)->get()),
                ],
            ),
        ]);
    }

    public function block(Customer $customer): JsonResponse
    {
        $this->authorize('customers.customer.block');

        $customer->update(['is_blocked' => true]);

        return response()->json(['data' => new CustomerResource($customer)]);
    }

    public function unblock(Customer $customer): JsonResponse
    {
        $this->authorize('customers.customer.block');

        $customer->update(['is_blocked' => false]);

        return response()->json(['data' => new CustomerResource($customer)]);
    }
}
