<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = ['title', 'details'];

    protected $casts = [
        'is_read' => 'boolean',
        'notified' => 'boolean'
    ];

    /**
     * Notifiable entity
     *
     * @return MorphTo
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
