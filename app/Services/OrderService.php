<?php

namespace App\Services;

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
}
