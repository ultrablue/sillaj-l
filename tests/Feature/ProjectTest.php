<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {

        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->project = factory('App\Project')->create(['user_id' => $this->user->id]);

    }

    /** @test */
    public function an_authenticated_user_with_no_projects_sees_no_projects_in_projects_list()
    {
        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get('/projects');
        $response->assertSee('You don\'t have any projects, yet.', false);
    }


    /** @test */
    public function an_authenticated_user_can_view_all_projects()
    {
        // TODO Also, we need tests for non-authenticated users.
        //Given we have an authenticatd User;
        $user = factory('App\User')->create();
        // And that User has a Project;
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        // And another User made a shared Project;
        $sharedProject = factory('App\Project')->create(['user_id' => $this->user->id, 'share' => TRUE]);
        // When we get the list of Projects...
        $response = $this->actingAs($user)->get('/projects');
        // Do we see the Project that the User made?
        $response->assertSee($project->name, false);
        // Do we see the shared Project?
        $response->assertSee($sharedProject->name, false);

    }

    /** @test */
    public function an_unauthenticated_user_can_not_view_a_single_project()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $response = $this->get('/projects/' . $this->project->id);
    }

    /** @test */
    public function an_authenticated_user_can_view_a_single_project()
    {

        $response = $this->actingAs($this->user)->get('/projects/' . $this->project->id);
        $response->assertSee($this->project->name, false);
    }


    /** @test */
    public function an_authenticated_user_can_view_a_projects_tasks()
    {
        // TODO this one appears to fail randomly? Run the tests a few times to see it in action.
        $task = factory('App\Task')->create();
        $this->project->tasks()->attach($task);

        $response = $this->actingAs($this->user)->get('/projects/' . $this->project->id);
        $response->assertSee($task->name, false);

    }

    /** @test */
    public function an_unathenticated_user_can_not_view_a_projects_tasks()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $response = $this->get('/projects/' . $this->project->id);

    }

    /** @test */
    public function this_is_an_example_of_an_incomplete_test()
    {
        $this->markTestIncomplete(__FUNCTION__ . ' must be completed.');
    }
}
