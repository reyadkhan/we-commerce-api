<?php

namespace App\Services\Impl;

use App\Exceptions\StockIsNotAvailableException;
use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;

class OrderServiceImpl extends BaseServiceImpl implements OrderService
{
    public function __construct(
        OrderRepository $repository,
        private ProductRepository $productRepo
    )
    {
        $this->repository = $repository;
    }

    public function findById(int $id): Order
    {
        return $this->repository->findByIdOrFail($id);
    }

    /**
     * @param User|Authenticatable $user
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function create(User|Authenticatable $user, array $orderCreateInfo): Order
    {
        $productIdQuantityMap = $this->extractProductIdQuantityMap($orderCreateInfo);
        $productInfo = $this->generateProductsOrderInfo($productIdQuantityMap);
        return $this->repository->create($user, $productInfo);
    }

    /**
     * @param int $id order identifier
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function update(int $id, array $orderCreateInfo): Order
    {
        $order = $this->repository->findByIdOrFail($id);
        $productIdQuantityMap = $this->extractProductIdQuantityMap($orderCreateInfo);
        $productInfo = $this->generateProductsOrderInfo($productIdQuantityMap);
        return $this->repository->update($order, $productInfo);
    }

    public function deleteById(int $id): bool
    {
        $order = $this->repository->findById($id);

        if(request()->user()->cannot('delete', $order)) {
            abort(403);
        }
        return $this->repository->delete($order);
    }

    private function generateProductsOrderInfo(array $productIdQuantityMap): array
    {
        $products = $this->productRepo->findAllByIdIn(array_keys($productIdQuantityMap));
        $pivotInfo = [];

        foreach ($products as $product) {
            if(isset($productIdQuantityMap[$product->id])) {

                if($productIdQuantityMap[$product->id] > $product->quantity) {
                    throw new StockIsNotAvailableException($product, (int) $productIdQuantityMap[$product->id]);
                }
                $pivotInfo[$product->id] = [
                    'unit_price' => $product->price,
                    'quantity' => $productIdQuantityMap[$product->id]
                ];
            }
        }
        return $pivotInfo;
    }

    /**
     * @param array[OrderCreateDTO] $orderCreateDTOs
     * @return array
     */
    private function extractProductIdQuantityMap(array $orderCreateDTOs): array
    {
        $map = [];

        foreach ($orderCreateDTOs as $orderInfo) {
            $map += $orderInfo->toMap();
        }
        return $map;
    }

    public function paginateByUserId(int $userId, mixed $page = 1, mixed $perPage = 20): Paginator
    {
        return $this->repository->paginateByUserId($userId, $page, $perPage);
    }

    public function findByIdAndUserId(int $id, int $userId): Order
    {
        return $this->repository->findByIdAndUserIdORFail($id, $userId);
    }
}
