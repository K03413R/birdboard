<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function it_has_a_path()
    {
        $project = ProjectFactory::new()->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    /** @test */
    function it_has_an_owner()
    {
        $project = ProjectFactory::new()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    function it_can_add_a_test()
    {
        $project = ProjectFactory::new()->create();

        $task = $project->addTask($this->faker->sentence());

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    function it_can_invite_a_user()
    {
        $project = ProjectFactory::new()->create();

        $project->invite($user = UserFactory::new()->create());

        $this->assertTrue($project->members->contains($user));
    }
}
