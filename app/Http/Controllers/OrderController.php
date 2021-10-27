<?php

namespace App\Http\Controllers;

use App\DTOs\OrderCreateDTO;
use App\Enums\OrderStatus;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(private OrderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->only('updateStatus');
    }

    public function index(): OrderCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return new OrderCollection($this->service->paginate($page, $perPage));
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

    public function update(int $id, OrderRequest $request): OrderResource
    {
        $dtos = $this->mapToDTO(...$request->validated());
        return new OrderResource($this->service->update($id, $dtos));
    }

    public function destroy(int $id): Response
    {
        $this->service->deleteById($id);
        return response('OK');
    }

    public function updateStatus(int $id, string $status): Response
    {
        $status = ucfirst(strtolower($status));
        Validator::make(compact('status'), [
            'status' => 'required|string|in:' . implode(',', OrderStatus::getUpdateAbleValues())
        ])->validate();
        $this->service->updateStatus($id, OrderStatus::fromValue($status));
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
