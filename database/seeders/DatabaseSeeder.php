<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 admins
        $admins = User::factory()->admin()->count(3)->create();

        // Create 3 managers
        $managers = User::factory()->manager()->count(3)->create();

        // Create 5 regular users
        $users = User::factory()->count(5)->create();

        // Combine all users for random assignment
        $allUsers = $admins->concat($managers)->concat($users);

        // Create 5 projects, each assigned to a random user
        $projects = collect();
        for ($i = 0; $i < 5; $i++) {
            $projects->push(
                Project::factory()
                    ->state(['user_id' => $allUsers->random()->id])
                    ->create()
            );
        }

        // Create 10 tasks (2 per project), assigned to random users
        $tasks = collect();
        foreach ($projects as $project) {
            for ($j = 0; $j < 2; $j++) {
                $tasks->push(
                    Task::factory()
                        ->state([
                            'project_id' => $project->id,
                            'assigned_to' => $allUsers->random()->id
                        ])
                        ->create()
                );
            }
        }

        // Create 10 comments (1 per task), assigned to random users
        foreach ($tasks as $task) {
            Comment::factory()
                ->state([
                    'task_id' => $task->id,
                    'user_id' => $allUsers->random()->id
                ])
                ->create();
        }
    }
}
