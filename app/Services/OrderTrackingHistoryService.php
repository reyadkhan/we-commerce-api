<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

interface OrderTrackingHistoryService
{
    public function getOrderHistory(int $orderId): Collection;
}
