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
use Carbon;
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

    // These are used for Report testing.
    protected $events = array();
    protected Project $projectA;
    protected Project $projectB;
    protected Task $taskOne;
    protected Task $taskTwo;

    public function setUp(): void
    {
        parent::setup();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
        $this->task = Task::factory()->create(['user_id' => $this->user->id]);
        $this->event = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id]);


        // These are used for Report testing.
        // Building the data is the most important part here.
        // We'll need a couple of Projects, and a couple of Tasks (for each Project?).
        // A User of course.
        // Events with normal start and end times (start and end quarter hours, please.)
        // And a range of dates that they're created within.
        $this->projectA = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Project A']);
        $this->projectB = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Project B']);

        $this->taskOne = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Task 1']);
        $this->taskTwo = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Task 2']);

        // $events = [];

        // 3600 = 1 hours
        // 1.75 * 3600 = 6300
        // 1.5  * 3600 = 5400
        // 1.25 * 3600 = 4500

        // These are Events that were created in the past and need to be accounted for in the all time totals. 
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskOne->id, 'time_start' => '08:00', 'time_end' => '09:00', 'event_date' => '1900-01-01']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskTwo->id, 'time_start' => '09:00', 'time_end' => '10:30', 'event_date' => '1900-01-02']);

        // These are for the actual reports themselves. This is the week that will be used for the week report.
        // I chose the year 1996 for two reasons:
        //    - It's probably well out of the range of dates that other tests might generate.
        //    - It begins on a Monday.
        // Totals:
        // Project A: 2:30; 2.5 * 3600 = 9000
        // Project B: 2:15; 2.25 * 3600 = 8100
        // Task 1: 2:00; 2 * 3600 = 7200
        // Task 2: 2:45; 2.75 * 3600 = 9900
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskOne->id, 'time_start' => '08:00', 'time_end' => '09:00', 'event_date' => '1996-01-01']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskTwo->id, 'time_start' => '10:00', 'time_end' => '11:30', 'event_date' => '1996-01-02']);

        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskOne->id, 'time_start' => '08:00', 'time_end' => '09:00', 'event_date' => '1996-01-03']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskTwo->id, 'time_start' => '10:00', 'time_end' => '11:15', 'event_date' => '1996-01-04']);
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
    public function an_events_start_method_returns_carbon()
    {
        $event = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id]);

        $this->assertInstanceOf(Carbon::class, $event->start());
    }

    /** @test */
    public function an_events_start_method_returns_null()
    {
        $event = Event::factory()->make(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id, 'time_start' => '']);

        $this->assertNull($event->start());
    }

    /** @test */
    public function an_events_end_method_returns_carbon()
    {
        $event = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id]);

        $this->assertInstanceOf(Carbon::class, $event->end());
    }

    /** @test */
    public function an_events_end_method_returns_null()
    {
        $event = Event::factory()->make(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id, 'time_end' => '']);
        $this->assertNull($event->end());
    }


    /** 
     * reportQuery method has a fairly involved query. That's what we're testing.
     * 
     * @test
     *  
     * */
    public function reportQuery_returns_correct_data()
    {

        $this->actingAs($this->user);

        // Weekly Report
        $reportStartDate = new CarbonImmutable('1996-01-01');
        $reportEndDate   = new CarbonImmutable('1996-01-07');
        $results = Event::reportQuery(['project', 'task'], $reportStartDate, $reportEndDate);

        // In this case, there should be 4 Events.
        $this->assertEquals(4, $results->count());
        // These do the per Project times.
        $this->assertEquals(9000, $results->where('project', 'Project A')->sum('duration'), "Project A total isn't correct.");
        $this->assertEquals(8100, $results->where('project', 'Project B')->sum('duration'), "Project B total isn't correct.");

        $this->assertEquals(7200, $results->where('task', 'Task 1')->sum('duration'), "Task 1 total isn't correct.");
        $this->assertEquals(9900, $results->where('task', 'Task 2')->sum('duration'), "Task 1 total isn't correct.");
    }

    /** 
     * reportQuery method has a fairly involved query. That's what we're testing.
     * 
     * @test
     *  
     * */
    public function totalProjectTimeBetweenTwoDates_returns_correct_data()
    {
        $this->actingAs($this->user);

        $reportStartDate = new CarbonImmutable('1996-01-01');
        $reportEndDate   = new CarbonImmutable('1996-01-07');
        $results = Event::totalProjectTimeBetweenTwoDates($reportStartDate, $reportEndDate);

        // dd($results->where('name', 'Project A')->pluck('total_duration')->first());

        // Prokect A - 12600
        // Project B - 13500
        $this->assertEquals(12600, $results->where('name', 'Project A')->pluck('total_duration')->first(), "Project A total isn't correct.");
        $this->assertEquals(13500, $results->where('name', 'Project B')->pluck('total_duration')->first(), "Project B total isn't correct.");
    }
}
