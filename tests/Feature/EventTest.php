<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_with_no_events_for_a_given_date_sees_no_events_in_the_dashboard()
    {
        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('You don\'t have any Events, yet.', false);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_view_all_events_for_a_given_date()
    {
        // Given an authenticated User;
        $user = factory('App\User')->create();
        // and a date;
        $date = Carbon::now();
        // the User can see Events for that Date.
        $firstProject = factory('App\Project')->create(['user_id' => $user->id]);
        $firstTask = factory('App\Task')->create(['user_id' => $user->id]);

        $firstEvent = factory('App\Event')->create(['user_id' => $user->id, 'project_id' => $firstProject->id, 'task_id' => $firstTask->id, 'event_date' => $date]);
        $response = $this->actingAs($user)->get('/');
        $response->assertSee(str_limit($firstEvent->note, 30), false);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_view_new_the_event_form()
    {
        // Given a User...
        $user = factory('App\User')->create();
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
        $user = factory('App\User')->create();
        // ... a Date
        $date = Carbon::now();
    }

    // TODO More Tests:
    // Test POST
    // Make sure validation works.
    // Start/End versus duration.
    // Shared tasks/projects?
}
