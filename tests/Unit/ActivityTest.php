<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function it_has_a_user()
    {
        $project = ProjectFactory::new()->create();

        $this->assertInstanceOf(User::class, $project->activity->first()->user);
    }
}
