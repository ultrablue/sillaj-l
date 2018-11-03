<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

use App\Project;
use App\Task;
use Auth;
use Validator;

use Carbon\Carbon;

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
    public function index()
    {
        //
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
/*
        $validator = Validator::make($request->all(), [
	    'task_id'    => 'required',
	    'project_id' => 'required',
	]);

	//time_end is required if time_start has a value and duration does not.
       $validator->sometimes('time_end', 'required', function ($input) {
                 
	    return ($input->time_start && !$input->duration);
	});

	//time_start is required if time_end has a value.
	$validator->sometimes('time_start', 'required', function ($input) {
	    return ($input->time_end);
	});

	//duration is required if neither time_start nor time_end have values or if time_start has a value and time_end does not.
	$validator->sometimes('duration', 'required', function ($input) {
	    return (!$input->time_start && !$input->time_end) || ($input->time_start && !$input->time_end);
	});

	if ($validator->fails()) {
	    dd($validator->errors());	
	    return redirect('event/create')
		->withErrors($validator)
		->withInput();
	} 
	dd($request->all());
            
        // Are both the start time and the end time populated? If so, we use them to calculate and set the duration.
        if ($request['time_start'] && $request['time_end']) {
            // Using strtotime instead of Carbon because it seems easier.
            $start = strtotime($request['time_start']);
            $end = strtotime($request['time_end']);
            $request['duration'] = $end - $start;
        } 
        // Since neither start time nor end time were present, parse HH:MM duration.
        else {
            //dd($request['duration']);
            list($hours, $minutes, $seconds) = array_pad(explode(':',$request['duration']), 3, 0);
            $duration = ($hours * 60 * 60) + ($minutes * 60) + $seconds;
            //dd($duration);
            $request['duration'] = $duration;
        }
 */
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
