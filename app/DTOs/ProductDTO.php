<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public int $qty,
        public ?string $desc = null,
        public ?string $img = null
    ) {}

    /**
     * @return array fillable model array
     */
    public function toModel(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->qty,
            'description' => $this->desc,
            'image' => $this->img
        ];
    }
}
