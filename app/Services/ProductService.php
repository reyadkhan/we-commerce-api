<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

interface ProductService
{
    public function paginate(int $page = 1, int $perPage = 20): Paginator;

    public function findById(int $id): Product;

    public function create(ProductDTO $product): Product;

    public function update(int $id, ProductDTO $product): Product;

    public function deleteById(int $id): bool;

    public function searchByName(string $name, int $page = 1, int $perPage = 15): Paginator;

    public function sortByColumns(array $sortByColumns, int $page = 1, int $perPage = 20): Paginator;

    public function totalProductCount(): int;
}
