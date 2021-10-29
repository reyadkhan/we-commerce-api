<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['amount'];

    protected $attributes = [
        'status' => OrderStatus::CREATED
    ];

    protected $casts = [
        'status' => OrderStatus::class
    ];

    /**
     * Order products
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)->withPivot('unit_price', 'quantity');
    }

    /**
     * Order user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order tracking histories
     *
     * @return HasMany
     */
    public function trackingHistories(): HasMany
    {
        return $this->hasMany(OrderTrackingHistory::class);
    }

    /**
     * Order notification
     *
     * @return MorphOne
     */
    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notifiable');
    }

    /**
     * Attach order sequence number
     */
    public function attachOrderId() {
        $lastSequenceNumber = (int) Order::select('order_id')->latest()->first()?->order_id;
        $this->order_id = sprintf("%04d", $lastSequenceNumber + 1);
    }
}
