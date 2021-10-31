<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function __invoke(Request $request)
    {
        $credentials = $this->validated($request);

        if( ! Auth::attempt($credentials)) {
            throw new AuthenticationException;
        }
        return $this->authInfo();
    }

    private function authInfo(): JsonResponse {
        $user = Auth::user();
        $token = $user->createToken('user-' . $user->id)->plainTextToken;
        return response()->json(compact('token'));
    }

    /**
     * @param Request $request
     * @return array validated data
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
    }
}
