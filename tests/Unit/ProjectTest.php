<?php
// TODO What happens if we try to save a different Project with the same ID?

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
        // TODO Wouldn't this be better if it created a NEW Project?
        $this->assertInstanceOf('App\User', $this->project->owner);
        $this->assertInstanceOf('App\Project', $this->user->projects->first());
    }

    /* TODO !! Add a project_has_tasks, please!! IOW, multiple tasks. Or refactor the one below to also add another Task and test for that? */
    /* TODO Do I even need these?? It seems like I'm testing the many to many features of Laravel itself, no?

    /** @test */
    public function a_project_can_add_a_task()
    {
        // Tests the many to many relationship between Projects and Tasks.
        // TODO Use Faker for this?? Or the Factory???
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

    /** @test */
    public function a_project_can_list_all_shared_projects() {
        // The idea here is to see whether the shared Projects created by any User are returned by the Model.
        $users = factory('App\User', 2)->create();
        foreach ($users as $user) {
            factory('App\Project', 2)->create(['user_id' => $user->id, 'share' => TRUE]);
            factory('App\Project', 2)->create(['user_id' => $user->id, 'share' => FALSE]);
        }
        $this->assertEquals($this->project->shared()->count(), 4);
        $this->assertEquals($this->project->otherShared($users[0])->count(), 2);
    } 

}
