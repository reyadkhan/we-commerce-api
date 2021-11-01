<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OrderService $orderService,
        private UserService $userService
    )
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $totalProduct = $this->productService->totalProductCount();
        $newOrder = $this->orderService->todaysOrderCount();
        $newUser = $this->userService->todaysUserCount();
        return response()->json(compact('totalProduct', 'newOrder', 'newUser'));
    }
}
