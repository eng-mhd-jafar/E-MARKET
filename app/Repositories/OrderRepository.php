<?php

namespace App\Repositories;

use App\Core\Domain\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function store($orderData, $total_Price)
    {

        try {
            DB::beginTransaction();

            $order = $this->order->create([
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

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


    public function update($order_id)
    {
        try {
            $this->order->where('id', $order_id)->update(['status' => 'paid']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
