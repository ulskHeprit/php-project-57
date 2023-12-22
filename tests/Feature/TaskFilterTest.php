<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Traits\Helpers;

class TaskFilterTest extends TestCase
{
    use RefreshDatabase;
    use Helpers;

    protected $url = '/tasks/';

    public function test_without_fitlers()
    {
        [, , , $tasks] = $this->createTasks();

        $response = $this->get($this->url);

        foreach ($tasks as $task) {
            $response->assertSee($task->name);
        }
    }

    public function test_with_status_fitlers()
    {
        [$statuses, , , $tasks] = $this->createTasks();
        $filterStatus = $statuses['new'];

        $filters = [
            'filter' => [
                'status_id' => $filterStatus->id,
            ],
        ];

        $response = $this->get($this->url . '?' . Arr::query($filters));

        foreach ($tasks as $task) {
            if ($task->status->id === $filterStatus->id) {
                $response->assertSee($task->name);
            } else {
                $response->assertDontSee($task->name);
            }
        }
    }

    public function test_with_created_by_id_fitlers()
    {
        [, $creators, , $tasks] = $this->createTasks();
        $filterCreated = $creators[1];

        $filters = [
            'filter' => [
                'created_by_id' => $filterCreated->id,
            ],
        ];

        $response = $this->get($this->url . '?' . Arr::query($filters));

        foreach ($tasks as $task) {
            if ($task->creator->id === $filterCreated->id) {
                $response->assertSee($task->name);
            } else {
                $response->assertDontSee($task->name);
            }
        }
    }

    public function test_with_assigned_by_id_fitlers()
    {
        [, , $assigners, $tasks] = $this->createTasks();
        $filterAssigned = $assigners[2];

        $filters = [
            'filter' => [
                'assigned_to_id' => $filterAssigned->id,
            ],
        ];

        $response = $this->get($this->url . '?' . Arr::query($filters));

        foreach ($tasks as $task) {
            if ($task->assignedUser->id === $filterAssigned->id) {
                $response->assertSee($task->name);
            } else {
                $response->assertDontSee($task->name);
            }
        }
    }

    public function test_with_multiple_fitlers()
    {
        [$statuses, $creators, $assigners, $tasks] = $this->createTasks();
        $filterStatus = $statuses['processing'];
        $filterCreated = $creators[1];
        $filterAssigned = $assigners[1];

        $filters = [
            'filter' => [
                'status_id' => $filterStatus->id,
                'created_by_id' => $filterCreated->id,
                'assigned_to_id' => $filterAssigned->id,
            ],
        ];

        $response = $this->get($this->url . '?' . Arr::query($filters));

        foreach ($tasks as $task) {
            if (
                $task->status->id === $filterStatus->id
                && $task->creator->id === $filterCreated->id
                && $task->assignedUser->id === $filterAssigned->id
            ) {
                $response->assertSee($task->name);
            } else {
                $response->assertDontSee($task->name);
            }
        }
    }

    public function createTasks()
    {
        $taskStatuses = [
            'new' => $this->createTaskStatus('new'),
            'processing' => $this->createTaskStatus('processing'),
            'completed' => $this->createTaskStatus('completed')
        ];

        $assigners = [
            User::factory()->create(),
            User::factory()->create(),
            User::factory()->create(),
        ];

        $creators = [
            User::factory()->create(),
            User::factory()->create(),
            User::factory()->create(),
        ];

        $tasks = [];
        $tasks[] = $this->createTask($creators[0], $taskStatuses['new'], null, $assigners[0], 'Task 1');
        $tasks[] = $this->createTask($creators[0], $taskStatuses['processing'], null, $assigners[0], 'Task 2');
        $tasks[] = $this->createTask($creators[0], $taskStatuses['processing'], null, $assigners[1], 'Task 3');
        $tasks[] = $this->createTask($creators[0], $taskStatuses['completed'], null, $assigners[0], 'Task 4');
        $tasks[] = $this->createTask($creators[1], $taskStatuses['new'], null, $assigners[1], 'Task 5');
        $tasks[] = $this->createTask($creators[1], $taskStatuses['processing'], null, $assigners[1], 'Task 6');
        $tasks[] = $this->createTask($creators[1], $taskStatuses['processing'], null, $assigners[1], 'Task 7');
        $tasks[] = $this->createTask($creators[1], $taskStatuses['completed'], null, $assigners[1], 'Task 8');
        $tasks[] = $this->createTask($creators[2], $taskStatuses['new'], null, $assigners[2], 'Task 9');
        $tasks[] = $this->createTask($creators[2], $taskStatuses['processing'], null, $assigners[2], 'Task -10');
        $tasks[] = $this->createTask($creators[2], $taskStatuses['processing'], null, $assigners[2], 'Task -11');
        $tasks[] = $this->createTask($creators[2], $taskStatuses['completed'], null, $assigners[2], 'Task -12');

        return [collect($taskStatuses), collect($creators), collect($assigners), collect($tasks)];
    }
}
