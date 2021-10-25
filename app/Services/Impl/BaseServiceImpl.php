<?php

namespace App\Services\Impl;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\Paginator;

class BaseServiceImpl
{
    protected BaseRepository $repository;

    public function paginate(int $page = 1, int $perPage = 20): Paginator
    {
        return $this->repository->paginate($page, $perPage);
    }

    public function deleteById(int $id): bool
    {
        $model = $this->repository->findByIdOrFail($id);
        return $this->repository->delete($model);
    }
}
