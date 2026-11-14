<?php

namespace App\Modules\Shipping\Drivers;

use App\Modules\Orders\Models\Shipment;
use App\Modules\Shipping\Contracts\Courier;

/**
 * SRS §11.2 open item: which courier (Steadfast/Pathao/RedX) is wired first
 * is undecided. This driver "books" locally (no external API) so the order
 * flow still completes end to end — swap the AppServiceProvider binding
 * once a courier is chosen.
 */
class NullCourier implements Courier
{
    public function book(Shipment $shipment): array
    {
        return [
            'consignment_id' => 'NULL-'.$shipment->id,
            'tracking_code' => 'TRK-'.str_pad((string) $shipment->id, 6, '0', STR_PAD_LEFT),
            'label_url' => null,
        ];
    }

    public function cancel(Shipment $shipment): bool
    {
        return true;
    }

    public function track(Shipment $shipment): array
    {
        return ['status' => $shipment->status];
    }
}
