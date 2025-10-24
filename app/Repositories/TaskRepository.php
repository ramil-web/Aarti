<?php

namespace App\Repositories;

use App\Attributes\Throws;
use App\Models\Comment;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

readonly class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(private Task $task)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function allByProject(int $projectId): Collection
    {
        try {
            return $this->task->query()->where('project_id', $projectId)->get();
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function find(int $id): ?Task
    {
        try {
            return $this->task->query()->find(id: $id);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(array $data): Task
    {
        try {
            return $this->task->query()->create(attributes: $data);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function addComment(int $taskId, array $data): Comment
    {
        $task = $this->find(id: $taskId);
        return $task->comments()->create([
            'text'    => $data['text'],
            'user_id' => auth()->id(),
        ]);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(int $id, array $data): Task
    {
        try {
            $task = $this->find(id: $id);
            $task->update(attributes: $data);
            return $this->find(id: $id);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function delete(int $id): bool
    {
        try {
            return $this->find(id: $id)->delete();
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function getComments(int $taskId): Collection
    {
        try {
            $task = $this->find(id: $taskId);
            return $task->comments()
                ->with('user:id,name,email')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
