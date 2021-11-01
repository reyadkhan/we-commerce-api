<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\Pagination\Paginator;

interface OrderService
{
    public function paginate(int $page = 1, int $perPage = 20): Paginator;

    public function findById(int $id): Order;

    /**
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function create(array $orderCreateInfo): Order;

    /**
     * @param int $id order identifier
     * @param array[OrderCrateDTO] $orderCreateInfo
     * @return Order
     */
    public function update(int $id, array $orderCreateInfo): Order;

    public function deleteById(int $id): bool;

    public function updateStatus(int $id, OrderStatus $status);

    /**
     * Search by order unique id
     *
     * @param string $orderId order generated unique id
     * @return Order
     */
    public function findByOrderId(string $orderId): Order;

    public function findAllByStatus(OrderStatus $status, int $page = 1, int $perPage = 20): Paginator;
}
