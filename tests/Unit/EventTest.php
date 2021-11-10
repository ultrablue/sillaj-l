<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Tests\TestCase;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent:: setup();
        $this->user = factory('App\User')->create();
        $this->project = factory('App\Project')->create(['user_id' => $this->user->id]);
        $this->task = factory('App\Task')->create(['user_id' => $this->user->id]);
        $this->event = factory('App\Event')->create(['user_id' => $this->user->id, 'project_id' => $this->project->id, 'task_id' => $this->task->id]);
        //Event::factory()->make(['user_id' => 1, 'project_id'=>1, 'task_id' => 3])
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
}
