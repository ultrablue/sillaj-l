<?php

namespace Tests\Feature;

use App\Event;
use App\Project;
use App\Task;
use App\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected $user;

    protected $projectA;
    protected $projectB;
    protected $projectC;

    protected $taskA;
    protected $taskB;
    protected $taskC;


    protected $events;

    public function setUp(): void
    {
        parent::setUp();

        // Make a User.
        $this->user = User::create(['name' => 'Report Tester1', 'email' => 'reportester1@test.com', 'password' => 'password']);

        // Create some Projects
        $this->projectA = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project A', 'description' => 'Test Project A', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->projectB = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project B', 'description' => 'Test Project B', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->projectC = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project C', 'description' => 'Test Project C', 'display' => true, 'share' => false, 'use_in_reports' => true]);

        // Create some Tasks.
        $this->taskA = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task A', 'description' => 'Test Task A', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->taskB = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task B', 'description' => 'Test Task B', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->taskC = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task C', 'description' => 'Test Task C', 'display' => true, 'share' => false, 'use_in_reports' => true]);

        // TODO do I need to store these in variables or can I just evoke the Factory?
        $this->events = [];
        // Create several Events that span several months, for each Project and Task. Something like:
        // 2000-01-01 Project A, Task A Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-01-01'), 'time_start' => new CarbonImmutable('2000-01-01 08:00'), 'time_end' => new CarbonImmutable('2000-01-01 09:00'), 'note' => 'Test Event A']);
        // 2000-01-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-01-02'), 'time_start' => new CarbonImmutable('2000-01-02 08:00'), 'time_end' => new CarbonImmutable('2000-01-02 09:00'), 'note' => 'Test Event B']);
        // 2000-01-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-01-03'), 'time_start' => new CarbonImmutable('2000-01-03 09:00'), 'time_end' => new CarbonImmutable('2000-01-03 10:00'), 'note' => 'Test Event C']);
        // 2000-01-04 Project B, Task B Start: 1000 End: 1100 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-01-04'), 'time_start' => new CarbonImmutable('2000-01-04 10:00'), 'time_end' => new CarbonImmutable('2000-01-04 11:00'), 'note' => 'Test Event D']);
        //
        // 2000-02-01 Project A, Task A Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-02-01'), 'time_start' => new CarbonImmutable('2000-02-01 08:00'), 'time_end' => new CarbonImmutable('2000-02-01 09:00'), 'note' => 'Test Event E']);
        // 2000-02-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-02-02'), 'time_start' => new CarbonImmutable('2000-02-02 08:00'), 'time_end' => new CarbonImmutable('2000-02-02 09:00'), 'note' => 'Test Event F']);
        // 2000-02-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-02-03'), 'time_start' => new CarbonImmutable('2000-02-03 09:00'), 'time_end' => new CarbonImmutable('2000-02-03 10:00'), 'note' => 'Test Event G']);
        // 2000-03-04 Project B, Task B Start: 0900 End: 1030 1.5.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-02-04'), 'time_start' => new CarbonImmutable('2000-02-04 09:00'), 'time_end' => new CarbonImmutable('2000-02-04 10:30'), 'note' => 'Test Event H']);
        //

        // We need a few Events that don't have durations. 
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-01'), 'time_start' => null, 'time_end' => new CarbonImmutable('2000-03-01 10:30'), 'note' => 'Test Event No End Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-02'), 'time_start' => new CarbonImmutable('2000-03-02 10:30'), 'time_end' => null, 'note' => 'Test Event No Start Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-03'), 'time_start' => null, 'time_end' => null, 'note' => 'Test Event No Start And End Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-04'), 'time_start' => null, 'time_end' => null, 'note' => 'Test Event No End Time']);


        // The Report will be generated for this week.
        // 2000-12-01 Project A, Task A Start: 0800 End: 0930 1.5.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-12-01'), 'time_start' => new CarbonImmutable('2000-12-01 08:00'), 'time_end' => new CarbonImmutable('2000-12-01 10:30'), 'note' => 'Test Event I']);
        // 2000-12-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-12-02'), 'time_start' => new CarbonImmutable('2000-12-02 08:00'), 'time_end' => new CarbonImmutable('2000-12-02 09:00'), 'note' => 'Test Event J']);
        // 2000-12-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-12-03'), 'time_start' => new CarbonImmutable('2000-12-03 09:00'), 'time_end' => new CarbonImmutable('2000-12-03 10:00'), 'note' => 'Test Event K']);
        // 2000-12-04 Project B, Task B Start: 0900 End: 1045 1.75.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-12-04'), 'time_start' => new CarbonImmutable('2000-12-04 09:00'), 'time_end' => new CarbonImmutable('2000-12-04 10:45'), 'note' => 'Test Event L']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-12-05'), 'time_start' => new CarbonImmutable('2000-12-05 09:00'), 'time_end' => new CarbonImmutable('2000-12-05 09:00'), 'note' => 'Test Event M']);
    }

    /**
     * A basic feature test example.
     */
    public function foo_test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** This tests the query that gets the total time for a Project. I guess that's a Feature? */
    // TODO Yikes, there are several variations that also need to be tested.
    //    It's possible that the dates in question have Projects that have Events with no durations.
    public function test_total_project_time_between_two_dates_returns_correct_data(): void
    {
        $this->actingAs($this->user);

        // Evoke Event::totalProjectTimeBetweenTwoDates($userId, $start, $end)
        // The start and end times are important. Don't change them unless you also change the data.
        $results = Event::totalProjectTimeBetweenTwoDates(new CarbonImmutable('2000-12-01'), new CarbonImmutable('2000-12-05'));

        // dd($results);

        // $results should have 3 objects, a total duration for each Project (Project A, Project B, Project C).
        $this->assertEquals(3, $results->count(), 'There should be 3 Projects.');

        // And the totals hould be:
        //    Project A - 7.50 hours (27000 seconds)
        //    Project B - 7.25 hours (26100 seconds)
        $this->assertEquals(27000, $results[0]->total_duration);
        $this->assertEquals(26100, $results[1]->total_duration);
    }

    public function test_some_reports_show_the_total_time_for_fetched_projects(): void
    {
        $this->markTestSkipped('We can\'t test MySQL ROLL UP in sqlite. 😿');
        $this->actingAs($this->user);

        $response = $this->post('/reports', []);

        $response->assertStatus(200);
    }
}
