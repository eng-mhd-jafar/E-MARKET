<?php

namespace App\Repositories;
use App\Http\Controllers\StripeController;
use App\Models\Order;
;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class OrderRepository
{

    public function store($orderData, $total_Price)
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

            foreach ($orderData["items"] as $item) {
                $order->items()->create($item);
            }
            $stripe = new StripeController();
            $url = $stripe->checkout($orderData['items'], $order->id);

            DB::commit();

            return $url;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
