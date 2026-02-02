<?php

namespace App\Core\Domain\Interfaces;

interface OrderRepositoryInterface
{
    public function store($orderData, $total_Price);
    public function update($order_id);
}
