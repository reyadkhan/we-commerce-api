<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;

interface OrderService
{
    public function paginate(int $page = 1, int $perPage = 20): Paginator;

    public function findById(int $id): Order;

    /**
     * @param User|Authenticatable $user
     * @param array[OrderCreateDTO] $orderCreateInfo
     * @return Order
     */
    public function create(User|Authenticatable $user, array $orderCreateInfo): Order;

    /**
     * @param int $id order identifier
     * @param array[OrderCrateDTO] $orderCreateInfo
     * @return Order
     */
    public function update(int $id, array $orderCreateInfo): Order;

    public function deleteById(int $id): bool;

    public function paginateByUserId(int $userId, mixed $page = 1, mixed $perPage = 20): Paginator;

    public function findByIdAndUserId(int $id, int $userId): Order;
}
