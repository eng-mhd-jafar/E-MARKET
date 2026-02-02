<?php

namespace App\Core\Domain\Interfaces;

interface PaymentGatewayInterface
{
    public function checkOut(array $products, int $order_id);

    public function handleWebhook(string $payload, string $sig);
}

