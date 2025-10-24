<?php

namespace App\Http\Controllers;

use App\Attributes\Throws;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Responses\DTO\ResponseDto;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(public TaskService $service
    )
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function index(int $projectId): ResponseDto
    {
        $tasks = $this->service->allByProject($projectId);
        return new ResponseDto(data: $tasks);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function show(int $id): ResponseDto
    {
        $task = $this->service->getById($id);
        return new ResponseDto(data: $task);
    }


    #[Throws(exceptionClass: Exception::class)]
    public function store(TaskRequest $request, int $projectId): ResponseDto
    {
        $task = $this->service->create($projectId, $request->validated());
        return new ResponseDto(data: $task, statusCode: Response::HTTP_CREATED);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(UpdateTaskRequest $request, int $id): ResponseDto
    {
        $task = $this->service->update($id, $request->validated());
        return new ResponseDto(data: $task, message: 'Task updated');
    }

    #[Throws(exceptionClass: Exception::class)]
    public function destroy(int $id): ResponseDto
    {
        if (auth()->user()->role !== 'manager') {
            return new ResponseDto(message: 'Forbidden', statusCode: Response::HTTP_FORBIDDEN);
        }
        $this->service->delete($id);
        return new ResponseDto(message: 'Task deleted',);
    }

}
