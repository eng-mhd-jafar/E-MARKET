<?php

namespace App\Core\Domain;
use App\Models\User;

interface JwtAuthRepositoryInterface
{
    public function create(array $data): User;

    public function findUserByEmail(string $email): ?User;

    public function findUserById(int $id): ?User;

}