<?php

namespace App\Modules\Customers\Services;

use App\Modules\Customers\Models\Customer;
use App\Modules\Customers\Models\Wishlist;
use Illuminate\Support\Facades\Session;

/**
 * MKT-6: guest wishlist lives in the session (array of product ids, `wishlist`
 * key) since the `wishlists` table requires a real customer_id. Once a guest
 * verifies OTP (CheckoutController::verifyOtp), merge() folds the session
 * list into the DB and the session copy is cleared.
 */
class WishlistService
{
    protected const string SESSION_KEY = 'wishlist';

    /** @return int[] product ids */
    public function ids(?Customer $customer): array
    {
        if ($customer) {
            return Wishlist::query()->where('customer_id', $customer->id)->pluck('product_id')->all();
        }

        return Session::get(self::SESSION_KEY, []);
    }

    public function has(?Customer $customer, int $productId): bool
    {
        return in_array($productId, $this->ids($customer), true);
    }

    /** @return bool true if now wishlisted, false if just removed */
    public function toggle(?Customer $customer, int $productId): bool
    {
        if ($customer) {
            $existing = Wishlist::query()->where('customer_id', $customer->id)->where('product_id', $productId)->first();

            if ($existing) {
                $existing->delete();

                return false;
            }

            Wishlist::query()->create(['customer_id' => $customer->id, 'product_id' => $productId]);

            return true;
        }

        $ids = Session::get(self::SESSION_KEY, []);

        if (in_array($productId, $ids, true)) {
            Session::put(self::SESSION_KEY, array_values(array_diff($ids, [$productId])));

            return false;
        }

        $ids[] = $productId;
        Session::put(self::SESSION_KEY, array_values(array_unique($ids)));

        return true;
    }

    /** Called right after a guest becomes an authenticated customer (OTP verify). */
    public function merge(Customer $customer): void
    {
        $sessionIds = Session::get(self::SESSION_KEY, []);

        foreach ($sessionIds as $productId) {
            Wishlist::query()->firstOrCreate(['customer_id' => $customer->id, 'product_id' => $productId]);
        }

        Session::forget(self::SESSION_KEY);
    }
}
