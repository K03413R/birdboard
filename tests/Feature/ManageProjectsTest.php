<?php

namespace Tests\Feature;

use App\Project;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProjectFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test*/
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertSuccessful();

        $this->followingRedirects()
            ->post('/projects', $attributes = ProjectFactory::new()->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes'])
        ;
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::new()->create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
                'notes' => 'Changed',
                'title' => 'Changed',
                'description' => 'Changed'
                ])->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::new()->create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseMissing('projects', $project->only('id'));

    }

    /** @test */
    function unauthorized_users_cannot_delete_projects()
    {
        $project = ProjectFactory::new()->create();

        $this->delete($project->path())->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertForbidden();

        $project->invite($user);

        $this->actingAs($user)
            ->delete($project->path())->assertForbidden();
    }

    /** @test */
    public function a_user_can_update_a_projects_notes()
    {
        $project = ProjectFactory::new()->create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'Changed']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = ProjectFactory::new()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function guest_cannot_manage_projects()
    {
        $project = ProjectFactory::new()->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = ProjectFactory::new()->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::new()->create(['description' => 'Lorem Ipsum']);
        $this->actingAs($project->owner)->get($project->path())
            ->assertSee($project->title)
            ->assertSee('Lorem Ipsum')
        ;
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();
        $project = ProjectFactory::new()->create();
        $this->get($project->path())->assertForbidden();

    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();
        $project = ProjectFactory::new()->create();
        $this->patch($project->path(), ['notes' => 'changed'])->assertForbidden();

    }

    /** @test */
    function a_user_can_see_project_invites_on_dashboard()
    {
        $project = ProjectFactory::new()->create();

        $john = UserFactory::new()->create();

        $project->invite($john);

        $this->actingAs($john)
            ->get(RouteServiceProvider::HOME)
            ->assertSee($project->title);
    }

    /** @test */
    function tasks_can_be_created_when_a_project_is_created()
    {
        $this->signIn();

        $this->get('/projects/create')->assertSuccessful();

        $attributes = $attributes = ProjectFactory::new()->raw();
        $attributes['tasks'] = [['body' => 'First Task']];

        $this->post('/projects', $attributes);

        $this->assertDatabaseHas('tasks', ['body' => 'First Task']);
    }


}
