<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository
{
    private Delivery $delivery;

    public function __construct()
    {
        parent::__construct(new Order);
        $this->delivery = new Delivery;
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
     * Find order by id or throw 404 (Not found) exception
     *
     * @param int $id Order identifier
     * @return Order
     */
    public function findByIdOrFail(int $id): Order
    {
       return $this->model->findOrFail($id);
    }

    /**
     * Find order or delivery by id or throw 404 (Not found) exception
     *
     * @param int $id Order identifier
     * @return Order
     */
    public function findCombinedByIdOrFail(int $id): Order
    {
        $order = $this->model->find($id);

        if(is_null($order)) {
            return $this->delivery->findOrFail($id);
        }
        return $order;
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
     * Paginate orders
     *
     * @param int $page page number
     * @param int $perPage per page data count
     * @param string $orderColumn order by column
     * @param string $order order direction
     * @return Paginator pagination object
     */
    public function paginate(int $page = 1, int $perPage = 20, string $orderColumn = 'created_at', string $order = 'desc'): Paginator
    {
        $orders = $this->model->orderBy('created_at', 'desc')->paginate(page: $page, perPage: $perPage);
        $deliveries = $this->delivery->orderBy('created_at', 'desc')->paginate(page: $page, perPage: $perPage);
        return $this->returnCombinedPagination($orders, $deliveries);
    }

    /**
     * Paginate order by user id
     *
     * @param int $userId User identifier
     * @param int $page page number
     * @param int $perPage page size
     * @return Paginator paginate results
     */
    public function paginateByUserId(int $userId, int $page, int $perPage): Paginator
    {
        $orders = $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')->paginate(page: $page, perPage: $perPage);
        $deliveries = $this->delivery->where('user_id', $userId)
            ->orderBy('created_at', 'desc')->paginate(page: $page, perPage: $perPage);
        return $this->returnCombinedPagination($orders, $deliveries);
    }

    private function returnCombinedPagination(LengthAwarePaginator $orders, LengthAwarePaginator $deliveries): Paginator
    {
        $total = max($orders->total(), $deliveries->total());
        $page = $orders->currentPage();
        $perPage = $orders->perPage();
        $merged = collect($orders->items())->merge($deliveries->items())
            ->sortByDesc('created_at')->take($perPage);
        $paginated = new LengthAwarePaginator($merged, $total, $perPage, $page);
        return $paginated->withPath(request()->url());
    }

    /**
     * Order find by id and user id
     *
     * @param int $id Order identifier
     * @param int $userId User identifier
     * @return Order Found order
     */
    public function findByIdAndUserIdORFail(int $id, int $userId): Order
    {
        return $this->model->where('user_id', $userId)->findOrFail($id);
    }

    /**
     * Order or Delivery find by id and user id
     *
     * @param int $id Order identifier
     * @param int $userId User identifier
     * @return Order Found order
     */
    public function findCombinedByIdAndUserIdORFail(int $id, int $userId): Order
    {
        $order = $this->model->where('user_id', $userId)->find($id);

        if(is_null($order)) {
            return $this->delivery->where('user_id', $userId)->findOrFail($id);
        }
        return $order;
    }

    /**
     * Order status update
     *
     * @param Order $order Order to be updated
     * @param OrderStatus $status Order new status
     * @return bool
     */
    public function updateStatus(Order $order, OrderStatus $status): bool
    {
        $order->status = $status;
        return $order->save();
    }

    /**
     * Find order or delivery by generated order id
     *
     * @param string $orderId Order generated unique id
     * @return Order|null
     */
    public function findByOrderIdOrFail(string $orderId): ?Order
    {
        $order = $this->model->where('order_id', $orderId)->first();

        if(is_null($order)) {
            return $this->delivery->where('order_id', $orderId)->firstOrFail();
        }
        return $order;
    }

    /**
     * Find all order by status
     *
     * @param OrderStatus $status order status
     * @param int $page
     * @param int $perPage
     * @return Paginator Order collection
     */
    public function findAllByStatus(OrderStatus $status, int $page, int $perPage): Paginator
    {
        $orders = $this->model->where('status', $status)
            ->orderByDesc('created_at')->paginate(page: $page, perPage: $perPage);

        if(OrderStatus::DELIVERED()->is($status)) {
            $deliveries = $this->delivery->orderByDesc('created_at')->paginate(page: $page, perPage: $perPage);
            return $this->returnCombinedPagination($orders, $deliveries);
        }
        return $this->returnCombinedPagination($orders, new LengthAwarePaginator([], 0, $perPage));
    }

    /**
     * Find all order by user id and status
     *
     * @param int $userId user identifier
     * @param OrderStatus $status
     * @param int $page
     * @param int $perPage
     * @return Paginator paginated order
     */
    public function findAllByUserIdAndStatus(int $userId, OrderStatus $status, int $page, int $perPage): Paginator
    {
        $orders = $this->model->where('user_id', $userId)->where('status', $status)
            ->orderByDesc('created_at')->skip($page - 1 * $perPage)->limit($perPage)->get();
        $deliveries = new Collection();

        if(OrderStatus::DELIVERED()->is($status)) {
            $deliveries = $this->delivery->where('id', $userId)
                ->orderByDesc('created_at')->skip($page - 1 * $perPage)->limit($perPage)->get();
        }
        return $this->returnCombinedPagination($orders, $deliveries, $perPage, $page);
    }
}
