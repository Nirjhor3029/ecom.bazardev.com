<?php

namespace App\Modules\Shipping\Contracts;

use App\Modules\Orders\Models\Shipment;

interface Courier
{
    /** @return array{consignment_id: ?string, tracking_code: ?string, label_url: ?string} */
    public function book(Shipment $shipment): array;

    public function cancel(Shipment $shipment): bool;

    /** @return array{status: string} normalized: picked_up|in_transit|delivered|returned|failed|hold */
    public function track(Shipment $shipment): array;
}
