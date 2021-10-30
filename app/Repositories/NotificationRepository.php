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

    public function markAsReadByIdIn(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)
            ->where('is_read', false)->update(['is_read' => true]);
    }

    public function countByIsRead(bool $isRead): int
    {
        return $this->model->where('is_read', $isRead)->count();
    }
}
