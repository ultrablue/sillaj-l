<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_project_has_an_owner() {
        // So I guess the idea here is to check that the references are working? In that case, it's ok to create a new User just for this.
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        $this->assertInstanceOf('App\User', $project->owner);
        $this->assertInstanceOf('App\Project', $user->projects->first());
    }

}
