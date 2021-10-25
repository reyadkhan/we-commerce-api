<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(private ProductService $service)
    {
        $this->middleware(['auth:sanctum', 'admin'])->except('index', 'show');
    }

    public function index(): AnonymousResourceCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return ProductResource::collection($this->service->paginate($page, $perPage));
    }

    public function show(int $id): ProductResource
    {
        return new ProductResource($this->service->findById($id));
    }

    public function store(ProductRequest $request): ProductResource
    {
        $product = new ProductDTO(...$request->validated());
        return new ProductResource($this->service->create($product));
    }

    public function update(ProductRequest $request, int $id): ProductResource
    {
        $product = new ProductDTO(...$request->validated());
        return new ProductResource($this->service->update($id, $product));
    }

    public function destroy(int $id): Response
    {
        $this->service->deleteById($id);
        return response('OK');
    }
}
