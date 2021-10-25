<?php

namespace App\Services;

use App\DTOs\RegisterDTO;
use App\Models\User;

interface UserService
{
    public function register(RegisterDTO $registerInfo): User;
}
