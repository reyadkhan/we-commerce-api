<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class CustomPaginatedResourceResponse extends PaginatedResourceResponse
{
    protected function meta($paginated)
    {
        $meta = parent::meta($paginated);
        return [
            'currentPage' => $meta['current_page'] ?? null,
            'lastPage' => $meta['last_page'] ?? null,
            'perPage' => $meta['per_page'] ?? null,
            'from' => $meta['from'] ?? null,
            'to' => $meta['to'] ?? null,
            'total' => $meta['total'] ?? null,
            'path' => $meta['path'] ?? null,
            'links' => $meta['links'] ?? null
        ];
    }
}
