<?php

namespace App\Repositories;

use App\Attributes\Throws;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

readonly class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(private Project $project)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function all(array $withRelation = []): Collection
    {
        try {
            return $this->project->query()->with($withRelation)->get();
        } catch (QueryException $e) {
            throw new Exception("Failed to fetch projects: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function find(int $id, array $withRelation = []): ?Project
    {
        try {
            return $this->project->query()->with($withRelation)->findOrFail($id);
        } catch (QueryException $e) {
            throw new Exception("Failed to find project: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(array $data): Project
    {
        try {
            return $this->project->query()->create($data);
        } catch (QueryException $e) {
            throw new Exception("Failed to create project: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(int $id, array $data): bool
    {
        try {
            $project = $this->find(id: $id);
            return $project->update($data);
        } catch (QueryException $e) {
            throw new Exception("Failed to update project: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function delete(int $id): bool
    {
        try {
            $project = $this->find(id: $id);
            return $project->delete();
        } catch (QueryException $e) {
            throw new Exception("Failed to delete project: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
