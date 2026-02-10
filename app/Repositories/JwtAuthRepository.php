<?php

namespace App\Repositories;

use App\Core\Domain\Interfaces\JwtAuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JwtAuthRepository implements JwtAuthRepositoryInterface

{
    public function __construct(protected User $user){}


    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findUserById(int $id): ?User
    {
        return $this->user->find($id);
    }
}
