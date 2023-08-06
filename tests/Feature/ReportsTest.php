<?php

namespace Tests\Feature;

use App\Event;
use App\Project;
use App\Task;
use App\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class ReportsTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    use WithoutMiddleware;


    protected $user;

    protected $projectA;
    protected $projectB;
    protected $projectC;

    protected $taskA;
    protected $taskB;
    protected $taskC;


    protected $events;



    public function setUp(): void
    {
        parent::setUp();

        // Make a User.
        $this->user = User::create(['name' => 'Report Tester1', 'email' => 'reportester1@test.com', 'password' => 'password']);

        // Create some Projects
        $this->projectA = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project A', 'description' => 'Test Project A', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->projectB = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project B', 'description' => 'Test Project B', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->projectC = Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Project C', 'description' => 'Test Project C', 'display' => true, 'share' => false, 'use_in_reports' => true]);

        // Create some Tasks.
        $this->taskA = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task A', 'description' => 'Test Task A', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->taskB = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task B', 'description' => 'Test Task B', 'display' => true, 'share' => false, 'use_in_reports' => true]);
        $this->taskC = Task::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Task C', 'description' => 'Test Task C', 'display' => true, 'share' => false, 'use_in_reports' => true]);

        // TODO do I need to store these in variables or can I just evoke the Factory?
        $this->events = [];
        // Create several Events that span several months, for each Project and Task. Something like:
        // 2000-01-01 Project A, Task A Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-01-01'), 'time_start' => new CarbonImmutable('2000-01-01 08:00'), 'time_end' => new CarbonImmutable('2000-01-01 09:00'), 'note' => 'Test Event A']);
        // 2000-01-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-01-02'), 'time_start' => new CarbonImmutable('2000-01-02 08:00'), 'time_end' => new CarbonImmutable('2000-01-02 09:00'), 'note' => 'Test Event B']);
        // 2000-01-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-01-03'), 'time_start' => new CarbonImmutable('2000-01-03 09:00'), 'time_end' => new CarbonImmutable('2000-01-03 10:00'), 'note' => 'Test Event C']);
        // 2000-01-04 Project B, Task B Start: 1000 End: 1100 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-01-04'), 'time_start' => new CarbonImmutable('2000-01-04 10:00'), 'time_end' => new CarbonImmutable('2000-01-04 11:00'), 'note' => 'Test Event D']);
        //
        // 2000-02-01 Project A, Task A Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-02-01'), 'time_start' => new CarbonImmutable('2000-02-01 08:00'), 'time_end' => new CarbonImmutable('2000-02-01 09:00'), 'note' => 'Test Event E']);
        // 2000-02-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-02-02'), 'time_start' => new CarbonImmutable('2000-02-02 08:00'), 'time_end' => new CarbonImmutable('2000-02-02 09:00'), 'note' => 'Test Event F']);
        // 2000-02-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-02-03'), 'time_start' => new CarbonImmutable('2000-02-03 09:00'), 'time_end' => new CarbonImmutable('2000-02-03 10:00'), 'note' => 'Test Event G']);
        // 2000-03-04 Project B, Task B Start: 0900 End: 1030 1.5.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-02-04'), 'time_start' => new CarbonImmutable('2000-02-04 09:00'), 'time_end' => new CarbonImmutable('2000-02-04 10:30'), 'note' => 'Test Event H']);
        //

        // We need a few Events that don't have durations. 
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-01'), 'time_start' => null, 'time_end' => new CarbonImmutable('2000-03-01 10:30'), 'note' => 'Test Event No End Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-02'), 'time_start' => new CarbonImmutable('2000-03-02 10:30'), 'time_end' => null, 'note' => 'Test Event No Start Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-03'), 'time_start' => null, 'time_end' => null, 'note' => 'Test Event No Start And End Time']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-03-04'), 'time_start' => null, 'time_end' => null, 'note' => 'Test Event No End Time']);


        // The Report will be generated for this week.
        // 2000-12-01 Project A, Task A Start: 0800 End: 0930 1.5.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-12-01'), 'time_start' => new CarbonImmutable('2000-12-01 08:00'), 'time_end' => new CarbonImmutable('2000-12-01 10:30'), 'note' => 'Test Event I']);
        // 2000-12-02 Project A, Task B Start: 0800 End: 0900 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectA->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-12-02'), 'time_start' => new CarbonImmutable('2000-12-02 08:00'), 'time_end' => new CarbonImmutable('2000-12-02 09:00'), 'note' => 'Test Event J']);
        // 2000-12-03 Project B, Task A Start: 0900 End: 1000 1.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskA->id, 'event_date' => new CarbonImmutable('2000-12-03'), 'time_start' => new CarbonImmutable('2000-12-03 09:00'), 'time_end' => new CarbonImmutable('2000-12-03 10:00'), 'note' => 'Test Event K']);
        // 2000-12-04 Project B, Task B Start: 0900 End: 1045 1.75.
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectB->id, 'task_id' => $this->taskB->id, 'event_date' => new CarbonImmutable('2000-12-04'), 'time_start' => new CarbonImmutable('2000-12-04 09:00'), 'time_end' => new CarbonImmutable('2000-12-04 10:45'), 'note' => 'Test Event L']);
        $this->events[] = Event::factory()->create(['user_id' => $this->user->id, 'project_id' => $this->projectC->id, 'task_id' => $this->taskC->id, 'event_date' => new CarbonImmutable('2000-12-05'), 'time_start' => new CarbonImmutable('2000-12-05 09:00'), 'time_end' => new CarbonImmutable('2000-12-05 09:00'), 'note' => 'Test Event M']);
    }


    /** This tests the query that gets the total time for a Project. I guess that's a Feature? */
    // TODO Yikes, there are several variations that also need to be tested. (NB: such as??)
    //    It's possible that the dates in question have Projects that have Events with no durations.
    public function test_total_project_time_between_two_dates_returns_correct_data(): void
    {
        $this->actingAs($this->user);

        // Evoke Event::totalProjectTimeBetweenTwoDates($userId, $start, $end)
        // The start and end times are important. Don't change them unless you also change the data.
        $results = Event::totalProjectTimeBetweenTwoDates(new CarbonImmutable('2000-12-01'), new CarbonImmutable('2000-12-05'));

        // dd($results);

        // $results should have 3 objects, a total duration for each Project (Project A, Project B, Project C).
        $this->assertEquals(3, $results->count(), 'There should be 3 Projects.');

        // And the totals hould be:
        //    Project A - 7.50 hours (27000 seconds)
        //    Project B - 7.25 hours (26100 seconds)
        //    Project C - 0 hours (0 seconds)
        $this->assertEquals(27000, $results[0]->total_duration);
        $this->assertEquals(26100, $results[1]->total_duration);
        $this->assertEquals(0, $results[2]->total_duration);
    }

    public function test_total_task_time_between_two_dates_returns_correct_data(): void
    {
        $this->actingAs($this->user);


        // For 2001-12-01 through 2000-12-05 the totals should look like this:
        // Task A - 7.50 hours (27000 seconds)
        // Task B - 7.25 hours (26100 seconds)
        // Task C - 0 hours (0 seconds)

        // Evoke Event::totalProjectTimeBetweenTwoDates($userId, $start, $end)
        // The start and end times are important. Don't change them unless you also change the data.
        $results = Event::totalTaskTimeBetweenTwoDates(new CarbonImmutable('2000-12-01'), new CarbonImmutable('2000-12-05'));

        $this->assertEquals(3, $results->count(), 'There should be 3 Projects.');

        $this->assertEquals(27000, $results[0]->total_duration);
        $this->assertEquals(26100, $results[1]->total_duration);
        $this->assertEquals(0, $results[2]->total_duration);

        // dd($results);

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'I\'m suspicious of the results above.'
        );
    }

    public function test_the_proper_dates_are_selected_for_the_given_range(): void
    {
        $this->markTestSkipped('This is really just a placeholder. Delete, please.');


        /**
         * These are the fields sent by the post:
        "group-by" => "project"
        "task-group-filter" => "1"
        "predefined-range" => "this-week"
        "custom-range" => "custom"
        "start_date" => null
        "end_date" => null
         */
    }

    public function test_some_reports_show_the_total_time_for_fetched_projects(): void
    {
        $this->markTestSkipped('We can\'t test MySQL ROLL UP in sqlite. 😿');
        $this->actingAs($this->user);

        $response = $this->post('/reports', []);

        $response->assertStatus(200);
    }



    /**
     * @dataProvider valuesThatShouldValadate
     *
     * @return void
     */
    public function test_acceptable_start_and_dates(array $requestData): void
    {

        $this->actingAs($this->user);

        $this->post('/reports/test', $requestData)->assertStatus(200);
    }

    public function valuesThatShouldValadate(): array
    {
        return [
            'two good dates and custom-range selected' => [
                [
                    'custom-range' => true,
                    'start_date'   => '2023-01-01',
                    'end_date'     => '2023-01-01',
                ]
            ]
        ];
    }

    /**
     * @dataProvider valuesThatShouldNotValidate
     *
     * @return void
     */
    public function test_dates_that_should_not_valadate(array $requestData): void
    {
        $this->actingAs($this->user);

        $this->post('/reports/test', $requestData)->assertStatus(302);
    }

    public function valuesThatShouldNotValidate(): array
    {

        return [
            'start date is not a proper date' => [
                [
                    'custom-range' => 'true',
                    'start_date'   => 'foo',
                    'end_date'     => '2023-01-01',
                ]
            ],
            'end date is not a proper date' => [
                [
                    'custom-range' => 'true',
                    'start_date'   => '2023-01-01',
                    'end_date'     => 'bar',
                ]
            ],

            'start and end are not proper dates' => [
                [
                    'custom-range' => 'true',
                    'start_date'   => 'foo',
                    'end_date'     => 'bar',
                ]
            ],

            'if one date is present the other must be, too (end date is \'\')' => [
                [
                    'custom-range' => 'true',
                    'start_date'   => '2023-01-01',
                    'end_date'     => '',
                ]
            ],

            'if one date is present the other must be, too (start date is \'\')' => [
                [
                    'custom-range' => 'true',
                    'start_date'   => '',
                    'end_date'     => '2023-01-01',
                ]
            ],
            'if one date is present the other be, too (end date is missing)' =>
            [
                [
                    'custom-range' => 'true',
                    'start_date'   => '2023-01-01',
                ]
            ],
            'if one date is present the other be, too (start date is missing)' =>
            [
                [
                    'custom-range' => 'true',
                    'end_date'     => '2023-01-01',
                ]
            ],
            // TODO This one passes validation for some reason??
            // 'two blank dates and custom-range not present' => [
            //     [
            //         'start_date' => '',
            //         'end_date'   => '',
            //     ]
            // ],

        ];
    }

    /**
     * Inject a date into the request so that we can test that the view contains the proper dates.
     * These ones are really testing whether app/Services/ReportService.php is behaving as expected.
     * That's probably not the best practice ever, but it works.
     * So, if these tests fail for some reason, you'll need to check:
     *    The Controller
     *    The Service Class (app/Services/ReportService.php)
     *    The Blade template itself.
     *
     * @return void
     */
    public function test_range_this_week_includes_proper_dates_for_this_week(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'now' => '2000-01-05',
            'predefined-range' => 'this-week'
        ]);

        $response->assertSeeText("Monday, January 3rd");
        $response->assertSeeText("Sunday, January 9th");
    }

    public function test_range_last_week_includes_proper_dates(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'now' => '2000-02-16',
            'predefined-range' => 'last-week'
        ]);

        $response->assertSeeText("Monday, February 7th");
        $response->assertSeeText("Sunday, February 13th");
    }


    public function test_range_last_month_includes_proper_dates(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'now' => '2000-02-16',
            'predefined-range' => 'previous-month'
        ]);

        $response->assertSeeText("Saturday, January 1st");
        $response->assertSeeText("Monday, January 31st");
    }

    public function test_range_month_to_date_includes_proper_dates(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'now' => '2000-07-27',
            'predefined-range' => 'month-to-date'
        ]);

        $response->assertSeeText("Saturday, July 1st");
        $response->assertSeeText("Thursday, July 27th");
    }

    public function test_range_year_to_date_includes_proper_dates(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'now' => '2001-09-12',
            'predefined-range' => 'year-to-date'
        ]);

        $response->assertSeeText("Monday, January 1st");
        $response->assertSeeText("Wednesday, September 12th");
    }

    //TODO Need a test for all time.
    // TODO Make sure that the grouping header is proper.
    // Task by Project

    public function test_group_by_project_and_task_displays_properly(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'group-by' => 'project'
        ]);

        $response->assertSeeText("Task by Project");
    }


    // Project by Task
    public function test_group_by_task_and_project_displays_properly(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/reports/test', [
            'group-by' => 'task'
        ]);

        $response->assertSeeText("Project by Task");
    }



    /**
     * Report data is proper
     * These are tests to make sure that report data is appearing properly.
     * They rely on the data that was created in setuo().
     * They may not be the best tests. But they should help to catch some basic display problems.
     */

    public function test_report_by_project_includes_projects_and_tasks(): void
    {

        // This week
        // March 1, 2000
        // Project
        $this->actingAs($this->user);

        $response = $this->post(route('reports-newReportTest'), [
            'now'              => '2000-02-01',
            'predefined-range' => 'this-week',
            'group-by'         => 'project'
        ]);

        $response->assertSeeText("Test Project A");
        $response->assertSeeText("Test Project B");
        $response->assertSeeText("Test Task A");
        $response->assertSeeText("Test Task B");
    }

    /**
     * This one is basically the same as the previous one, just grouped differently.
     *
     * @return void
     */
    public function test_report_by_task_includes_tasks_and_projects(): void
    {

        // This week
        // March 1, 2000
        // Project
        $this->actingAs($this->user);

        $response = $this->post(route('reports-newReportTest'), [
            'now'              => '2000-02-01',
            'predefined-range' => 'this-week',
            'group-by'         => 'task'
        ]);

        $response->assertSeeText("Test Project A");
        $response->assertSeeText("Test Project B");
        $response->assertSeeText("Test Task A");
        $response->assertSeeText("Test Task B");
    }
}
