<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->model->create($data)->refresh();
    }

    /**
     * Count user within N days
     *
     * @param int $day day number
     * @return int
     */
    public function countUserByLastNDays(int $day): int
    {
        $from = now()->subDays($day - 1)->startOfDay();
        $to = now();
        return $this->model->whereBetween('created_at', [$from, $to])->count();
    }
}
