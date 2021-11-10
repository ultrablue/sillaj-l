<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_has_an_owner()
    {
        // Create a User.
        $user = User::factory()->create();
        // Create a 
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf('App\User', $task->owner);
    }
}
