<?php

namespace Database\Factories;

use App\Attributes\FactoryDefinition;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    #[FactoryDefinition(field: 'title', type: 'string')]
    #[FactoryDefinition(field: 'description', type: 'string')]
    #[FactoryDefinition(field: 'start_date', type: 'datetime')]
    #[FactoryDefinition(field: 'end_date', type: 'datetime')]
    #[FactoryDefinition(field: 'user_id', type: 'integer')]
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'title' => $this->faker->sentence(nbWords: 3),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, '+1 year'),
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $user->id,
        ]);
    }
}
