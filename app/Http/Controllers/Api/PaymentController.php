<?php

namespace App\Http\Controllers\Api;

use App\Core\Domain\Interfaces\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentGatewayInterface $paymentGateway){}

    public function checkout(Request $request)
    {
        $products = $request->input('items', []);
        $orderId = (int) $request->input('order_id');

        return $this->paymentGateway->checkOut($products, $orderId);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature', '');

        return $this->paymentGateway->handleWebhook($payload, $sig);
    }
}

