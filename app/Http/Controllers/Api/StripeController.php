<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Helpers\ApiResponse;
use App\Models\Order;
use App\Models\Product;
use GPBMetadata\Google\Protobuf\Api;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{

    public function checkout(array $products, int $orderId)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                    // Stripe expects the amount in cents
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
                'order_id' => $orderId,
            ]
        ]);

        return $session->url;
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');

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

            Order::where('id', $orderId)->update(['status' => 'paid']);
        }

        return ApiResponse::success('Webhook handled successfully');
    }
}
