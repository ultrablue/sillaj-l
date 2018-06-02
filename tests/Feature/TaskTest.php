<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() {
        
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->task = factory('App\Task')->create(['user_id' => $this->user->id]);

    }

 
    /** @test */
    public function an_authenticated_user_can_view_all_tasks()
    {
        // Given we have an authenticatd User;
        $user = factory('App\User')->create();
        // And that User has a Task;
        $task = factory('App\Task')->create(['user_id' => $user->id]);
        // And antother User made a shared Task;
        $otherUser = factory('App\User')->create();
        $sharedTask = factory('App\Task')->create(['user_id' => $otherUser->id, 'share' => TRUE]);
        // When we get the list of Tasks... 
        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
        // Do we see the Task that the User made?
        $response->assertSee($task->name);
        // Do we see the shared Task?
        $response->assertSee($sharedTask->name);

}
    
    
    /** @test */
    public function an_authenticated_user_with_no_tasks_sees_no_tasks_in_tasks_list() {
        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get('/tasks');
        $response->assertSee('You don\'t have any tasks, yet.');
    }

    /** @test */
    public function an_unauthenticated_user_can_not_view_a_single_task()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $response = $this->get('/tasks/' . $this->task->id);
    }

   /** @test */
    public function an_authenticated_user_can_view_a_single_task()
    {

        $response = $this->actingAs($this->user)->get('/tasks/' . $this->task->id);
        $response->assertSee($this->task->name);
    }

    /** @test */
    public function an_authenticated_user_can_view_a_tasks_projects()
    {
        $firstUser = factory('App\User')->create();
        $secondUser = factory('App\User')->create();
        $firstProject = factory('App\Project')->create(['user_id' => $firstUser->id]);
        $secondProject = factory('App\Project')->create(['user_id' =>$secondUser->id]);
        $this->task->projects()->attach($firstProject);
        $this->task->projects()->attach($secondProject);
        
        $response = $this->actingAs($this->user)->get('/tasks/' . $this->task->id);
        $response->assertSee($firstProject->name);
        $response->assertSee($secondProject->name);
         
    }

    /** @test */
    public function an_unathenticated_user_can_not_view_a_tasks_projects()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        
        $response = $this->get('/tasks/' . $this->task->id);
         
    } 

}
