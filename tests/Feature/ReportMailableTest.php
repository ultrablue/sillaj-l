<?php

namespace Tests\Feature;

use App\Mail\Report;
use App\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportMailableTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_report_mail()
    {
        // TODO This should probably make a User, and give it some Events.
        // Given an authenticated User;
        $user = factory('App\User')->create();
        // A date;
        $date = CarbonImmutable::now();
        // A dummy Collection;
        $eventsCollection = collect();
        // A dummy groupBy Array;
        $groupDisplayArray = [];
        // And a header (which is what we're actually looking for in the assertion)
        $reportHeader = 'This is a test';

        // Createa a new Report Mailable.
        $mail = new Report($eventsCollection, $groupDisplayArray, 0, $date, $reportHeader);

        // It should contain the header text
        $mail->assertSeeInHtml('This is a test');
    }
}
