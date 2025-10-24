<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending','in_progress','done']),
            'due_date' => $this->faker->date(),
            // Используем существующие проекты и пользователей, если указаны через state
            'project_id' => Project::factory(),
            'assigned_to' => User::factory(),
        ];
    }

    public function forProject(Project $project)
    {
        return $this->state(fn(array $attributes) => [
            'project_id' => $project->id
        ]);
    }

    public function assignedTo(User $user)
    {
        return $this->state(fn(array $attributes) => [
            'assigned_to' => $user->id
        ]);
    }
}
