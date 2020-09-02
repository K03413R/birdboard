<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_project_owner_can_invite_a_user()
    {
        $project = ProjectFactory::new()->create();

        $user_to_invite = UserFactory::new()->create();
        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => $user_to_invite->email
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($user_to_invite));
    }

    /** @test */
    function the_invite_user_must_have_a_birdboard_account()
    {
        $project = ProjectFactory::new()->create();

        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => $this->faker->email
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a Birdboard account.'
        ], null, 'invitation');
    }

    /** @test */
    function invited_users_can_update_project_details()
    {
        $project = ProjectFactory::new()->create();

        $project->invite($user = UserFactory::new()->create());

        $this->actingAs($user)
            ->post(action('ProjectTasksController@store', $project), $attrs = ['body' => 'foo']);

        $this->assertDatabaseHas('tasks', $attrs);
    }

    /** @test */
    function only_the_owner_of_a_project_can_invite_a_user()
    {
        $project = ProjectFactory::new()->create();
        $user = UserFactory::new()->create();

        $this->actingAs($user)
            ->post($project->path() . '/invitations', [
            'email' => UserFactory::new()->create()->email
        ])->assertForbidden();

        $project->invite($user);

        $this->actingAs($user)
            ->post($project->path() . '/invitations', [
                'email' => UserFactory::new()->create()->email
            ])->assertForbidden();
    }
}
