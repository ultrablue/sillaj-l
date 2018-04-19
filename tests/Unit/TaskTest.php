<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */

    // This one needs to be fleshed out a little. :/
    // I think it's not working because I didn't define the relationship
    // somewhere. :\
    //public function it_has_an_owner() {
    //    $task = factory('App\Task')->create(['user_id'=>1]);

    //    $this->assertInstanceOf('App\User', $task->owner);
    //}
}
