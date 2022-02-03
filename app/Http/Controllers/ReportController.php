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

use App\Event as Event;
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
                // $eventsCollection = Event::whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->where(['user_id' => $request->user()->id])->get();
                if ($group === 'project') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.project_id', 'projects.name'])
                        ->selectRaw('SUM(duration) as totalDuration, projects.name')
                        ->whereBetween('event_date', [$startTime, $endTime])
                        ->join('projects', 'events.project_id', '=', 'projects.id')
                        ->orderBy('projects.name')
                        ->get();
                } elseif ($group === 'task') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.task_id', 'tasks.name'])
                        ->selectRaw('SUM(duration) as totalDuration, tasks.name')
                        ->whereBetween('event_date', [$startTime, $endTime])
                        ->join('tasks', 'events.task_id', '=', 'tasks.id')
                        ->orderBy('tasks.name')
                        ->get();
                }
                break;
            case 'year-to-date':
                $startTime = $now->copy()->startOfYear();
                $endTime = $now;
                $eventsCollection = Event::whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->where(['user_id' => $request->user()->id])->get();
                break;
            case 'all-time':
                if ($group === 'project') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.project_id', 'projects.name'])
                        ->selectRaw('SUM(duration) as totalDuration, projects.name')
                        ->join('projects', 'events.project_id', '=', 'projects.id')
                        ->orderBy('projects.name')
                        ->get();
                } elseif ($group === 'task') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.task_id', 'tasks.name'])
                        ->selectRaw('SUM(duration) as totalDuration, tasks.name')
                        ->join('tasks', 'events.task_id', '=', 'tasks.id')
                        ->orderBy('tasks.name')
                        ->get();
                }
                // dd($range);
                // We don't need either of these for this Report.
                // $startTime = null;
                // $endTime = null;
                // $eventsCollection = Event::where(['user_id' => $request->user()->id])
                // ->groupBy('project_id', 'id', 'task_id', 'user_id', 'time_start')
                // ->orderBy('project_id')
                // ->get();
                break;
            default:
            case 'this-week':
                $startTime = $now->copy()->startOfWeek();
                $endTime = $now->copy()->endOfWeek();
                // $eventsCollection = Event::whereBetween('event_date', [$startTime, $endTime])->with(['task', 'project'])->where(['user_id' => $request->user()->id])->get();
                if ($group === 'project') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.project_id', 'projects.name'])
                        ->selectRaw('SUM(duration) as totalDuration, projects.name')
                        ->whereBetween('event_date', [$startTime, $endTime])
                        ->join('projects', 'events.project_id', '=', 'projects.id')
                        ->orderBy('projects.name')
                        ->get();
                } elseif ($group === 'task') {
                    $eventsCollection = Event::where(['events.user_id' => $request->user()->id])
                        ->groupBy(['events.task_id', 'tasks.name'])
                        ->selectRaw('SUM(duration) as totalDuration, tasks.name')
                        ->whereBetween('event_date', [$startTime, $endTime])
                        ->join('tasks', 'events.task_id', '=', 'tasks.id')
                        ->orderBy('tasks.name')
                        ->get();
                }
                break;
        }

        // $events = new Event();
        // dd($eventsCollection->count());
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy($groupArray)->groupBy($groupArray);
        dd($eventsCollection->first()->first()->count());
        return view('reports.show', ['events' => $eventsCollection, 'total' => $totalTime, 'group' => $groupDisplayArray, 'dates' => [$startTime, $endTime]]);
    }

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
