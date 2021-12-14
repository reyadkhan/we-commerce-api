<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'image'
    ];

    /**
     * Product orders
     *
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->using(OrderProduct::class)->withPivot('unit_price', 'quantity');
    }

    /**
     * Product all updated deliveries
     *
     * @return BelongsToMany
     */
    public function deliveries(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'order_product', relatedPivotKey: 'order_id')
            ->using(OrderProduct::class)->withPivot('unit_price', 'quantity');
    }
}
