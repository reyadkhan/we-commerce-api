<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Notification);
    }

    public function findByIdOrFail(int $id): Notification
    {
        return $this->model->findOrFail($id);
    }

    public function markAsRead(Notification $notification): bool
    {
        $notification->is_read = true;
        return $notification->save();
    }

    public function countByIsRead(bool $isRead): int
    {
        return $this->model->where('is_read', $isRead)->count();
    }
}
