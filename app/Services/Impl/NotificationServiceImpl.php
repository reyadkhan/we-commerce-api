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

    public function markAsRead(int $id): bool
    {
        $notification = $this->repository->findByIdOrFail($id);

        if( ! $notification->is_read) {
            return $this->repository->markAsRead($notification);
        }
        return true;
    }

    public function unreadCount(): int
    {
        return $this->repository->countByIsRead(false);
    }
}
