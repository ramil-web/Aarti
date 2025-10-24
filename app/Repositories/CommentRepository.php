<?php

namespace App\Repositories;

use App\Attributes\Throws;
use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

readonly class CommentRepository implements CommentRepositoryInterface
{
    public function __construct(private Comment $comment)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(array $data): Comment
    {
        try {
            return $this->comment->query()->create(attributes: $data);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   #[Throws(exceptionClass: Exception::class)]
    public function listByTask(int $taskId, array $withRelation): Collection
    {
        try {
            return $this->comment->query()
                ->with($withRelation)
                ->where('task_id', $taskId)
                ->get();
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
