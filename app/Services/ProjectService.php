<?php

namespace App\Services;

use App\Attributes\Throws;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Override;

#[Override]
readonly class ProjectService
{
    public const WITH_TASKS = ['tasks'];

    public function __construct(private ProjectRepositoryInterface $projectRepository)
    {
    }

   #[Throws(exceptionClass: Exception::class)]
   #[Override]
    public function list(): Collection
    {
        return Cache::remember('projects_list', 300, fn (): Collection => $this->projectRepository->all(withRelation: self::WITH_TASKS));
    }

    #[Throws(exceptionClass: Exception::class)]
    #[Override]
    public function get(int $id): ?Project
    {
        return Cache::remember("project:$id", 300, fn (): ?Project => $this->projectRepository->find(id: $id, withRelation: self::WITH_TASKS));
    }

    #[Throws(exceptionClass: Exception::class)]
    #[Override]
    public function create(array $data): Project
    {
        $data['user_id'] = auth()->id() ?? throw new Exception('User not authenticated');
        Cache::forget('projects_list');
        return $this->projectRepository->create($data);
    }

    #[Throws(exceptionClass: Exception::class)]
    #[Override]
    public function update(array $data, int $id): bool
    {
        Cache::forget('projects_list');
        Cache::forget("project:$id");
        return $this->projectRepository->update(id: $id, data: $data);
    }

    #[Override]
    public function delete(int $id): bool
    {
        Cache::forget('projects_list');
        Cache::forget("project:$id");
        return $this->projectRepository->delete(id: $id);
    }
}
