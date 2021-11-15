<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Task;
use App\User;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {

        parent::setUp();
        $this->user = User::factory()->create();
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);

    }


    /** @test */
    public function an_authenticated_user_can_view_all_tasks()
    {
        // Given we have an authenticatd User;
        $user = User::factory()->create();
        // And that User has a Task;
        $task = Task::factory()->create(['user_id' => $user->id]);
        // And antother User made a shared Task;
        $otherUser = User::factory()->create();
        $sharedTask = Task::factory()->create(['user_id' => $otherUser->id, 'share' => TRUE]);
        // When we get the list of Tasks...
        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
        // Do we see the Task that the User made?
        $response->assertSee($task->name, false);
        // Do we see the shared Task?
        $response->assertSee($sharedTask->name,false);

    }


    /** @test */
    public function an_authenticated_user_with_no_tasks_sees_no_tasks_in_tasks_list()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/tasks');
        $response->assertSee('You don\'t have any tasks, yet.', false);
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
        $response->assertSee($this->task->name, false);
    }

    /** @test */
    public function an_authenticated_user_can_view_a_tasks_projects()
    {
        // FIXME - This one's probably really not working as expected.
        $this->markTestSkipped("This is probably really broken.");
        $firstUser = factory('App\User')->create();
        $secondUser = factory('App\User')->create();
        $firstProject = factory('App\Project')->create(['user_id' => $firstUser->id]);
        $secondProject = factory('App\Project')->create(['user_id' => $secondUser->id]);
        $this->task->projects()->attach($firstProject);
        $this->task->projects()->attach($secondProject);

        $response = $this->actingAs($this->user)->get('/tasks/' . $this->task->id);
        $response->assertSee($firstProject->name, false);
        $response->assertSee($secondProject->name, false);

    }

    /** @test */
    public function an_unathenticated_user_can_not_view_a_tasks_projects()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $response = $this->get('/tasks/' . $this->task->id);

    }

}
