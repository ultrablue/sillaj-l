<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        // TODO Do I need this test?
        $this->markTestSkipped('TODO - Do I need this test?? 🤷‍');
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
