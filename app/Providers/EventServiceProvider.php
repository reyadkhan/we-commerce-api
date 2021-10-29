<?php

namespace App\Providers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderSavedEvent;
use App\Listeners\CreateOrderTrackingHistory;
use App\Listeners\SendOrderNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderSavedEvent::class => [
            CreateOrderTrackingHistory::class
        ],
        OrderCreatedEvent::class => [
            SendOrderNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
