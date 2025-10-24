<?php

namespace App\Http\Controllers;

use App\Attributes\Throws;
use App\Http\Requests\ProjectRequest;
use App\Http\Responses\DTO\ResponseDto;
use App\Services\ProjectService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $service)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function index(): ResponseDto
    {
        return new ResponseDto(data: $this->service->list());
    }

    #[Throws(exceptionClass: Exception::class)]
    public function store(ProjectRequest $request): ResponseDto
    {
        $project = $this->service->create(data: $request->validated());
        return new ResponseDto(data: $project, statusCode: Response::HTTP_CREATED);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function show(int $id): ResponseDto
    {
        $project = $this->service->get(id: $id) ?? throw new Exception('Project not found');
        return new ResponseDto(data: $project);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(ProjectRequest $request, int $id): ResponseDto
    {
        $updated = $this->service->update(data: $request->validated(), id: $id);
        return new ResponseDto(data: ['updated' => $updated]);
    }

    public function destroy(int $id): ResponseDto
    {
        $this->service->delete(id: $id);
        return new ResponseDto(success: true, message: 'Project deleted');
    }
}
