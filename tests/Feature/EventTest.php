<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use App\Project;
use App\Task;
use App\Event;

class EventTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_with_no_events_for_a_given_date_sees_no_events_in_the_dashboard()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('You don\'t have any Events, yet.', false);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_view_all_events_for_a_given_date()
    {
        // Given an authenticated User;
        $user = User::factory()->create();
        // and a date;
        $date = Carbon::now();
        // the User can see Events for that Date.
        $firstProject = Project::factory()->create(['user_id' => $user->id]);
        $firstTask = Task::factory()->create(['user_id' => $user->id]);

        $firstEvent = Event::factory()->create(['user_id' => $user->id, 'project_id' => $firstProject->id, 'task_id' => $firstTask->id, 'event_date' => $date]);
        $response = $this->actingAs($user)->get('/');
        $response->assertSee(str_limit($firstEvent->note, 30), false);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_view_new_the_event_form()
    {
        // Given a User...
        $user = User::factory()->create();
        // ...that User can see the Event Form.
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('Create or Edit an Event', false);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_a_new_task_with_a_start_and_end_date()
    {
        // Given a User...
        $user = User::factory()->create();
        // ... a Date
        $date = Carbon::now();
    }


    // TODO More Tests:
    // Test POST
    // Make sure validation works.
    // Start/End versus duration.
    // Shared tasks/projects?

    // GET /events should return a 405.
    // Create (POST) an Event as an unauthenticated User should fail.
    // Create an Event with start and end times.
    // Create an Event with just start time.
    // Create an Event with just an end time.
    // Create an Event with just a Duration.

    // GET events/{event} should return a 200.
    //                           the proper Event should be displayed.
    // PUT events/{event} should return a 200.
    //                           the updated Event should be displayed.

    /**
     * 
     * Tests GET /events/{event}
     * 
     * @return void
     */
    public function test_an_authenticated_user_can_get_an_event_by_id()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $date = Carbon::now();

        $event = Event::factory()->create(['user_id' => $user->id, 'project_id' => $project->id, 'task_id' => $task->id, 'event_date' => $date]);
        $response = $this->actingAs($user)->get('/events/'.$event->id);
        $response->assertStatus(200);
        $response->assertSee($event->note, false);
    }
}
