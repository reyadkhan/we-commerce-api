<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoveDeliveredOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deliveredOrders = Order::where('status', OrderStatus::DELIVERED())->get();
        Log::info("Starting delivered order to move to Deliveries.");
        $this->moveToDelivery($deliveredOrders);
        Log::info("Finished delivered order moving to Deliveries.");
    }

    private function moveToDelivery(Collection $orders)
    {
        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {
                Delivery::create($order->toArray());
                $order->delete();
                Log::info("Order with id[" . $order->id . "] has been moved.");
            });
        }
    }
}
