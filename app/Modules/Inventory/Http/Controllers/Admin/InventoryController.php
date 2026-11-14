<?php

namespace App\Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\Admin\AdjustStockRequest;
use App\Modules\Inventory\Http\Resources\Admin\InventoryItemResource;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\StockMovement;
use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('inventory.item.view');

        $query = InventoryItem::query()->with(['variant.product']);

        if ($q = $request->string('q')->toString()) {
            $query->where(function ($query) use ($q) {
                $query->whereHas('variant', fn ($v) => $v->where('sku', 'like', "%{$q}%")
                    ->orWhereHas('product', fn ($p) => $p->whereRaw("JSON_EXTRACT(name, '$.bn') LIKE ?", ["%{$q}%"])
                        ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$q}%"])));
            });
        }

        if ($request->boolean('low_stock_only')) {
            $query->whereColumn('on_hand', '<=', 'low_stock_threshold');
        }

        return $this->paginated($query->paginate($request->integer('per_page', 20)), InventoryItemResource::class);
    }

    public function adjust(AdjustStockRequest $request, InventoryItem $item, InventoryService $inventory): JsonResponse
    {
        $inventory->adjust($item->variant_id, $request->integer('delta'), $request->string('reason')->toString(), $request->user()->id);

        return response()->json(['data' => new InventoryItemResource($item->refresh()->load('variant.product'))]);
    }

    public function movements(InventoryItem $item): JsonResponse
    {
        $this->authorize('inventory.item.view');

        $movements = StockMovement::query()->where('inventory_item_id', $item->id)->latest('created_at')->limit(50)->get();

        return response()->json(['data' => $movements]);
    }
}
