<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderObserver
{
    public function creating(Order $order)
    {
        if(empty($order->order_id)) {
            $order->attachOrderId();
        }
    }

    public function updating(Order $order)
    {
        if($order->isDirty('status')
            && OrderStatus::DELIVERED()->is($order->status)
            && OrderStatus::DELIVERED()->isNot($order->getOriginal('status'))) {
            $this->updateProductsQuantity($order->products);
        }
    }

    private function updateProductsQuantity(Collection $products)
    {
        foreach ($products as $product) {
            $product->quantity = max($product->quantity - $product->pivot->quantity, 0);
            $product->save();
        }
    }
}
