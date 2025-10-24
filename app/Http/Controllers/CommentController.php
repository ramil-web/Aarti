<?php

namespace App\Http\Controllers;

use _PHPStan_6597ef616\Nette\Neon\Exception;
use App\Attributes\Throws;
use App\Http\Requests\AddCommentRequest;
use App\Http\Responses\DTO\ResponseDto;
use App\Services\CommentService;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(protected CommentService $service)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function index(int $taskId): ResponseDto
    {
        $comments = $this->service->getCommentsByTask($taskId);
        return new ResponseDto(data: $comments);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function store(AddCommentRequest $request, int $taskId): ResponseDto
    {
        $comment = $this->service->create($taskId, $request->validated());
        return new ResponseDto(data: $comment, message: 'Comment added', statusCode: Response::HTTP_CREATED);
    }
}
