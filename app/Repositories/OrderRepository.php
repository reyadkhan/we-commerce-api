<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Order);
    }

    /**
     * Find by model id
     *
     * @param int $id Order identifier
     * @return Order|null
     */
    public function findById(int $id): ?Order
    {
        return $this->model->find($id);
    }

    /**
     * Find by model id or throw 404 (Not found) exception
     *
     * @param int $id Order identifier
     * @return Order
     */
    public function findByIdOrFail(int $id): Order
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new order
     *
     * @param User|Authenticatable $user order user
     * @param array $orderProducts products price and quantity info
     * @return Order created order
     */
    public function create(User|Authenticatable $user, array $orderProducts): Order
    {
        return DB::transaction(function() use ($user, $orderProducts) {
            $newOrder = $user->orders()->create();
            $newOrder->products()->sync($orderProducts);
            return $newOrder->refresh();
        });
    }

    /**
     * @param Order $order to be updated
     * @param array $orderProducts products price and quantity info
     * @return Order updated order
     */
    public function update(Order $order, array $orderProducts): Order
    {
        $order->products()->sync($orderProducts);
        return $order->refresh();
    }

    /**
     * Paginate order by user id
     *
     * @param int $userId User identifier
     * @param mixed $page page number
     * @param mixed $perPage page size
     * @return Paginator paginate results
     */
    public function paginateByUserId(int $userId, mixed $page, mixed $perPage): Paginator
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')->paginate(page: $page, perPage: $perPage);
    }

    public function findByIdAndUserIdORFail(int $id, int $userId): Order
    {
        return $this->model->where('user_id', $userId)->findOrFail($id);
    }
}
