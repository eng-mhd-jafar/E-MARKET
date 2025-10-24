<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Helpers\ApiResponse;
use App\Models\Product;
use GPBMetadata\Google\Protobuf\Api;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{

    public function checkout(Request $request)
    {
        $products = $request->input('products', []);
        if (empty($products)) {
            return response()->json(['error' => 'No products provided'], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                    'unit_amount' => $product['price'],
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
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function index()
    {
        $products = Product::all();
        return ApiResponse::successWithData(ProductResource::collection($products), 200);
    }
}

