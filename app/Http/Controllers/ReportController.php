<?php

namespace App\Http\Controllers;

use App\Event;
use App\Mail\Report;
use Carbon\Carbon;
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
        $groupArray = ['project.name', 'task.name'];
        $groupDisplayArray = ['Project', 'Task'];
        $groupDisplayArray = ['Project', 'Task'];
        $now = new Carbon();
        $startTime = $now->copy()->startOfWeek();
        $endTime = $now->copy()->endOfWeek();

        $events = new Event();
        $eventsCollection = $events->whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->where(['user_id' => $request->user()->id])->get();
        // dd($eventsCollection);
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy($groupArray)->groupBy($groupArray);

        return new Report($eventsCollection, $groupDisplayArray, $totalTime);
        // Mail::to('to@to.to', "To To")->send(new Report('Good day, sir!'));
    }

    private function reportQuery()
    {
        // code...
    }
}
