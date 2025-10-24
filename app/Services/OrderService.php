<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\OrderRepository;

class OrderService
{
    protected $OrderRepository;


    public function __construct(OrderRepository $OrderRepository)
    {
        $this->OrderRepository = $OrderRepository;
    }

    public function Store(array $OrderData)
    {
        $total_price = 0;

        foreach ($OrderData['items'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        return $this->OrderRepository->store($OrderData, $total_price);
    }
}
