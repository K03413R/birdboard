<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ProjectFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_has_projects()
    {
        $user = UserFactory::new()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    function it_has_shared_projects()
    {
        $john = UserFactory::new()->create();
        $nick = UserFactory::new()->create();

        $project = ProjectFactory::new()->create();
        $project_1 = ProjectFactory::new()->create();

        $project->invite($john);
        $this->assertTrue($john->sharedProjects->contains($project));

        $project_1->invite($nick);
        $this->assertFalse($john->sharedProjects->contains($project_1));
    }

    /** @test */
    function it_has_all_projects()
    {
        $john = UserFactory::new()->create();
        $nick = UserFactory::new()->create();

        ProjectFactory::new()->withOwner($john)->create();
        $this->assertCount(1, $john->allProjects());

        $project = ProjectFactory::new()->create();
        $project->invite($nick);
        $this->assertCount(1, $john->fresh()->allProjects());

        $project->invite($john);
        $this->assertCount(2, $john->fresh()->allProjects());
    }
}
