<?php

namespace App\Http\Controllers;

use App\DTOs\OrderCreateDTO;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private OrderService $service)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): AnonymousResourceCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return OrderResource::collection($this->service->paginate($page, $perPage));
    }

    public function show(int $id): OrderResource
    {
        return new OrderResource($this->service->findById($id));
    }

    public function store(OrderRequest $request): OrderResource
    {
        $dtos = $this->mapToDTO(...$request->validated());
        return new OrderResource($this->service->create($dtos));
    }

    public function update(int $id, OrderRequest $request)
    {
        $dtos = $this->mapToDTO(...$request->validated());
        return new OrderResource($this->service->update($id, $dtos));
    }

    public function destroy(int $id)
    {
        $this->service->deleteById($id);
        return response('OK');
    }

    /**
     * @param array $products
     * @return array[OrderCreateDTO]
     */
    private function mapToDTO(array $products): array
    {
        $dtos = [];

        foreach ($products as $product) {
            $dtos[] = new OrderCreateDTO(...$product);
        }
        return $dtos;
    }
}
