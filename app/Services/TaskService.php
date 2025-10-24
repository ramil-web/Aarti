<?php

namespace App\Services;

use App\Attributes\Throws;
use App\Jobs\SendTaskAssignedEmailJob;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

readonly class TaskService
{
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function allByProject(mixed $projectId): mixed
    {
        return $this->taskRepository->allByProject(projectId: $projectId);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function getById(int $id): ?Task
    {
        return $this->taskRepository->find(id: $id);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(int $projectId, array $data): Task
    {
        $data['project_id'] = $projectId;
        $task = $this->taskRepository->create(data: $data);
        if (isset($data['assigned_to'])) {
            SendTaskAssignedEmailJob::dispatch($task);
        }
        return $task;
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(int $id, array $data): JsonResponse|Task
    {
        $task = $this->getById($id);
        if (auth()->user()->role != 'manager' && auth()->id() !== $task->assigned_to) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->taskRepository->update(id: $id, data: $data);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function delete(int $id): bool
    {
        return $this->taskRepository->delete(id: $id);
    }
}
