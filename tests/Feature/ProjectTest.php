<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp() {
        
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->project = factory('App\Project')->create(['user_id' => $this->user->id]);

    }

    /** @test */
    public function an_authenticated_user_with_no_projects_sees_no_projects_in_projects_list() {
        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get('/projects');
        $response->assertSee('You don\'t have any projects, yet.');
    }




    /** @test */
    public function an_authenticated_user_can_view_all_projects()
    {
        // First, we need to make a User.
        // The User should be able to see:
        //  All of the User's Projects.
        //  All shared Projects.
        //
        // TODO These tests need to be authenticated users!
        // TODO Also, we need tests for non-authenticated users.
        //Given we have an authenticatd User
        $user = factory('App\User')->create();
        //$this->be($user);
        $response = $this->actingAs($user)->get('/projects');
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        //$response->assertViewHas('projects');
        $response->assertSee($this->project->name);

    }

    /** @test */
    public function a_user_can_view_a_single_project()
    {

        $response = $this->get('/projects/' . $this->project->id);
        $response->assertSee($this->project->name);
    }

    
    /** @test */
    public function a_user_can_view_a_projects_tasks()
    {
        $task = factory('App\Task')->create();
        $this->project->tasks()->attach($task); 
        
        $response = $this->get('/projects/' . $this->project->id);
        $response->assertSee($task->name);
         
    }

    /** @test */
    public function this_is_an_example_of_an_incomplete_test()
    {
        $this->markTestIncomplete(__FUNCTION__ . ' must be completed.');
    }
}
