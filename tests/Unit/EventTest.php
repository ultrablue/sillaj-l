<?php

// This line was added by Greg.

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\User;
use App\Project;
use App\Task;
use App\Event;
use Carbon\CarbonImmutable;

use function PHPUnit\Framework\assertEquals;

class EventTest extends TestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;
    protected $user;
    protected $project;
    protected $task;
    protected $event;

    public function setUp(): void
    {
        parent::setup();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);
        $this->event = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id]);
    }

    /** @test */
    public function an_event_has_an_owner()
    {
        // Testing the Model's owner() method.
        $this->assertInstanceOf('App\User', $this->event->owner);
    }

    /** @test */
    public function an_event_has_a_task()
    {
        // Testing the Model's task() method.

        $this->assertInstanceOf('App\Task', $this->event->task);
    }

    /** @test */
    public function an_event_has_a_project()
    {
        // Testing the Model's project() method.

        $this->assertInstanceOf('App\Project', $this->event->project);
    }

    /** @test */
    public function total_project_time_between_two_dates_returns_correct_data(): void
    {
        // TODO Should this be in Feature Tests? Because it requires an authenticated User?
        // TODO Or in a dedicated set of Report Tests?


        // Make a User.
        $user = User::create(["name" => "Report Tester1", "email" => "reportester1@test.com", "password" => "password"]);

        // Create two Projects
        // Project A
        // Project B
        $this->actingAs($user);
        $projectA = Project::factory()->create(['user_id' => $user->id, "name" => "Test Project A", "description" => "Test Project A", "display" => true, "share" => false, "use_in_reports" => true]);
        $projectB = Project::factory()->create(['user_id' => $user->id, "name" => "Test Project B", "description" => "Test Project B", "display" => true, "share" => false, "use_in_reports" => true]);

        // Create two Tasks.
        // Task A
        // Task B
        $taskA = Task::factory()->create(['user_id' => $user->id, "name" => "Test Task A", "description" => "Test Task A", "display" => true, "share" => false, "use_in_reports" => true]);
        $taskB = Task::factory()->create(['user_id' => $user->id, "name" => "Test Task B", "description" => "Test Task B", "display" => true, "share" => false, "use_in_reports" => true]);

        // TODO do I need to store these in variables or can I just evoke the Factory?
        $events = [];
        // Create several Events that span several months, for each Project and Task. Something like:
        // 2000-01-01 Project A, Task A Start: 0800 End: 0900 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-01-01'), 'time_start' => new CarbonImmutable('2000-01-01 08:00'), 'time_end' => new CarbonImmutable('2000-01-01 09:00'), 'note' => 'Test Event A']);
        // 2000-01-02 Project A, Task B Start: 0800 End: 0900 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-01-02'), 'time_start' => new CarbonImmutable('2000-01-02 08:00'), 'time_end' => new CarbonImmutable('2000-01-02 09:00'), 'note' => 'Test Event B']);
        // 2000-01-03 Project B, Task A Start: 0900 End: 1000 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-01-03'), 'time_start' => new CarbonImmutable('2000-01-03 09:00'), 'time_end' => new CarbonImmutable('2000-01-03 10:00'), 'note' => 'Test Event C']);
        // 2000-01-04 Project B, Task B Start: 1000 End: 1100 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-01-04'), 'time_start' => new CarbonImmutable('2000-01-04 10:00'), 'time_end' => new CarbonImmutable('2000-01-04 11:00'), 'note' => 'Test Event D']);
        //
        // 2000-02-01 Project A, Task A Start: 0800 End: 0900 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-02-01'), 'time_start' => new CarbonImmutable('2000-02-01 08:00'), 'time_end' => new CarbonImmutable('2000-02-01 09:00'), 'note' => 'Test Event E']);
        // 2000-02-02 Project A, Task B Start: 0800 End: 0900 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-02-02'), 'time_start' => new CarbonImmutable('2000-02-02 08:00'), 'time_end' => new CarbonImmutable('2000-02-02 09:00'), 'note' => 'Test Event F']);
        // 2000-02-03 Project B, Task A Start: 0900 End: 1000 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-02-03'), 'time_start' => new CarbonImmutable('2000-02-03 09:00'), 'time_end' => new CarbonImmutable('2000-02-03 10:00'), 'note' => 'Test Event G']);
        // 2000-03-04 Project B, Task B Start: 0900 End: 1030 1.5.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-02-04'), 'time_start' => new CarbonImmutable('2000-02-04 09:00'), 'time_end' => new CarbonImmutable('2000-02-04 10:30'), 'note' => 'Test Event H']);
        //
        // The Report will be generated for this week.
        // 2000-03-01 Project A, Task A Start: 0800 End: 0930 1.5.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-03-01'), 'time_start' => new CarbonImmutable('2000-03-01 08:00'), 'time_end' => new CarbonImmutable('2000-03-01 10:30'), 'note' => 'Test Event I']);
        // 2000-03-02 Project A, Task B Start: 0800 End: 0900 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectA->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-03-02'), 'time_start' => new CarbonImmutable('2000-03-02 08:00'), 'time_end' => new CarbonImmutable('2000-03-02 09:00'), 'note' => 'Test Event J']);
        // 2000-03-03 Project B, Task A Start: 0900 End: 1000 1.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskA->id, 'event_date' => new CarbonImmutable('2000-03-03'), 'time_start' => new CarbonImmutable('2000-03-03 09:00'), 'time_end' => new CarbonImmutable('2000-03-03 10:00'), 'note' => 'Test Event K']);
        // 2000-03-04 Project B, Task B Start: 0900 End: 1045 1.75.
        $events[] = Event::factory()->create(['user_id' => $user->id, 'project_id' => $projectB->id, 'task_id' => $taskB->id, 'event_date' => new CarbonImmutable('2000-03-04'), 'time_start' => new CarbonImmutable('2000-03-04 09:00'), 'time_end' => new CarbonImmutable('2000-03-04 10:45'), 'note' => 'Test Event L']);

        // Evoke Event::totalProjectTimeBetweenTwoDates($start, $end)
        // $start = CarbonImmutable('2000-03-01', '2000-03-05')
        $results = Event::totalProjectTimeBetweenTwoDates($user->id, new CarbonImmutable('2000-03-01'), new CarbonImmutable('2000-03-30'));

        // dd($results[0]->total_duration);

        // $results should have 2 things, a total for each Project (Project A and Project B).
        $this->assertEquals(2, $results->count());
        // It should return:
        //    Project A - 7.50 hours (27000 seconds)
        //    Project B - 7.25 hours (26100 seconds)
        assertEquals(27000, $results[0]->total_duration);
        assertEquals(26100, $results[1]->total_duration);


        // dd($results);
    }
}
