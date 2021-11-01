<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductCollection;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OrderService $orderService
    ) {
        $this->middleware('auth:sanctum')->only('searchOrder', 'searchOrderByStatus');
    }

    public function searchProduct(Request $request): ProductCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return new ProductCollection(
            $this->productService->searchByName($request->name ?? '', $page, $perPage));
    }

    public function searchOrder(string $orderId): OrderResource
    {
        return new OrderResource($this->orderService->findByOrderId($orderId));
    }

    public function searchOrderByStatus(string $status): OrderCollection
    {
        $status = ucfirst(strtolower($status));
        Validator::make(compact('status'), [
            'status' => 'required|string|in:' . implode(',', OrderStatus::getValues())
        ])->validate();
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return new OrderCollection(
            $this->orderService->findAllByStatus(OrderStatus::fromValue($status), $page, $perPage));
    }
}
