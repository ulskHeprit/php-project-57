<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CanAssertFlash;
use Tests\TestCase;
use Tests\Traits\Helpers;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanAssertFlash;
    use Helpers;

    protected $url = '/labels/';

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

        $response->assertViewIs('label.create');
    }

    public function test_store_without_auth(): void
    {
        $response = $this->post(
            $this->url,
            [
                'name' => 'Test label'
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
                    'name' => 'Test label',
                    'description' => 'Test label descr',
                ]
            );

        $label = Label::find(1);

        $this->assertEquals('Test label', $label->name);
        $this->assertEquals('Test label descr', $label->description);
    }

    public function test_show_with_auth(): void
    {
        $label = $this->createLabel();

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $label->id);

        $response->assertViewIs('label.show');
        $response->assertSee($label->name, false);
        $response->assertSee($label->description, false);
    }

    public function test_edit_without_auth(): void
    {
        $response = $this->get($this->url . '1/edit');

        $response->assertRedirect(route('login'));
    }

    public function test_edit_with_auth(): void
    {
        $label = $this->createLabel();

        $response = $this
            ->actingAs($this->user)
            ->get($this->url . $label->id . '/edit');

        $response->assertViewIs('label.edit');
        $response->assertSee($label->name, false);
        $response->assertSee($label->description, false);
    }

    public function test_update_without_auth(): void
    {
        $response = $this->patch(
            $this->url . '1',
            [
                'id' => 1,
                'name' => 'Edited test label'
            ]
        );

        $response->assertRedirect(route('login'));
    }

    public function test_update_with_auth(): void
    {
        $label = $this->createLabel();
        $newDate = [
            'id' => $label->id,
            'name' => 'Edited test label',
            'description' => 'Edited test label descr'
        ];

        $this
            ->actingAs($this->user)
            ->patch(
                $this->url . $label->id,
                $newDate
            );

        $label->refresh();

        $this->assertEquals($newDate['name'], $label->name);
        $this->assertEquals($newDate['description'], $label->description);
    }

    public function test_delete_without_auth(): void
    {
        $response = $this->delete($this->url . '1');

        $response->assertRedirect(route('login'));
    }

    public function test_delete_with_auth(): void
    {
        $label = $this->createLabel();

        $this
            ->actingAs($this->user)
            ->delete($this->url . $label->id);

        $this->expectException(ModelNotFoundException::class);

        Label::findOrFail($label->id);
    }

    public function test_delete_with_auth_with_task(): void
    {
        $taskStatus = $this->createTaskStatus();
        $label = $this->createLabel();
        $this->createTask($this->user, $taskStatus, $label);

        $this
            ->actingAs($this->user)
            ->delete($this->url . $label->id);

        $this->assertFlash('warning', __('Failed to delete label'));

        Label::findOrFail($label->id);
    }
}
