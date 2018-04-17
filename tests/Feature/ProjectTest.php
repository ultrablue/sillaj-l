<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp() {
        
        parent::setUp();
        $this->project = factory('App\Project')->create();

    }

    /** @test */
    public function a_user_can_view_all_projects()
    {

        $response = $this->get('/projects');
        $response->assertSee($this->project->name);

    }

    /** @test */
    public function a_user_can_view_a_single_thread()
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

}
