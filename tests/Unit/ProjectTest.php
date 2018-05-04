<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    protected $guarded = [];
    protected $user;
    protected $project;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->project = factory('App\Project')
            ->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function a_project_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->project->owner);
        $this->assertInstanceOf('App\Project', $this->user->projects->first());
    }

    /* TODO !! Add a project_has_tasks, please!! */

    /** @test */
    public function a_project_can_add_a_task()
    {
       $this->project->addTask([
            'user_id'        => $this->user->id,
            'name'           => 'A test Task.',
            'description'    => 'Test Task\'s description.',
            'display'        => TRUE,
            'use_in_reports' => TRUE,
            'share'          => TRUE,
        ]);

      $this->assertCount(1, $this->project->tasks);
    }
}
