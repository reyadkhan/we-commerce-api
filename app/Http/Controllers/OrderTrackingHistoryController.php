<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderTrackingCollection;
use App\Services\OrderTrackingHistoryService;

class OrderTrackingHistoryController extends Controller
{
    public function __construct(private OrderTrackingHistoryService $service)
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get an order tracking history
     *
     * @param int $orderId Order identifier
     * @return OrderTrackingCollection
     */
    public function __invoke(int $orderId): OrderTrackingCollection
    {
        return new OrderTrackingCollection($this->service->getOrderHistory($orderId));
    }
}
