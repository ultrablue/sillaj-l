<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;

class EventTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function an_event_has_an_owner() {
        // Testing the Model's owner() method.
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id'=> $user->id]);
        $task    = factory('App\Task')->create(['user_id'=> $user->id]);
        $event   = factory('App\Event')->create(['user_id'=> $user->id, 'project_id' => $project->id, 'task_id' => $task->id]);
        
        $this->assertInstanceOf('App\User', $event->owner);
    }


    /** @test */
    public function an_event_has_a_task() {
        // Testing the Model's owner() method.
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id'=> $user->id]);
        $task    = factory('App\Task')->create(['user_id'=> $user->id]);
        $event   = factory('App\Event')->create(['user_id'=> $user->id, 'project_id' => $project->id, 'task_id' => $task->id]);
        
        $this->assertInstanceOf('App\Task', $event->task);
    }


    /** @test */
    public function an_event_has_a_project() {
        // Testing the Model's owner() method.
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id'=> $user->id]);
        $task    = factory('App\Task')->create(['user_id'=> $user->id]);
        $event   = factory('App\Event')->create(['user_id'=> $user->id, 'project_id' => $project->id, 'task_id' => $task->id]);
        
        $this->assertInstanceOf('App\Project', $event->project);
    }

}
