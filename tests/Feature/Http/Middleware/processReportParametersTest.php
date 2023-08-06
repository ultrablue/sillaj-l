<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class processReportParametersTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $this->markTestSkipped('This test probably isn\'t needed. Please delete if so.');


        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
