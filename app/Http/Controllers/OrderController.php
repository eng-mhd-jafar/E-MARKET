<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Helpers\ApiResponse;
use App\Models\Product;
use App\Services\OrderService;


class OrderController extends Controller
{
    protected $orderService;


    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(CreateOrderRequest $request)
    {
        $StripeUrl = $this->orderService->store($request->toArray());
        if ($StripeUrl) {
            return ApiResponse::successWithData($StripeUrl, 'Order placed successfully');
        } else {
            return ApiResponse::error('Failed to place order');
        }
    }

    public function index()
    {
        return Product::all();
    }
}
