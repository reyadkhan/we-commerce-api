<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OrderService $orderService
    ) {
        $this->middleware('auth:sanctum')->only('searchOrder');
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
}
