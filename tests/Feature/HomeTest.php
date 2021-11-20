<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


use App\User;

class HomeTest extends TestCase
{


    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * An authenticated User can get the root Route.
     *
     * @return void
     */
    public function test_an_authenticated_user_gets_the_root_route()
    {

        $user = User::factory()->make();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }


    /**
     * An authenticated User can get the home Route.
     *
     * @return void
     */
    public function test_an_authenticated_user_gets_the_home_route()
    {

        $user = User::factory()->make();

        $response = $this->actingAs($user)->get('home');

        $response->assertStatus(200);
    }


    /**
     * An authenticated User can get the root Route with a date.
     *
     * @return void
     */
    public function test_an_authenticated_user_can_get_the_root_route_with_a_date() {
        //http://sillaj-l.local/2021-12-02

        $user = User::factory()->make();

        $response = $this->actingAs($user)->get('/2021-01-01');

        $response->assertStatus(200);

    }


}
