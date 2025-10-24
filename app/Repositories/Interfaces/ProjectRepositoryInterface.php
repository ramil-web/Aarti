<?php

namespace App\Repositories\Interfaces;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function all(array$withRelation);
    public function find(int $id, array $withRelation): ?Project;
    public function create(array $data): Project;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
