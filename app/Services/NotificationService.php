<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator;

interface NotificationService
{
    public function paginate(int $page = 1, int $perPage = 20): Paginator;

    public function markAsRead(int $id): bool;

    public function unreadCount(): int;
}
