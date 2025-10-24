<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data): ?User;

    public function update(int $id, array $data): bool;

    public function getByEmail(string $email): ?User;

}
