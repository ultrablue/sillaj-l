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
}
