<?php

use App\Enums\OrderTrackingStatus;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

const GUARD = 'sanctum';

if( ! function_exists("getPageVar")) {
    function getPageVar(): array {
        $page = max(request()->page, 1);
        $perPage = max(request()->perPage ?? 20, 1);
        return compact('page', 'perPage');
    }
}

if( ! function_exists('isAdmin')) {
    function isAdmin(): bool {
        $auth = auth(GUARD);
        return $auth->check() && $auth->user()->is_admin;
    }
}

if( ! function_exists('authUser')) {
    function authUser(): Authenticatable|User|null {
        return auth(GUARD)->user();
    }
}

if( ! function_exists('authId')) {
    function authId(): int|null {
        return auth(GUARD)->id();
    }
}

if( ! function_exists('getOrderTrackingDetails')) {
    function getOrderTrackingDetails(OrderTrackingStatus $status, String $productNames, float $price, int $quantity): string {
        return __("Order :status with total quantity :quantity price :price tk of the products :products.", [
            'status' => strtolower($status),
            'quantity' => $quantity, 'price' => $price, 'products' => $productNames
        ]);
    }
}

if( ! function_exists('getProductsSerializeName')) {
    function getProductsSerializeName(Collection $products): string {
        return implode(", ", $products->pluck('name')->toArray());
    }
}
