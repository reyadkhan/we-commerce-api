<?php

namespace App\DTOs;

class OrderCreateDTO
{
    public function __construct(
        public int $id,
        public int $quantity
    ) {}

    public function toMap(): array
    {
        return [$this->id => $this->quantity];
    }
}
