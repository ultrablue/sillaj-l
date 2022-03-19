<?php

namespace App\Http\Controllers;

use App\Event as Event;
use App\Mail\Report;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Handles all Report Generation.
 */
class ReportController extends Controller
{


    /**
     * 
     * The index Action. Displays a parameters form for the reports.
     * 
     * @param Request $request
     * 
     * @return View.
     */
    public function index(Request $request)
    {
        return view('reports.index');
    }

    /**
     * 
     * Handles creating Report data given the parameters selected in the paramters form.
     * 
     * @param Request $request
     * 
     * @return [type]
     */
    public function show(Request $request)
    {
        // ddd($request->all());

        // Grouping, which can be by Project or by Task. Defaults to Project.
        $group = $request->input('group-by', 'project');
        // The date range of the report. Defaults to this week.
        $range = $request->input('predefined-range', 'this-week');
        // The Leave filter, if there is one. Defaults to null.
        $filter = $request->input('task-group-filter', null);

        // The View uses this to properly group the output as well as to display a properly descriptive description in the Report View's header.
        $groupDisplayArray = [];
        switch ($group) {
            case 'project':
                $groupDisplayArray = ['Project', 'Task'];
                break;
            case 'task':
                $groupDisplayArray = ['Task', 'Project'];
                break;
        }

        /*
         * This bit figures out the start datetime and end datetimes based on what was requested.
         */

        // now will be used a few times.
        $now = new CarbonImmutable();

        switch ($range) {
            case 'previous-month':
                $startTime = $now->startOfMonth()->subMonthsNoOverflow();
                $endTime = $now->subMonthsNoOverflow()->endOfMonth();
                break;
            case 'month-to-date':
                $startTime = $now->copy()->startOfMonth();
                $endTime = $now;
                break;
            case 'year-to-date':
                $startTime = $now->copy()->startOfYear();
                $endTime = $now;
                break;
            case 'all-time':
                $startTime = new CarbonImmutable(Event::where('user_id', '=', auth()->user()->id)->min('event_date'));
                $endTime = new CarbonImmutable(Event::where('user_id', '=', auth()->user()->id)->max('event_date'));
                break;
            case 'custom':
                // TODO Validate these!!!!
                $startTime = new CarbonImmutable($request->start_date);
                $endTime = new CarbonImmutable($request->end_date);
                if ($group === 'project') {
                    $eventsCollection = Event::rollupByProject($startTime, $endTime);
                } elseif ($group === 'task') {
                    $eventsCollection = Event::rollupByTask($startTime, $endTime);
                }
                break;
            case 'last-week':
                $startTime = $now->startOfWeek()->subDays(7);
                $endTime = $startTime->endOfWeek();
                break;
            default:
            case 'this-week':
                $startTime = $now->startOfWeek();
                $endTime = $now->endOfWeek();
                break;
        }


        // TODO There's got to be a better way of organizing all of the decisions based on the parameter settings.
        if ($filter) {
            // This is a superduper hack, waiting for the groups to be stored in the database.
            $filterGroup[1] = [7, 8];
            $eventsCollection = Event::filterByTaskGroup($startTime, $endTime, $filterGroup[$filter]);
            $groupedEvents = $eventsCollection->groupBy('name');
            // ddd($groupedEvents);
            return view('reports.leave', ['events' => $eventsCollection->groupBy('name'), 'dates' => ['start' => $startTime, 'end' => $endTime]]);
            // ddd($eventsCollection);
        } else {
            // Get the proper data. Note that the dataset is the result of ROLL UP in the query.
            if ($group === 'project') {
                $eventsCollection = Event::rollupByProject($startTime, $endTime);
            } elseif ($group === 'task') {
                // ddd($group);
                $eventsCollection = Event::rollupByTask($startTime, $endTime);
            }
        }
        return view('reports.show', ['events' => $eventsCollection, 'total' => $eventsCollection->last()->duration, 'group' => $groupDisplayArray, 'dates' => [$startTime, $endTime]]);
    }

    // I think these ones are for the email reports?
    public function currentDayByProjectReport(Request $request)
    {
        // TODO 💥- Make some Tests please!!!!!
        // The View will need this. I suppose it could get it itself. 🤔
        $now = new Carbon();

        $eventsCollection = auth()->user()
            ->events()
            ->whereBetween('event_date', [$now->toDateString(), $now->toDateString()])
            ->with(['task', 'project'])
            ->get();

        $totalTime = $eventsCollection->sum('duration');

        // By Project, then Task.
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
        $groupDisplayArray = ['Project', 'Task'];
        $reportHeader = 'Hours for ' . $now->format('l F j, Y');
        // Mail::send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now));
        return new Report($eventsCollection, $groupDisplayArray, $totalTime, $now, $reportHeader);
    }

    public function previousMonthReportByProject(Request $request)
    {
        $now = new CarbonImmutable();
        $startOfLastMonth = $now->subMonthsNoOverflow()->startOfMonth();
        $endOfLastMonth = $now->subMonthsNoOverflow()->endOfMonth();
        $user = $request->user();

        // This exact query is duplicated in previousMonthReportByTask. But I'm not what the best way to DRY it.
        $eventsCollection = auth()->user()
            ->events()
            ->whereBetween('event_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])
            ->with(['task', 'project'])
            ->get();

        $totalTime = $eventsCollection->sum('duration');
        // By Project, then Task.
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
        $groupDisplayArray = ['Project', 'Task'];
        $reportHeader = 'Effort for ' . $startOfLastMonth->format('F Y') . ' by Project';
        // TODO - is there a way to determine context? IOW, if this was called from the CLI/Artisan, then do an email. Otherwise, return a view.
        // Mail::send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now));
        return new Report($eventsCollection, $groupDisplayArray, $totalTime, $now, $reportHeader);
    }

    public function previousMonthReportByTask(Request $request)
    {
        $now = new CarbonImmutable();
        $startOfLastMonth = $now->subMonthsNoOverflow()->startOfMonth();
        $endOfLastMonth = $now->subMonthsNoOverflow()->endOfMonth();
        $user = $request->user();

        $eventsCollection = Event::rollUp($startOfLastMonth, $endOfLastMonth, $user);

        // This exact query is duplicated in previousMonthReportByProject. But I'm not what the best way to DRY it.
        $eventsCollection = auth()->user()
            ->events()
            ->whereBetween('event_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])
            ->with(['task', 'project'])
            ->get();

        $totalTime = $eventsCollection->sum('duration');
        // By Task, then Project.
        $eventsCollection = $eventsCollection->sortBy(['task.name', 'project.name'])->groupBy(['task.name', 'project.name']);

        $groupDisplayArray = ['Task', 'Project'];
        $reportHeader = 'Effort for ' . $startOfLastMonth->format('F Y') . ' by Task';
        // TODO - is there a way to determine context? IOW, if this was called from the CLI/Artisan, then do an email. Otherwise, return a view.
        // Mail::send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now));
        return new Report($eventsCollection, $groupDisplayArray, $totalTime, $now, $reportHeader);
    }

    // Gah. I'm not sure how to DRY this up.
    private function reportQuery(CarbonImmitable $start, CarbonImmutable $end, User $user, array $grouping)
    {
        $eventsCollection = Event::rollUp($start, $end, $user);
    }
}
