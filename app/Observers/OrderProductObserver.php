<?php

namespace App\Observers;

use App\Models\OrderProduct;

class OrderProductObserver
{
    /**
     * Handle the OrderProduct "created" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function creating(OrderProduct $orderProduct)
    {
        $order = $orderProduct->pivotParent;
        $order->amount += $this->getTotalAmount($orderProduct);
        $order->save();
    }

    public function updating(OrderProduct $orderProduct)
    {
        $prevQuantity = $orderProduct->getOriginal('quantity');

        if($prevQuantity !== $orderProduct->quantity) {
            $order = $orderProduct->pivotParent;
            $quantityDiff = $orderProduct->quantity - $prevQuantity;
            $order->amount += $orderProduct->unit_price * $quantityDiff;
            $order->save();
        }
    }

    /**
     * Handle the OrderProduct "deleted" event.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return void
     */
    public function deleting(OrderProduct $orderProduct)
    {
        $orderProduct->refresh();
        $order = $orderProduct->pivotParent;
        $order->amount = max($order->amount - $this->getTotalAmount($orderProduct), 0);
        $order->save();
    }

    private function getTotalAmount(OrderProduct $orderProduct)
    {
        return $orderProduct->quantity * $orderProduct->unit_price;
    }
}
