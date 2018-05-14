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
    public function a_user_can_view_all_projects()
    {
        // TODO These tests need to be authenticated users!
        // TODO Also, we need tests for non-authenticated users.
        $response = $this->get('/projects');
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
