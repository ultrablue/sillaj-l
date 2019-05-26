<?php

namespace App\Http\Controllers;

use App\Event;
use Exception;
use Illuminate\Http\Request;

use App\Project;
use App\Task;
use Auth;
use Illuminate\Http\Response;
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
     * @return Response
     */
    public function index(Request $request)
    {

        return view('events.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     * @param Request $request
     * @return Response
     * @throws Exception Either the start time or duration are required.
     */
    public function store(Request $request)
    {
        //TODO I need some validation, please!

        // If no end time was sent, set the end time to the start time.
        if (!$request['time_end']) {
            $request['time_end'] = $request['time_start'];
        }


        // TODO move this to a Mutator in the Model?
        // Are both the start time and the end time populated? If so, we use them to calculate and set the duration.
        if (empty($request['time_start'])) {
            throw new Exception("Start time is required.");
        } elseif ($request['time_start'] && $request['time_end']) {
            // Using strtotime instead of Carbon because it seems easier.
            $start = strtotime($request['time_start']);
            $end = strtotime($request['time_end']);
            $request['duration'] = $end - $start;
        } // Since neither start time nor end time were present, parse HH:MM duration.
        elseif ($request['duration']) {
            // TODO We don't want or need seconds here?!?!?!?
            // TODO Add the duration to the start time to get end time, please.
            list($hours, $minutes, $seconds) = array_pad(explode(':', $request['duration']), 3, 0);
            $duration = ($hours * 60 * 60) + ($minutes * 60) + $seconds;
            $request['duration'] = $duration;
        } else {
            throw new Exception("Either start time or duration are required.");
        }
        Auth::user()->events()->create(request(['date', 'project_id', 'task_id', 'event_date', 'duration', 'note', 'time_start', 'time_end']));
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Event $event
     * @return Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Event $event
     * @return Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Event $event
     * @return Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Event $event
     * @return Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
