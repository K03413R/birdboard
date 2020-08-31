<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\Factories\TaskFactory;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test  */
    public function it_belongs_to_a_project()
    {
        $task = TaskFactory::new()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_has_a_path()
    {
        $task = TaskFactory::new()->create();

        $this->assertEquals("/projects/{$task->project->id}/tasks/{$task->id}", $task->path());
    }

    /** @test */
    function it_can_be_completed()
    {
        $task = TaskFactory::new()->create();
        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    function it_can_be_incompleted()
    {
        $task = TaskFactory::new()->create(['completed' => true]);
        $this->assertTrue($task->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}
