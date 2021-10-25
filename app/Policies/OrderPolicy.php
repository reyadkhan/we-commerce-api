<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Order $order)
    {
        return $user->id === $order->user_id
            && OrderStatus::CREATED()->is($order->status);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can access the order history
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function getHistory(User $user, Order $order)
    {
        return isAdmin() || $user->id === $order->user_id;
    }
}
