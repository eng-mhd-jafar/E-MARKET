<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $stripeUrl = $this->orderService->store(($request->validated())->toArray());
        if ($stripeUrl) {
            return ApiResponse::successWithData($stripeUrl, 'Order placed successfully');
        } else {
            return ApiResponse::error('Failed to place order');
        }
    }

    public function index()
    {
        return Product::all();
    }
}
