<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    /**
     * Find by model id
     *
     * @param int $id Product identifier
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * Find by model id or throw 404 (Not found) exception
     *
     * @param int $id Product identifier
     * @return Product
     */
    public function findByIdOrFail(int $id): Product
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new model
     *
     * @param array $data Product fillable data array
     * @return Product created model
     */
    public function create(array $data): Product
    {
        return $this->model->create($data)->refresh();
    }

    /**
     * Update an existing model
     *
     * @param Product $model the model to be updated
     * @param array $data model fillable updated data array
     * @return Product updated model
     */
    public function update(Product $model, array $data): Product
    {
        $model->update($data);
        return $model->refresh();
    }

    /**
     * Find product by name like
     *
     * @param string $name product name
     * @param int $page pagination page number
     * @param int $perPage pagination page size
     * @return Paginator pagination results
     */
    public function findByNameLik(string $name, int $page, int $perPage): Paginator
    {
        return $this->model->where('name', 'like', '%' . $name . '%')
            ->orderBy('name')->paginate(page: $page, perPage: $perPage);
    }

    /**
     * Sort product by columns
     *
     * @param array $colWithDirection search column with direction
     * @param int $page pagination page number
     * @param int $perPage pagination page size
     * @return Paginator pagination results
     */
    public function orderByColumns(array $colWithDirection, int $page, int $perPage): Paginator
    {
        $model = $this->model;

        foreach ($colWithDirection as $column => $direction) {
            $model = $this->model->orderBy($column, $direction);
        }
        return $model->paginate(page: $page, perPage: $perPage);
    }

    /**
     * Find products by multiple ids
     *
     * @param array $ids Product identifiers
     * @return Collection found results
     */
    public function findAllByIdIn(array $ids): Collection
    {
        return $this->model->findMany($ids);
    }

    /**
     * Total product count
     *
     * @return int
     */
    public function totalCount(): int
    {
        return $this->model->count();
    }
}
