<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /** @test */
    public function it_has_a_path()
    {
        $project = ProjectFactory::new()->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    /** @test */
    public function it_has_an_owner()
    {
        $project = ProjectFactory::new()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_add_a_test()
    {
        $project = ProjectFactory::new()->create();

        $task = $project->addTask($this->faker->sentence());

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }


}
