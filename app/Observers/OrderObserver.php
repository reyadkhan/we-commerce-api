<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Events\OrderCreatedEvent;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->attachOrderId();
    }

    public function created(Order $order)
    {
        OrderCreatedEvent::dispatch($order);
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
