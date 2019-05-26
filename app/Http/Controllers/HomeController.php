<?php

namespace App\Http\Controllers;

use App\Event;
use App\Project;
use App\Task;
use Illuminate\Http\Request;

use Carbon\Carbon;

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
     * @param Request $request
     * @param String $eventdate The date in yyyy-mm-dd format.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventdate = null)
    {


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

        //dd($eventdate);
        $thisDaysEvents = Event::where(['user_id' => $request->user()->id])->whereDate('event_date', $searchForDate->toDateString())->orderBy('time_start')->get();
//        dd($thisDaysEvents);

        // Please comment this!! It's crazy.
        $thisMonthsEvents = Event::whereMonth('event_date', $searchForDate->month)->whereYear('event_date', $searchForDate->year)->select('event_date')->distinct()->orderBy('event_date')->pluck('event_date');
        //dd($thisMonthsEvents);

        // We'll need a list of Projects
        $projects = Project::allAvailable()->orderBy('name')->get();
        //dd($projects);


        // And a list of Tasks.
        $tasks = Task::allAvailable()->orderBy('name')->get();
        //dd($tasks);


        return view('home', compact('now', 'previousMonth', 'nextMonth', 'thisDaysEvents', 'searchForDate', 'thisMonthsEvents', 'month', 'projects', 'tasks'));

    }
}
