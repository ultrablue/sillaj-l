<?php
// TODO What happens if we try to save a different Project with the same ID?

namespace Tests\Unit;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Project;

class ProjectTest extends TestCase
{
    // use DatabaseMigrations;
    use RefreshDatabase;

    protected $guarded = [];
    protected $user;
    protected $project;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()
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
    /* TODO Do I even need these?? It seems like I'm testing the many to many features of Laravel itself, no? */

    /** @test */
    public function a_project_can_add_a_task()
    {
        $this->markTestSkipped('Do I really need this test?');
        // Tests the many to many relationship between Projects and Tasks.
        // TODO Use Faker for this?? Or the Factory???
        $this->project->addTask([
            'user_id' => $this->user->id,
            'name' => 'A test Task.',
            'description' => 'Test Task\'s description.',
            'display' => TRUE,
            'use_in_reports' => TRUE,
            'share' => TRUE,
        ]);

        $this->assertCount(1, $this->project->tasks);
    }

    /** @test */
    public function a_project_can_list_all_shared_projects()
    {
        // Huh? This seems really specific and brittle??
        $this->markTestSkipped('Huh?? This seems really specific and brittle.');
        // The idea here is to see whether the shared Projects created by any User are returned by the Model.
        $users = User::factory()->count(2)->create();
        foreach ($users as $user) {
            Project::factory()->count(2)->create(['user_id' => $user->id, 'share' => TRUE]);
            Project::factory()->count(2)->create(['user_id' => $user->id, 'share' => FALSE]);
        }
        $this->assertEquals($this->project->shared()->count(), 4);
        $this->assertEquals($this->project->otherShared($users[0])->count(), 2);
    }
}
