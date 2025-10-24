<?php

namespace App\Models;

use App\Attributes\BelongsToRelation;
use App\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    #[fillable(field: 'task_id')]
    #[fillable(field: 'user_id')]
    #[fillable(field: 'text')]
    protected $fillable = [
        'task_id', 'user_id', 'text'
    ];

    #[BelongsToRelation(relatedModel: Task::class)]
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    #[BelongsToRelation(relatedModel: User::class)]
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
