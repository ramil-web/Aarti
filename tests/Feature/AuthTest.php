<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $data = [
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['data', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }
}
