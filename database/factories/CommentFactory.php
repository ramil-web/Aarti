<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->sentence(),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function forTask(Task $task)
    {
        return $this->state(fn(array $attributes) => [
            'task_id' => $task->id
        ]);
    }

    public function byUser(User $user)
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id
        ]);
    }
}
