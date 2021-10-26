<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OrderService $orderService
    ) {
        $this->middleware('auth:sanctum')->only('searchOrder', 'searchOrderByStatus');
    }

    public function searchProduct(Request $request): AnonymousResourceCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return ProductResource::collection(
            $this->productService->searchByName($request->name ?? '', $page, $perPage));
    }

    public function searchOrder(string $orderId): OrderResource
    {
        return new OrderResource($this->orderService->findByOrderId($orderId));
    }

    public function searchOrderByStatus(string $status): AnonymousResourceCollection
    {
        $status = ucfirst(strtolower($status));
        Validator::make(compact('status'), [
            'status' => 'required|string|in:' . implode(',', OrderStatus::getValues())
        ])->validate();
        return OrderResource::collection(
            $this->orderService->findAllByStatus(OrderStatus::fromValue($status)));
    }
}
