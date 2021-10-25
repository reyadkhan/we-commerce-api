<?php

namespace App\Models;

use App\Enums\OrderTrackingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTrackingHistory extends Model
{
    protected $fillable = [
        'status', 'order_price', 'order_quantity', 'product_ids'
    ];

    protected $casts = [
        'status' => OrderTrackingStatus::class
    ];

    /**
     * Getter for product_ids attribute
     *
     * @return array products ids
     */
    public function getProductIdsAttribute(): array
    {
        return explode(',', $this->attributes['product_ids']);
    }

    /**
     * Setter for product_ids attribute
     *
     * @param array $productIds product ids
     */
    public function setProductIdsAttribute(array $productIds)
    {
        $this->attributes['product_ids'] = implode(',', $productIds);
    }

    /**
     * History order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
