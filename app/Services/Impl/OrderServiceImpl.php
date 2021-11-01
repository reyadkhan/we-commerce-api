<?php

namespace App\Services\Impl;

use App\Enums\OrderStatus;
use App\Events\OrderCreatedEvent;
use App\Events\OrderSavedEvent;
use App\Exceptions\StockIsNotAvailableException;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Validation\UnauthorizedException;

class OrderServiceImpl implements OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private ProductRepository $productRepo
    ) {}

    public function paginate(int $page = 1, int $perPage = 20): Paginator
    {
        if(isAdmin()) {
            return $this->repository->paginate($page, $perPage);
        }
        return $this->repository->paginateByUserId(authId(), $page, $perPage);
    }

    public function findById(int $id): Order
    {
        if(isAdmin()) {
            return $this->repository->findCombinedByIdOrFail($id);
        }
        return $this->repository->findCombinedByIdAndUserIdORFail($id, authId());
    }

    /**
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function create(array $orderCreateInfo): Order
    { //TODO this will be improved for better performance [async and quable events]
        $productIdQuantityMap = $this->extractProductIdQuantityMap($orderCreateInfo);
        $productInfo = $this->generateProductsOrderInfo($productIdQuantityMap);
        $order = $this->repository->create(authUser(), $productInfo);
        $this->dispatchOrderCreatedEvents($order);
        return $order;
    }

    /**
     * @param int $id order identifier
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function update(int $id, array $orderCreateInfo): Order
    {
        $order = $this->repository->findByIdOrFail($id);
        $this->throwIfNotAuthorizedToUpdate($order);
        $productIdQuantityMap = $this->extractProductIdQuantityMap($orderCreateInfo);
        $productInfo = $this->generateProductsOrderInfo($productIdQuantityMap);
        $updatedOrder = $this->repository->update($order, $productInfo);
        OrderSavedEvent::dispatch($updatedOrder);
        return $updatedOrder;
    }

    private function throwIfNotAuthorizedToUpdate(Order $order)
    {
        if(authUser()->cannot('update', $order)) {
            throw new UnauthorizedException;
        }
    }

    /**
     * This will dispatch created and saved events for notification and order tracking history
     *
     * @param Order $order newly created events
     */
    private function dispatchOrderCreatedEvents(Order $order)
    { //TODO this events listener should be quable for better performance
        OrderSavedEvent::dispatch($order);
        OrderCreatedEvent::dispatch($order);
    }

    public function deleteById(int $id): bool
    {
        $order = $this->findById($id);

        if(authUser()->cannot('delete', $order)) {
            throw new UnauthorizedException;
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

    public function updateStatus(int $id, OrderStatus $status)
    {
        $order = $this->repository->findByIdOrFail($id);

        if( ! isAdmin() || OrderStatus::CREATED()->is($status) || OrderStatus::DELIVERED()->is($order->status)) {
            throw new UnauthorizedException;
        }
        $this->repository->updateStatus($order, $status);
        OrderSavedEvent::dispatch($order);
    }

    public function findByOrderId(string $orderId): Order
    {
        $order = $this->repository->findByOrderIdOrFail($orderId);

        if(authUser()->cannot('show', $order)) {
            throw new UnauthorizedException;
        }
        return $order;
    }

    public function findAllByStatus(OrderStatus $status, int $page = 1, int $perPage = 20): Paginator
    {
        if(isAdmin()) {
            return $this->repository->findAllByStatus($status, $page, $perPage);
        }
        return $this->repository->findAllByUserIdAndStatus(authId(), $status, $page, $perPage);
    }

    public function todaysOrderCount(): int
    {
        return $this->repository->countOrderByLastNDays(1);
    }


}
