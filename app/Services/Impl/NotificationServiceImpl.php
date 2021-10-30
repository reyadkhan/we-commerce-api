<?php

namespace App\Services\Impl;

use App\Repositories\NotificationRepository;
use App\Services\NotificationService;

class NotificationServiceImpl extends BaseServiceImpl implements NotificationService
{
    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function markAsRead(array $ids): bool
    {
        return $this->repository->markAsReadByIdIn($ids);
    }

    public function unreadCount(): int
    {
        return $this->repository->countByIsRead(false);
    }
}
