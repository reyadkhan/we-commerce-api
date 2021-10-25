<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Observers\OrderObserver;
use App\Observers\OrderProductObserver;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Services\Impl\OrderServiceImpl;
use App\Services\Impl\ProductServiceImpl;
use App\Services\Impl\UserServiceImpl;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductService::class, fn($app) => new ProductServiceImpl($app->make(ProductRepository::class)));

        $this->app->singleton(UserService::class, fn($app) => new UserServiceImpl($app->make(UserRepository::class)));

        $this->app->singleton(OrderService::class, fn($app) => new OrderServiceImpl(new OrderRepository, new ProductRepository));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Order::observe(OrderObserver::class);
        OrderProduct::observe(OrderProductObserver::class);
    }
}
