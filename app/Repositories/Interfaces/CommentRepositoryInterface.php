<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function create(array $data): Comment;
    public function listByTask(int $taskId, array $withRelation);
}
