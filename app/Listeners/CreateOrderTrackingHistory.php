<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Enums\OrderTrackingStatus;
use App\Events\OrderSavedEvent;
use App\Models\OrderTrackingHistory;

class CreateOrderTrackingHistory
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderSavedEvent $event)
    {
        $history = new OrderTrackingHistory;
        $history->order_price = $event->order->amount;
        $history->order_quantity = $event->order->products->sum('pivot.quantity');
        $history->product_ids = $event->order->products->pluck('id')->toArray();

        if(OrderStatus::CREATED()->is($event->order->status)
            && $event->order->trackingHistories()->exists()) {
            $history->status = OrderTrackingStatus::UPDATED();
        } else {
            $history->status = $event->order->status;
        }
        $history->details = getOrderTrackingDetails(
            $history->status,
            getProductsSerializeName($event->order->products),
            $history->order_price, $history->order_quantity
        );
        $history->order()->associate($event->order);
        $history->save();
    }
}
