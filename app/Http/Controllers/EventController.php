<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

use App\Project;
use App\Task;
use Auth;

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
        //dd(request()->all()); 
        //Event::create(request( ['date', 'project_id', 'task_id'] ));
        Auth::user()->events()->create(request( ['date', 'project_id', 'task_id', 'event_date', 'duration', 'note', 'time_start', 'time_end'] ));
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
