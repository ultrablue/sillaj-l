<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\CarbonImmutable;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Project;
use App\Task;
use App\Event;


class ReportsTest extends TestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;


    protected $user;
    protected $projectA, $projectB;
    protected $taskA, $taskB;
    protected $events;

    public function setUp(): void
    {
        parent::setUp();

        // Make a User.
        $this->user = User::create(["name" => "Report Tester1", "email" => "reportester1@test.com", "password" => "password"]);

        // Create two Projects
        // Project A
        // Project B
        $this->projectA = Project::factory()->create(['user_id' => $this->user->id, "name" => "Test Project A", "description" => "Test Project A", "display" => true, "share" => false, "use_in_reports" => true]);
        $this->projectB = Project::factory()->create(['user_id' => $this->user->id, "name" => "Test Project B", "description" => "Test Project B", "display" => true, "share" => false, "use_in_reports" => true]);

        // Create two Tasks.
        // Task A
        // Task B
        $this->taskA = Task::factory()->create(['user_id' => $this->user->id, "name" => "Test Task A", "description" => "Test Task A", "display" => true, "share" => false, "use_in_reports" => true]);
        $this->taskB = Task::factory()->create(['user_id' => $this->user->id, "name" => "Test Task B", "description" => "Test Task B", "display" => true, "share" => false, "use_in_reports" => true]);


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
        // The Report will be generated for this week.
        // 2000-03-01 Project A, Task A Start: 0800 End: 0930 1.5.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-03-01'), 'time_start' => new CarbonImmutable('2000-03-01 08:00'), 'time_end' => new CarbonImmutable('2000-03-01 10:30'), 'note' => 'Test Event I']);
        // 2000-03-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-03-02'), 'time_start' => new CarbonImmutable('2000-03-02 08:00'), 'time_end' => new CarbonImmutable('2000-03-02 09:00'), 'note' => 'Test Event J']);
        // 2000-03-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-03-03'), 'time_start' => new CarbonImmutable('2000-03-03 09:00'), 'time_end' => new CarbonImmutable('2000-03-03 10:00'), 'note' => 'Test Event K']);
        // 2000-03-04 Project B, Task B Start: 0900 End: 1045 1.75.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-03-04'), 'time_start' => new CarbonImmutable('2000-03-04 09:00'), 'time_end' => new CarbonImmutable('2000-03-04 10:45'), 'note' => 'Test Event L']);
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
    public function test_total_project_time_between_two_dates_returns_correct_data(): void
    {

        // Evoke Event::totalProjectTimeBetweenTwoDates($userId, $start, $end)
        // The start and end times are important. Don't change them unless you also change the data.
        $results = Event::totalProjectTimeBetweenTwoDates($this->user->id, new CarbonImmutable('2000-03-01'), new CarbonImmutable('2000-03-30'));

        // $results should have 2 objects, a total duration for each Project (Project A and Project B).
        $this->assertEquals(2, $results->count());

        // And the totals hould be:
        //    Project A - 7.50 hours (27000 seconds)
        //    Project B - 7.25 hours (26100 seconds)
        $this->assertEquals(27000, $results[0]->total_duration);
        $this->assertEquals(26100, $results[1]->total_duration);
    }
}
