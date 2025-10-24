<?php

namespace App\Models;

use App\Attributes\BelongsToRelation;
use App\Attributes\Fillable;
use App\Attributes\HasManyRelation;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    #[fillable(field: 'title')]
    #[Fillable(field: 'description')]
    #[fillable(field: 'status')]
    #[fillable(field: 'due_date')]
    #[fillable(field: 'project_id')]
    #[fillable(field: 'assigned_to')]
    protected $fillable = [
        'title', 'description', 'status', 'due_date', 'project_id', 'assigned_to'
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
    ];

    #[BelongsToRelation(relatedModel: Project::class)]
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }


  #[HasManyRelation(relatedModel: Comment::class )]
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
