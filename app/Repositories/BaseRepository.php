<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function __construct(protected Model $model) {}

    /**
     * @param int $page page number
     * @param int $perPage per page data count
     * @param string $orderColumn order by column
     * @param string $order order direction
     * @return Paginator pagination object
     */
    public function paginate(int $page = 1, int $perPage = 20, string $orderColumn = 'created_at', string $order = 'desc'): Paginator
    {
        return $this->model->orderBy($orderColumn, $order)->paginate(page: $page, perPage: $perPage);
    }

    /**
     * Get all available results of the model
     *
     * @return Collection model collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Delete an existing model
     *
     * @param Model $model the model to be deleted
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}
