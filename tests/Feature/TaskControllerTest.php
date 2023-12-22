<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\Helpers;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    use Helpers;

    protected $url = '/tasks/';

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
        $this->test_show_with_auth();
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

        $response->assertViewIs('task.create');
    }

    public function test_store_without_auth(): void
    {
        $response = $this->post(
            $this->url,
            [
                'name' => 'Test task',
                'description' => 'Task descr',
                'status_id' => 1,
                'assigned_to_id' => 1,
            ]
        );

        $response->assertRedirect(route('login'));
    }

    public function test_store_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();

        $this
            ->actingAs($this->user)
            ->post(
                $this->url,
                [
                    'name' => 'Test task',
                    'description' => 'Task descr',
                    'status_id' => $taskStatus->id,
                    'assigned_to_id' => $this->user->id,
                ]
        );

        $task = Task::find(1);

        $this->assertEquals('Test task', $task->name);
        $this->assertEquals('Task descr', $task->description);
        $this->assertEquals($taskStatus->id, $task->status->id);
        $this->assertEquals($this->user->id, $task->creator->id);
        $this->assertEquals($this->user->id, $task->assignedUser->id);
    }

    public function test_show_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();
        $task = $this->createTask($this->user, $taskStatus);

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $task->id);

        $response->assertViewIs('task.show');
        $response->assertSee($task->name, false);
    }

    public function test_edit_without_auth(): void
    {
        $response = $this->get($this->url . '1/edit');

        $response->assertRedirect(route('login'));
    }

    public function test_edit_with_auth(): void
    {
        $taskStatus = $this->createTaskStatus();
        $task = $this->createTask($this->user, $taskStatus);

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $task->id . '/edit');

        $response->assertViewIs('task.edit');
        $response->assertSee($task->name, false);
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
        $assignedUser = User::factory()->create();
        $taskStatus = $this->createTaskStatus();
        $task = $this->createTask($this->user, $taskStatus);
        $taskStatus2 = $this->createTaskStatus();

        $this
            ->actingAs($this->user)
            ->patch(
                $this->url . $task->id,
                [
                    'id' => $task->id,
                    'name' => 'Edited task',
                    'description' => 'Edited task descr',
                    'status_id' => $taskStatus2->id,
                    'assigned_to_id' => $assignedUser->id,
                ]
            );

        $task->refresh();

        $this->assertEquals('Edited task', $task->name);
        $this->assertEquals('Edited task descr', $task->description);
        $this->assertEquals($taskStatus2->id, $task->status->id);
        $this->assertEquals($assignedUser->id, $task->assignedUser->id);
    }

    public function test_delete_without_auth(): void
    {
        $response = $this->delete($this->url . '1');

        $response->assertRedirect(route('login'));
    }

    public function test_delete_with_auth_correct(): void
    {
        $taskStatus = $this->createTaskStatus();
        $task = $this->createTask($this->user, $taskStatus);

        $this
            ->actingAs($this->user)
            ->delete($this->url . $task->id);

        $this->expectException(ModelNotFoundException::class);

        Task::findOrFail($task->id);
    }

    public function test_delete_with_auth_incorrect(): void
    {
        $taskStatus = $this->createTaskStatus();
        $task = $this->createTask($this->user, $taskStatus);
        $anotherUser = User::factory()->create();

        $response = $this
            ->actingAs($anotherUser)
            ->delete($this->url . $task->id);

        $this->assertEquals(401, $response->status());

        Task::findOrFail($task->id);
    }


}
