<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Helpers\ApiResponse;
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
        $result = $this->orderService->store($request->toArray());
        if ($result) {
            return ApiResponse::success('Ordered successfully');
        } else {
            return ApiResponse::error('Failed to place order', 500);
        }
    }
}
