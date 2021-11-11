<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Task;
use Illuminate\Support\Facades\App;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_has_an_owner()
    {
        // dd(App::environment());
        // dump(config('app.env'));
        // dump(env('APP_ENV'));
        // dd(env('DB_CONNECTION'));
        // Create a User.
        $user = User::factory()->create();
        // Create a Task for the User.
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Assert that the relation is returning the proper type.
        $this->assertInstanceOf('App\User', $task->owner);
    }
}
