<?php

namespace App\Models;

use App\Enums\OrderStatus;

class Delivery extends Order
{
    protected $fillable = ['id', 'order_id', 'amount', 'user_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $attributes = [];

    public $incrementing = false;

    public function getStatusAttribute(): string
    {
        return OrderStatus::DELIVERED();
    }
}
