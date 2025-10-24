<?php

namespace App\Models;

use App\Attributes\Fillable;
use App\Attributes\HasManyRelation;
use App\Attributes\BelongsToRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    #[Fillable(field: 'title')]
    #[Fillable(field: 'description')]
    #[Fillable(field: 'start_date')]
    #[Fillable(field: 'end_date')]
    #[Fillable(field: 'user_id')]
    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'user_id'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    #[BelongsToRelation(relatedModel: User::class, foreignKey: 'user_id')]
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    #[HasManyRelation(relatedModel: Task::class)]
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
