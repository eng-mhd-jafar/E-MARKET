<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JwtAuthRepository
{
    public function __construct(protected User $user)
    {
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return $this->user->find($id);
    }
}
