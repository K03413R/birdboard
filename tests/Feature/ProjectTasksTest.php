<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\Factories\TaskFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_cannot_add_tasks_to_project()
    {
        $project = ProjectFactory::new()->create();
        $this->post($project->path() . '/tasks')->assertRedirect('/login');
    }

    /** @test */
    public function only_the_owner_of_project_can_add_tasks()
    {
        $this->signIn();

        $project = ProjectFactory::new()->create();

        $attributes = TaskFactory::new()->raw();

        $this->post($project->path() . '/tasks', $attributes)->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['body' => $attributes['body']]);
    }

    /** @test */
    public function only_the_owner_of_project_can_update_a_task()
    {
        $this->signIn();
        $project = ProjectFactory::new()
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::new()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Lorem Ipsum']);

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee('Lorem Ipsum');
    }

    /** @test */
    public function a_project_requires_a_body()
    {
        $project = ProjectFactory::new()->create();

        $attributes = TaskFactory::new()->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    /** @test  */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::new()
            ->withTasks(1)
            ->create();
        $task = $project->tasks->first();

        $this->actingAs($project->owner)
            ->patch($task->path(), [
            'body' => 'changed'
        ]);

        $this->assertDatabaseHas('tasks', [
           'id' => $task->id,
        ]);
    }

    /** @test */
    function a_task_can_be_completed()
    {
        $project = ProjectFactory::new()
            ->withTasks(1)
            ->create();
        $task = $project->tasks->first();

        $this->actingAs($project->owner)
            ->patch($task->path(), [
                'completed' => true
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }

    /** @test */
    function a_task_can_be_incompleted()
    {
        $project = ProjectFactory::new()
            ->withTasks(1)
            ->create();
        $task = $project->tasks->first();

        $this->actingAs($project->owner)
            ->patch($task->path(), [
                'completed' => true
            ]);

        $this->actingAs($project->owner)
            ->patch($task->path(), [
                'completed' => false
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false,
        ]);
    }
}
