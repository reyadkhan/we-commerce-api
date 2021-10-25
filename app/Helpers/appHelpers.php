<?php

if( ! function_exists("getPageVar")) {
    function getPageVar(): array {
        $page = max(request()->page, 1);
        $perPage = max(request()->perPage ?? 20, 1);
        return compact('page', 'perPage');
    }
}

if( ! function_exists('isAdmin')) {
    function isAdmin(): bool {
        $auth = auth('sanctum');
        return $auth->check() && $auth->user()->is_admin;
    }
}
