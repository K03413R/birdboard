<?php

namespace Tests\Feature;

use App\Activity;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function creating_a_project()
    {
        $project = ProjectFactory::new()->create();
        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function($activity){
            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    function updating_a_project()
    {
        $project = ProjectFactory::new()->create();
        $original_title = $project->title;
        $project->update(['title' => 'Changed']);
        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity) use($original_title){
            $this->assertEquals('updated_project', $activity->description);

            $expected = [
                'before' => ['title' => $original_title],
                'after' => ['title' => 'Changed'],
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    function creating_a_task()
    {
//        $this->withoutExceptionHandling();
        $project = ProjectFactory::new()->create();
        $project->addTask('Some Task');
        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function(Activity  $activity){
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some Task', $activity->subject->body);
        });
    }

    /** @test */
    function completing_a_task()
    {
        $project = ProjectFactory::new()->withTasks(1)->create();
        $task = $project->tasks->first();
        $this->actingAs($project->owner)
            ->patch($task->path(), [
            'completed' => true
        ]);
        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function(Activity  $activity) use($task){
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals($task->body, $activity->subject->body);
        });
    }

    /** @test */
    function incompleting_a_task()
    {
        $project = ProjectFactory::new()->withTasks(1)->create();
        $task = $project->tasks->first();

        $task->complete();

        $this->assertCount(3, $project->activity);

        $this->actingAs($project->owner)->patch($task->path(), [
                'completed' => false
            ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);
        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test */
    function deleting_a_task()
    {
        $project = ProjectFactory::new()->withTasks(1)->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);

    }
}
