<?php

namespace App\Http\Controllers;

use App\Event;
use App\Project;
use App\Task;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Add authentication to this entire Controller; it has no publicly
     * available methods.
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
     * @return Response
     *
     * @throws Exception either the start time or duration are required
     */
    public function store(Request $request)
    {
        // dd(@$request->all());

        // TODO Make the space one work for multiple spaces, eg: 1       1.
        $timeAndDurationRegex = '#^(\d{1,2})?([ :\.])?(\d{1,2})?$#';

        $validatedData = $request->validate([
            'duration' => "nullable|regex:$timeAndDurationRegex",
            'time_start' => "nullable|regex:$timeAndDurationRegex",
            'time_end' => "nullable|regex:$timeAndDurationRegex",
        ]);

        // dd($validatedData);

        // dd($request->event_date);

        $event = Auth::user()->events()->create($request->all());

        return redirect($request->event_date);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Event $event)
    {
        // We'll need a list of Projects
        $projects = Project::allAvailable()->orderBy('name')->get();
        //dd($projects);

        // And a list of Tasks.
        $tasks = Task::allAvailable()->orderBy('name')->get();
        //dd($tasks);
        return view('events.show', compact('event', 'projects', 'tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Event $event)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Event $event)
    {
//        dd($request->all());
//        dd($event);
        $event->update($request->all());
//        $event = Auth::user()->events()->update($request->all());
        return redirect()->route('home');
    }

    /**
     * Soft delete the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('home')->with('status', 'Event deleted.');
    }
}
