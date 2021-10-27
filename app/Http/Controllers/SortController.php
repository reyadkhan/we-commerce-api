<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Services\ProductService;
use Illuminate\Http\Request;

class SortController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function sortProduct(Request $request): ProductCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        $sortBy = $request->only('price');
        return new ProductCollection($this->productService->sortByColumns($sortBy, $page, $perPage));
    }
}
