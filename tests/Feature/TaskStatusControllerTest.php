<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CanAssertFlash;
use Tests\TestCase;
use Tests\Traits\Helpers;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanAssertFlash;
    use Helpers;

    protected $url = '/task_statuses/';

    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $response = $this->get($this->url);

        $response->assertStatus(200);
    }

    public function test_show_without_auth(): void
    {
        $response = $this->get($this->url . '1');

        $response->assertRedirect(route('login'));
    }

    public function test_create_without_auth(): void
    {
        $response = $this
            ->get($this->url . 'create');

        $response->assertRedirect(route('login'));
    }

    public function test_create_with_auth(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get($this->url . 'create');

        $response->assertViewIs('task_status.create');
    }

    public function test_store_without_auth(): void
    {
        $response = $this->post(
            $this->url,
            [
                'name' => 'Test status'
            ]
        );

        $response->assertRedirect(route('login'));
    }

    public function test_store_with_auth(): void
    {
        $this
            ->actingAs($this->user)
            ->post(
                $this->url,
                [
                    'name' => 'Test status'
                ]
        );

        $taskStatus = TaskStatus::find(1);

        $this->assertEquals('Test status', $taskStatus->name);
    }

    public function test_show_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $taskStatus->id);

        $response->assertViewIs('task_status.show');
        $response->assertSee($taskStatus->name, false);
    }

    public function test_edit_without_auth(): void
    {
        $response = $this->get($this->url . '1/edit');

        $response->assertRedirect(route('login'));
    }

    public function test_edit_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $taskStatus->id . '/edit');

        $response->assertViewIs('task_status.edit');
        $response->assertSee($taskStatus->name, false);
    }

    public function test_update_without_auth(): void
    {
        $response = $this->patch(
            $this->url . '1',
            [
                'id' => 1,
                'name' => 'Edited test status'
            ]
        );

        $response->assertRedirect(route('login'));
    }

    public function test_update_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();

        $this
            ->actingAs($this->user)
            ->patch(
            $this->url . $taskStatus->id,
            [
                'id' => $taskStatus->id,
                'name' => 'Edited test status'
            ]
        );

        $taskStatus->refresh();

        $this->assertEquals('Edited test status', $taskStatus->name);
    }

    public function test_delete_without_auth(): void
    {
        $response = $this->delete($this->url . '1');

        $response->assertRedirect(route('login'));
    }

    public function test_delete_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();

        $this
            ->actingAs($this->user)
            ->delete($this->url . $taskStatus->id);

        $this->expectException(ModelNotFoundException::class);

        TaskStatus::findOrFail($taskStatus->id);
    }

    public function test_delete_with_auth_with_task(): void
    {
        $taskStatus = $this->createTaskStatus();
        $this->createTask($this->user, $taskStatus);

        $this
            ->actingAs($this->user)
            ->delete($this->url . $taskStatus->id);

        $this->assertFlash('warning', __('Failed to delete status'));

        TaskStatus::findOrFail($taskStatus->id);
    }
}
