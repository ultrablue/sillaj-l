<?php

namespace Tests\Unit;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Make sure that the table exists.
     *  @test 
     * 
     */
    public function tasks_table_exists()
    {
        $this->assertTrue(
            Schema::hasTable('tasks')
        );
    }

    /** 
     * Check the schema to make sure all of the columns are there.
     * NOTE: This will pass if there are columns in the database that 
     * aren't in the hasColumns array. If you forget to to update this 
     * test after adding a new column, it'll still pass.
     * 
     * @test */
    public function tasks_table_schema_check()
    {
        $this->assertTrue(
            Schema::hasColumns('tasks', [
                'id', 'user_id', 'name', 'description', 'display', 'use_in_reports', 'share', 'created_at', 'updated_at', 'deleted_at',
            ])
        );
    }

    /** 
     * Make sure that the Task's owner is a User.
     * 
     * @test */
    public function a_task_belongs_to_a_user()
    {
        // Create a User.
        $user = User::factory()->create();

        // Create a Task for the User.
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Assert that the relation is returning the proper type.
        $this->assertInstanceOf('App\User', $task->owner);
    }

    /**
     * 
     * The many-to-many relationship should return a Collection.
     * 
     * @test
     */
    public function a_task_belongs_to_many_projects()
    {
        // Creaate a User.
        $user = User::factory()->create();

        // Create a Task.
        $task = Task::factory()->create(['user_id' => $user->id]);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $task->projects()->sync($project);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $task->projects);
    }

    /**
     * 
     * Tests the allAvailable Scope.
     * It's not really finished because I think that the scope should belong to the User Model.
     * 
     * @test
     */
    public function scope_all_available(){

        // We need a User...
        $user = User::factory()->create();

        // ... and a non-Shared Task...
        $privateTask = Task::factory()->create(['user_id' => $user->id, 'share' => FALSE]);
        
        // ... and a Shared Task.
        $sharedTask = Task::factory()->create(['user_id' => $user->id, 'share' => TRUE]);

        // The scope call should return a Collection...
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $task->allAvailable);

        // ... with 2 items.
        
        // TODO We should probably have an inverse test as well (a User doesn't get another User's private Tasks)
    }

}
