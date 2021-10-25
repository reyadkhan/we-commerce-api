<?php

namespace App\Services\Impl;

use App\Repositories\OrderRepository;
use App\Services\OrderTrackingHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\UnauthorizedException;

class OrderTrackingHistoryServiceImpl implements OrderTrackingHistoryService
{
    public function __construct(private OrderRepository $orderRepo) {}

    public function getOrderHistory(int $orderId): Collection
    {
        $order = $this->orderRepo->findByIdOrFail($orderId);

        if(request()->user()->cannot('getHistory', $order)) {
            throw new UnauthorizedException;
        }
        return $order->trackingHistories;
    }
}
