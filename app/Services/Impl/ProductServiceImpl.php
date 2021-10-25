<?php

namespace App\Services\Impl;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Contracts\Pagination\Paginator;

class ProductServiceImpl extends BaseServiceImpl implements ProductService
{
    public function __construct(protected ProductRepository $repository) {}

    public function findById(int $id): Product
    {
        return $this->repository->findByIdOrFail($id);
    }

    public function create(ProductDTO $product): Product
    {
        return $this->repository->create($product->toModel());
    }

    public function update(int $id, ProductDTO $product): Product
    {
        $model = $this->findById($id);
        return $this->repository->update($model, $product->toModel());
    }

    public function searchByName(string $name, int $page = 1, int $perPage = 15): Paginator
    {
        return $this->repository->findByNameLik($name, $page, $perPage);
    }

    public function sortByColumns(array $sortByColumns, int $page = 1, int $perPage = 20): Paginator
    {
        $colWithDirection = array_map(fn($direction) => in_array($direction, ['asc', 'desc']) ? $direction : 'asc', $sortByColumns);
        return $this->repository->orderByColumns($colWithDirection, $page, $perPage);
    }
}
