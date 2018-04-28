<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageProjectTasks extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_add_new_tasks_to_a_project()
    {

        //Given we have an authenticatd User
        $user = factory('App\User')->create();
        $this->be($user);
        //And an existing Project
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        //When the User adds a new Task to the Project,
        $task = factory('App\Task')->make(['user_id' => $user->id]);
        $this->post('projects/' . $project->id . '/tasks', $task->toArray());
        //Then the Task should appear on the page.
        //$this->get($project->path())->assertSee($task->name);
    }
}
