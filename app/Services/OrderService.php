<?php

namespace App\Services;

use App\Core\Domain\Interfaces\OrderRepositoryInterface;
use App\Core\Domain\Interfaces\PaymentGatewayInterface;

class OrderService
{
    protected $orderRepositoryInterface;
    protected $paymentGatewayInterface;

    public function __construct(OrderRepositoryInterface $orderRepositoryInterface, PaymentGatewayInterface $paymentGatewayInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->paymentGatewayInterface = $paymentGatewayInterface;
    }

    public function store(array $OrderData)
    {
        $total_price = 0;

        foreach ($OrderData['items'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        $order = $this->orderRepositoryInterface->store($OrderData, $total_price);
        if (!$order) {
            return false;
        }
        $url = $this->paymentGatewayInterface->checkout($OrderData['items'], $order->id);
        return $url;
    }
}
