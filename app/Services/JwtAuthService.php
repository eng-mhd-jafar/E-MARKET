<?php

namespace App\Services;

use App\Core\Domain\Interfaces\JwtAuthRepositoryInterface;
use App\Models\User;

class JwtAuthService
{
    public function __construct(protected JwtAuthRepositoryInterface $jwtAuthRepository)
    {
    }
    public function register(array $data): array
    {
        $user = $this->jwtAuthRepository->create($data);
        $token = auth('jwt')->login($user);

        return $this->generateTokenResponse($token, $user);
    }

    public function login(array $credentials): ?array
    {
        $token = auth('jwt')->attempt($credentials);

        if (!$token) {
            return null;
        }

        $user = auth('jwt')->user();

        return $this->generateTokenResponse($token, $user);
    }

    public function getCurrentUser(): ?User
    {
        return auth('jwt')->user();
    }

    public function logout(): void
    {
        auth('jwt')->logout();
    }

    public function refreshToken(): array
    {
        $token = auth('jwt')->refresh();
        $user = auth('jwt')->user();

        return $this->generateTokenResponse($token, $user);
    }

    protected function generateTokenResponse(string $token, User $user): array
    {
        $factory = auth('jwt')->factory();

        return [
            'access_token' => $token,
            'refresh_token' => $token, // في Laravel JWT، نفس الـ token يستخدم للـ refresh
            'token_type' => 'bearer',
            'expires_in' => $factory->getTTL() * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl', 20160) * 60, // بالثواني
            'user' => $user
        ];
    }
}
