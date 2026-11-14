<?php

namespace App\Modules\Orders\Support;

use App\Modules\Orders\Models\Order;

class OrderNumberGenerator
{
    /**
     * ORD-YYMM-NNNNNN. Sequential-looking but guessable by design (SRS
     * CHK-R8) — guest tracking always requires phone + order_no together.
     *
     * NOTE: the monthly counter below is a best-effort count, not a locked
     * sequence — under heavy concurrent checkout it could in rare cases
     * produce the same visible number for two orders in the same second.
     * That's a cosmetic risk only; stock correctness (NFR-2) does not
     * depend on this number, it comes from the row-locked reservation in
     * InventoryService.
     */
    public static function generate(): string
    {
        $prefix = 'ORD-'.now()->format('ym').'-';

        $seq = Order::query()->where('order_no', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $seq, 6, '0', STR_PAD_LEFT);
    }
}
