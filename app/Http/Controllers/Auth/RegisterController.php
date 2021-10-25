<?php

namespace App\Http\Controllers\Auth;

use App\DTOs\RegisterDTO;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class RegisterController
{
    public function __construct(private UserService $service) {}

    public function __invoke(Request $request): UserResource
    {
        $registerDTO = new RegisterDTO(...$this->validated($request));
        return new UserResource($this->service->register($registerDTO));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
           'firstName' => 'required|string|max:50',
           'lastName' => 'nullable|string|max:50',
           'email' => 'required|string|email|unique:users',
           'password' => 'required|string|min:8|max:150'
        ]);
    }
}
