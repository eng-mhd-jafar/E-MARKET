<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(protected User $user)
    {
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function markEmailAsVerified(User $user): void
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
