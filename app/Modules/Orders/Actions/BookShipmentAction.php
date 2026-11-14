<?php

namespace App\Modules\Orders\Actions;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Shipment;
use App\Modules\Shipping\Contracts\Courier;

/** ORD-5/SHP-R1: book a courier consignment for a packed order. */
class BookShipmentAction
{
    public function __construct(protected Courier $courier) {}

    public function execute(Order $order, string $courierName = 'default'): Shipment
    {
        $shipment = Shipment::query()->create([
            'order_id' => $order->id,
            'courier' => $courierName,
            'status' => 'pending',
            'cod_amount' => $order->due_total,
        ]);

        $result = $this->courier->book($shipment);

        $shipment->update([
            'consignment_id' => $result['consignment_id'],
            'tracking_code' => $result['tracking_code'],
            'label_url' => $result['label_url'],
            'status' => 'booked',
            'booked_at' => now(),
        ]);

        return $shipment;
    }
}
