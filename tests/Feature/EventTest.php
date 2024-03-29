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

    // GET /events should return a 405.
    // Create (POST) an Event as an unauthenticated User should fail.

    // GET events/{event} should return a 200.
    //                           the proper Event should be displayed.
    // PUT events/{event} should return a 200.
    //                           the updated Event should be displayed.


    /**
     * This one combines a number of Assertions into one Test. 
     * They're all related to creating Events, so I guess it's ok to 
     * have them all in one.
     */
    public function test_create_events()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $startDate = Carbon::create(2021, 1, 1, 10, 0, 0);
        $endDate = Carbon::create(2021, 1, 1, 11, 0, 0);

        // Given a User, a date, a time start and end, a Project and Task, make an Event.
        $response = $this->actingAs($user)->post('/events', [
            'event_date' => $startDate->toDateString(),
            'time_start' => $startDate->format('H:i'),
            'time_end' => $endDate->format('H:i'),
            'project_id' => $project->id,
            'duration' => null,
            'task_id' => $task->id
        ]);
        // The Action redirects back to the main page.
        $response->assertStatus(302);


        // Given a User, a date, a time start, a Project and Task, make an Event.
        $response = $this->actingAs($user)->post('/events', [
            'event_date' => $startDate->toDateString(),
            'time_start' => $startDate->format('H:i'),
            // 'time_end' => $endDate->format('H:i'),
            'duration' => null,
            'project_id' => $project->id,
            'task_id' => $task->id
        ]);
        // We should be redirected.
        $response->assertStatus(302);

        // Given a User, a date, a time start, a duration, a Project and Task, make an Event.
        $response = $this->actingAs($user)->post('/events', [
            'event_date' => $startDate->toDateString(),
            'time_start' => $startDate->format('H:i'),
            // 'time_end' => $endDate->format('H:i'),
            'duration' => '1:00',
            'project_id' => $project->id,
            'task_id' => $task->id
        ]);
        // If the insert was successful, we should be redirected.
        $response->assertStatus(302);


        // Given a User, a date, a time end, a duration, a Project and Task, make an Event.
        $response = $this->actingAs($user)->post('/events', [
            'event_date' => $startDate->toDateString(),
            // 'time_start' => $startDate->format('H:i'),
            'time_end' => $endDate->format('H:i'),
            'duration' => '1:00',
            'project_id' => $project->id,
            'task_id' => $task->id
        ]);
        $response->assertStatus(302);

        // Given a User, a date, a duration, a Project and Task, make an Event.
        $response = $this->actingAs($user)->post('/events', [
            'event_date' => $startDate->toDateString(),
            // 'time_start' => $startDate->format('H:i'),
            // 'time_end' => $endDate->format('H:i'),
            'duration' => '1:00',
            'project_id' => $project->id,
            'task_id' => $task->id
        ]);
        $response->assertStatus(302);



        // // Attempts to test the validator, but it's not working.
        // $this->expectException('ErrorException');
        // $response = $this->actingAs($user)->post('/events', [
        //     'event_date' => $startDate->toDateString(),
        //     'time_start' => '1PM',
        //     'time_end' => '2pm',
        //     'project_id' => $project->id,
        //     'duration' => null,
        //     'task_id' => $task->id,
        //     'note' => 'Testing time format.'
        // ]);
        // // The Action redirects back to the main page.
        // $response->assertStatus(302);
    }



    public function test_update_an_existing_event()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $startDate = Carbon::create(2021, 1, 1, 10, 0, 0);
        $endDate = Carbon::create(2021, 1, 1, 11, 0, 0);

        $event = Event::factory()
            ->create([
                'event_date' => $startDate,
                'event_date' => $endDate,
                'time_start' => $startDate->format('H:i'),
                'time_end'   => $endDate->format('H:i'),
                'project_id' => $project->id,
                'task_id'    => $task->id,
                'note'       => 'create for test'
            ]);

        // Given a User, a date, a time start and end, a Project and Task, make an Event.
        $response = $this->actingAs($user)->put('/events/'.$event->id, [
            'event_date' => $startDate->toDateString(),
            'time_start' => $startDate->format('H:i'),
            'time_end' => $endDate->format('H:i'),
            'project_id' => $project->id,
            'duration' => null,
            'task_id' => $task->id,
            'note' => 'updated for test'
        ]);
        // The Action redirects back to the main page.
        $response->assertStatus(302);
    }


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
        $response = $this->actingAs($user)->get('/events/' . $event->id);
        $response->assertStatus(200);
        $response->assertSee($event->note, false);
    }
}
