<?php

namespace App\Models;

use App\Attributes\Fillable;
use App\Attributes\HasManyRelation;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    #[fillable(field: 'name')]
    #[fillable(field: 'email')]
    #[fillable(field: 'password')]
    #[fillable(field: 'phone')]
    #[fillable(field: 'role')]

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    #[HasManyRelation(relatedModel: Project::class)]
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

   #[HasManyRelation(relatedModel: Task::class)]
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

   #[HasManyRelation(relatedModel: Comment::class)]
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


   public function hasRole(UserRoleEnum $role): bool
    {
        return $this->role === $role->value;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRoleEnum::ADMIN);
    }

    public function isManager(): bool
    {
        return $this->hasRole(UserRoleEnum::MANAGER);
    }
}
