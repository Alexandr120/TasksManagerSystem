<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class UserAuthRepository implements UserAuthRepositoryInterface
{
    public function user(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }

    public function createAuthToken(): string
    {
        return $this->user()->createToken('token')->plainTextToken;
    }

    public function checkCredentials(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function logout(): void
    {
        $this->user()->currentAccessToken()->delete();
    }


}
