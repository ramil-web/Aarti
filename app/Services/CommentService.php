<?php

namespace App\Services;

use App\Attributes\Throws;
use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Exception;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(int $id, array $data): Comment
    {
        $comment = [
            'task_id' => $id,
            'user_id' => auth()->id(),
            'text'    => $data['text']
        ];
        return $this->commentRepository->create(data: $comment);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function getCommentsByTask(int $taskId): mixed
    {
        $withRelation = ['user'];
        return $this->commentRepository->listByTask(taskId: $taskId, withRelation: $withRelation);
    }

}
