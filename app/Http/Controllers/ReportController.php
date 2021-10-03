<?php

/**
 * What needs to be displayed:
 *    The report's title. Something like this:
 *       Hours for [day: the date; week of [start and end], month of [month name], year to date [start(?) - end]], etc.
 *    The various Levels (by Project (Level 1) and Task (Level 2), by Task and Project)
 *       The percentage of the total for the Level.
 *    A graph of the percentages
 *       A pie chart seems like the best choice?
 *
 *  Report Date Ranges
 *      Custom/Any - any two dates.
 */

namespace App\Http\Controllers;

use App\Event;
use App\Mail\Report;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('reports.index');
    }

    public function show(Request $request)
    {
        $group = $request->input('group-by', 'project');
        $range = $request->input('predefined-range', 'this-week');

        /**
         * The way I'm dealing with how the View knows how to display the grouping is pretty lazy.
         * It's achieved in $groupDisplayArray, which is hard-coded here.
         * There's probably a better way to do it. At some point, for example, when we implement
         * a Clients group, it'll probably make sense to make it more dynamic.
         */
        $groupArray = [];
        $groupDisplayArray = [];
        switch ($group) {
            case 'project':
                $groupArray = ['project.name', 'task.name'];
                $groupDisplayArray = ['Project', 'Task'];
                break;
            case 'task':
                $groupArray = ['task.name', 'project.name'];
                $groupDisplayArray = ['Task', 'Project'];
                break;
        }

        /*
         * This bit figures out the start datetime and end datetimes based on what was requested.
         */

        // So far, we're only interested in ranges that are relative to today, so now will be used a few times.
        $now = new Carbon();

        // this-week
        // month-to-date
        // year-to-date
        // (all-time)
        switch ($range) {
            case 'month-to-date':
                $startTime = $now->copy()->startOfMonth();
                $endTime = $now;
                break;
            case 'year-to-date':
                $startTime = $now->copy()->startOfYear();
                $endTime = $now;
                break;
            default:
            case 'this-week':
                $startTime = $now->copy()->startOfWeek();
                $endTime = $now->copy()->endOfWeek();
                break;
        }

        $events = new Event();
        $eventsCollection = $events->whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->where(['user_id' => $request->user()->id])->get();
        // dd($eventsCollection);
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy($groupArray)->groupBy($groupArray);

        return view('reports.show', ['events' => $eventsCollection, 'total' => $totalTime, 'group' => $groupDisplayArray, 'dates' => [$startTime, $endTime]]);
    }

    public function emailReport(Request $request)
    {
        // TODO - Change the name of the Method to Event::DailyRollUp() (or something).
        // TODO - Fix the display to handle both (or all?) cases. Right now, it's showing Project even when Task is the top-level group.
        // TODO 💥- Make some Tests please!!!!!
        // Thew View will need this. I suppose it could get it itself. 🤔
        $now = new Carbon();
        $event = new Event();
        $eventsCollection = $event->dailyRollupByProject($now, $request->user()->id);
        $totalTime = $eventsCollection->sum('duration');

        // By Project, then Task.
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
        // By Task, then Project.
        // $eventsCollection = $eventsCollection->sortBy(['task.name', 'project.name'])->groupBy(['task.name', 'project.name']);
        // dd($eventsCollection);
        $groupDisplayArray = ['Project', 'Task'];
        // TODO Ooops, this is a dupe.
        $now = new Carbon();
        // Mail::send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now));
        return new Report($eventsCollection, $groupDisplayArray, $totalTime, $now);
    }

    public function previousMonthReport(Request $request)
    {
        $now = new CarbonImmutable();
        $event = new Event();

        $eventsCollection = $event->monthlyRollup($now);
        $totalTime = $eventsCollection->sum('duration');
        // By Project, then Task.
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
        // By Task, then Project.
        // $eventsCollection = $eventsCollection->sortBy(['task.name', 'project.name'])->groupBy(['task.name', 'project.name']);
        // dd($eventsCollection);
        $groupDisplayArray = ['Project', 'Task'];
        // Mail::send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now));
        return new Report($eventsCollection, $groupDisplayArray, $totalTime, $now);
    }

    private function reportQuery()
    {
        // code...
    }
}
