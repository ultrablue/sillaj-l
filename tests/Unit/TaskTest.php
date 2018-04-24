<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_task_has_an_owner() {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create(['user_id' => $user->id]);

        $this->assertInstanceOf('App\User', $task->owner);
    }
}
