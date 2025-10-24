<?php

namespace App\Repositories;
use App\Models\Order;
;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{

    public function store($orderData, $total_Price): bool
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'phone_number' => $orderData['phone_number'],
                'location' => $orderData['location'] ?? null,
                'shipping_required' => $orderData['shipping_required'],
                'payment_method' => $orderData['payment_method'],
                'total_price' => $total_Price,
            ]);

            foreach ($orderData["order_items"] as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return false;
        }
    }
}