<?php

namespace App\Services;

use App\Core\Domain\Interfaces\OrderRepositoryInterface;
use App\Core\Domain\Interfaces\PaymentGatewayInterface;
use App\Http\Helpers\ApiResponse;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService implements PaymentGatewayInterface
{
    protected string $apikey;
    protected OrderRepositoryInterface $orderRepositoryInterface;

    public function __construct(string $apikey, OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->apikey = $apikey;
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        Stripe::setApiKey($this->apikey);
    }

    public function checkOut(array $products, int $order_id)
    {
        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                    'unit_amount' => (int) ($product['price'] * 100),
                ],
                'quantity' => $product['quantity'],
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url('/success'),
            'cancel_url' => url('/cancel'),
            'metadata' => [
                'order_id' => $order_id,
            ]
        ]);
        return $session->url;
    }

    public function handleWebhook(string $payload, string $sig)
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Invalid signature', 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;

            $this->orderRepositoryInterface->update($orderId);
        }

        return ApiResponse::success('Webhook handled successfully');
    }
}