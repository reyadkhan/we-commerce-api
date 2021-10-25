<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class SortController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function sortProduct(Request $request)
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        $sortBy = $request->only('price');
        return ProductResource::collection(
            $this->productService->sortByColumns($sortBy, $page, $perPage));
    }
}
