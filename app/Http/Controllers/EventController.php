<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

use App\Project;
use App\Task;
use Auth;
use Validator;

use Carbon\Carbon;

use Solution10\Calendar\Calendar as Calendar;
use Solution10\Calendar\Resolution\MonthResolution;

class EventController extends Controller
{
    /**
     * Add authentication to this entire Controller; it has no publicly
     * available methods.
     *
     */
    public function __construct()
    {
        // TODO We can also move this to routes.
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventdate = null)
    {
        $now = Carbon::now();
        if (!$eventdate){
            $searchForDate  = Carbon::now();
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
        // This is a comment.
        $thisDaysEvents = Event::where(['user_id' => $request->user()->id])->whereDate('event_date' , $searchForDate->toDateString())->orderBy('time_start')->get();


        // Please comment this!! It's crazy.
        $thisMonthsEvents = Event::whereMonth('event_date', $searchForDate->month)->whereYear('event_date', $searchForDate->year)->select('event_date')->distinct()->orderBy('event_date')->pluck('event_date');
        //dd($thisMonthsEvents);

        // We'll need a list of Projects
        $projects = Project::allAvailable()->orderBy('name')->get();
        //dd($projects);
        
        
        // And a list of Tasks.
        $tasks = Task::allAvailable()->orderBy('name')->get();
        //dd($tasks);
 

       return view('events.index', compact('now', 'previousMonth', 'nextMonth', 'thisDaysEvents', 'searchForDate', 'thisMonthsEvents', 'month', 'projects', 'tasks')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // We'll need a list of Projects
        $projects = Project::allAvailable()->orderBy('name')->get();
        //dd($projects);
        
        
        // And a list of Tasks.
        $tasks = Task::allAvailable()->orderBy('name')->get();
        //dd($tasks);
        
        
        return view('events.create', compact('projects', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // TODO move this to a Mutator in the Model?  
        // Are both the start time and the end time populated? If so, we use them to calculate and set the duration.
        if ($request['time_start'] && $request['time_end']) {
            // Using strtotime instead of Carbon because it seems easier.
            $start = strtotime($request['time_start']);
            $end = strtotime($request['time_end']);
            $request['duration'] = $end - $start;
        } 
        // Since neither start time nor end time were present, parse HH:MM duration.
        else {
            list($hours, $minutes, $seconds) = array_pad(explode(':',$request['duration']), 3, 0);
            $duration = ($hours * 60 * 60) + ($minutes * 60) + $seconds;
            $request['duration'] = $duration;
        }
        Auth::user()->events()->create(request( ['date', 'project_id', 'task_id', 'event_date', 'duration', 'note', 'time_start', 'time_end'] ));
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
