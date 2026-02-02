<?php

namespace App\Http\Controllers\Api;

use App\Core\Domain\Interfaces\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function checkout(array $products, int $orderId)
    {
        return $this->paymentGateway->checkOut($products, $orderId);
    }

    public function handleWebhook(Request $request)
    {
        $sig = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        return $this->paymentGateway->handleWebhook($payload, $sig);
    }

}
