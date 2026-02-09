<?php

namespace App\Core\Domain\Interfaces;

use App\Models\User;

interface SanctumRepositoryInterface
{
    public function create(array $data): User;

    public function findUserByEmail(string $email): ?User;

    public function deleteUserTokens(User $user): bool;

    public function update(User $user): void;

}
