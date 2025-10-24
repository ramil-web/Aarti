<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement(['admin','manager','user']),
        ];
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function manager()
    {
        return $this->state(fn (array $attributes) => ['role' => 'manager']);
    }
}
