<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderTrackingResource;
use App\Services\OrderTrackingHistoryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @return AnonymousResourceCollection
     */
    public function __invoke(int $orderId): AnonymousResourceCollection
    {
        return OrderTrackingResource::collection($this->service->getOrderHistory($orderId));
    }
}
