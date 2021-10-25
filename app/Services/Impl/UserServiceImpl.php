<?php

namespace App\Services\Impl;

use App\DTOs\RegisterDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl extends BaseServiceImpl implements UserService
{
    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function register(RegisterDTO $registerInfo): User
    {
        $fillableData = $registerInfo->toModel();
        $fillableData['password'] = Hash::make($fillableData['password']);
        return $this->repository->create($fillableData);
    }
}
