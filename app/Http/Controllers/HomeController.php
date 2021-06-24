<?php

namespace App\Http\Controllers;

use App\Event;
use App\Project;
use App\Task;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Solution10\Calendar\Calendar as Calendar;
use Solution10\Calendar\Resolution\MonthResolution;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param string $eventdate the date in yyyy-mm-dd format
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventdate = null)
    {
        // Hmmm. Is binding to a Carbon date a good idea? Can I use a php Date instead? That way, I might be able to
        // use any Class that used the Date Interface?? Or even as the default value?
        $now = Carbon::now();
        if (!$eventdate) {
            $searchForDate = Carbon::now();
        } else {
            $searchForDate = Carbon::createFromFormat('Y-m-d', $eventdate);
            if ($searchForDate->format('Y-m-d') != $eventdate) {
                abort(404);
            }
        }
        $calendar = new Calendar($searchForDate);
        $calendar->setResolution(new MonthResolution());
        $viewData = $calendar->viewData();
        // Calendar always returns an array of Months, but we just need one, so let's just get the first element of the array.
        $month = $viewData['contents'][0];
        //dd($calendar);

        $previousMonth = $searchForDate->copy()->subMonth()->startOfMonth();
        $nextMonth = $searchForDate->copy()->addMonth()->startOfMonth();

//        dd($searchForDate);
        $thisDaysEvents = Event::where(['user_id' => $request->user()->id])->whereDate('event_date', $searchForDate->toDateString())->orderBy('time_start')->get();

//        dd($thisDaysEvents);

        $justDurations = $thisDaysEvents->pluck('iso_8601_duration');

        $totalDuration = new CarbonInterval(0);
//        dump($totalDuration);
        foreach ($justDurations as $duration) {
            $totalDuration = $totalDuration->add($duration);
        }
//        dump($justDurations);

//        dd($totalDuration->cascade()->format('%H %I'));

        // Please comment this!! It's crazy.
        $thisMonthsEvents = Event::whereMonth('event_date', $searchForDate->month)->whereYear('event_date', $searchForDate->year)->select('event_date')->distinct()->orderBy('event_date')->pluck('event_date');
        //dd($thisMonthsEvents);

        // We'll need a list of Projects
        $projects = Project::allAvailable()->orderBy('name')->get();
        // dd($projects);

        // And a list of Tasks.
        $tasks = Task::allAvailable()->orderBy('name')->get();
        //dd($tasks);

        return view('home', compact('now', 'previousMonth', 'nextMonth', 'thisDaysEvents', 'searchForDate', 'thisMonthsEvents', 'month', 'projects', 'tasks', 'totalDuration'));
    }
}
