<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchController
{
    public function __construct(private ProductService $productService) {}

    public function searchProduct(Request $request): AnonymousResourceCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return ProductResource::collection(
            $this->productService->searchByName($request->name ?? '', $page, $perPage));
    }
}
