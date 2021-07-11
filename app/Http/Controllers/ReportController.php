<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startTime = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('y')));
        $endTime = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('y')));
        $events = new Event();
        $eventsCollection = $events->whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->get();
        // dd($eventsCollection);
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);

        return view('reports.index', ['events' => $eventsCollection, 'total' => $totalTime]);
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

        // switch ($range) {
        //     case 'this-year':
        //         $startTime = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('y')));
        //         $endTime = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('y')));
        //         break;
        //     default:
        // }

        $now = new Carbon();
        $startTime = $now->copy()->startOfWeek();
        $endTime = $now->copy()->endOfWeek();

        // dd(['Now' => $now->toString(), 'Start' => $startTime->toString(), 'End' => $endTime->toString()]);

        $events = new Event();
        $eventsCollection = $events->whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->get();
        // dd($eventsCollection);
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy($groupArray)->groupBy($groupArray);

        return view('reports.show', ['events' => $eventsCollection, 'total' => $totalTime, 'group' => $groupDisplayArray]);
    }
}
