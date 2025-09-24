<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function markEmailAsVerified($user)
    {
        $user->update([
            'email_verified_at' => now(),
            'OTP' => null,
            'verification_code_expires_at' => null,
        ]);
    }

    public function deleteUserTokens(User $user): bool
    {
        return $user->tokens()->delete() > 0;
    }

}