<?php

namespace Tests\Feature;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected ProjectRepositoryInterface $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ProjectRepositoryInterface::class);
        $this->app->instance(ProjectRepositoryInterface::class, $this->repositoryMock);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_projects_list()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $projects = Project::factory()->count(3)->make();

        $this->repositoryMock
            ->shouldReceive('all')
            ->with(['tasks'])
            ->once()
            ->andReturn($projects);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'data' => $projects->toArray(),
                'message' => null,
            ]);

        $this->assertTrue(Cache::has('projects_list'));
    }

    public function test_store_creates_project_and_clears_cache()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $data = [
            'title'       => 'New Project',
            'description' => 'Test Description',
            'start_date'  => '2025-10-18',
            'end_date'    => '2025-12-31',
        ];

        $project = Project::factory()->make(array_merge($data, ['user_id' => $user->id]));

        $this->repositoryMock
            ->shouldReceive('create')
            ->with(array_merge($data, ['user_id' => $user->id]))
            ->once()
            ->andReturn($project);

        Cache::put('projects_list', collect([]));

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/projects', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'success' => true,
                'data' => $project->toArray(),
                'message' => null,
            ]);

        $this->assertFalse(Cache::has('projects_list'));
    }

    public function test_show_returns_single_project()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->make(['id' => 1]);

        $this->repositoryMock
            ->shouldReceive('find')
            ->with(1, ['tasks'])
            ->once()
            ->andReturn($project);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/projects/1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'data' => $project->toArray(),
                'message' => null,
            ]);

        $this->assertTrue(Cache::has('project:1'));
    }

    public function test_show_throws_exception_for_nonexistent_project()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->repositoryMock
            ->shouldReceive('find')
            ->with(999, ['tasks'])
            ->once()
            ->andReturn(null);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/projects/999');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson(['message' => 'Project not found']);
    }

    public function test_update_updates_project_and_clears_cache()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['id' => 1]);

        $data = [
            'title'       => 'Updated Title',
            'description' => 'Updated Description',
            'start_date'  => '2025-10-18 00:00:00',
            'end_date'    => '2025-12-31 00:00:00',
        ];

        $this->repositoryMock
            ->shouldReceive('update')
            ->with(1, $data)
            ->once()
            ->andReturn(true);

        Cache::put('projects_list', collect([]));
        Cache::put('project:1', $project);

        Sanctum::actingAs($user, ['*']);

        $request = Mockery::mock(ProjectRequest::class);
        $request->shouldReceive('validated')->andReturn($data);
        $this->app->instance(ProjectRequest::class, $request);

        $this->withoutMiddleware();

        $response = $this->patchJson('/api/projects/1', $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'data' => ['updated' => true],
                'message' => null,
            ]);

        $this->assertFalse(Cache::has('projects_list'));
        $this->assertFalse(Cache::has('project:1'));
    }

    public function test_destroy_deletes_project_and_clears_cache()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['id' => 1]);

        $this->repositoryMock
            ->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(true);

        Cache::put('projects_list', collect([]));
        Cache::put('project:1', $project);

        Sanctum::actingAs($user, ['*']);
        $this->withoutMiddleware();

        $response = $this->deleteJson('/api/projects/1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Project deleted',
            ]);

        $this->assertFalse(Cache::has('projects_list'));
        $this->assertFalse(Cache::has('project:1'));
    }
}
