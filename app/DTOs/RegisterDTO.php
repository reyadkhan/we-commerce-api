<?php

namespace App\DTOs;

class RegisterDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password
    ) {}

    /**
     * @return array model fillable array
     */
    public function toModel(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
