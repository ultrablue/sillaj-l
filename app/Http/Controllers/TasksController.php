<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
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
    public function index(Request $request)
    {
        // Get the User's Projects.
        $tasks = Task::where('user_id', $request->user()->id)->orderBy('name')->get() ?? '';
        $otherSharedTasks = Task::where([['user_id', '<>', $request->user()->id], ['share', '=', true]])->get();

        return view('tasks.index', compact('tasks', 'otherSharedTasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allProjects = Project::allAvailable()->get();
//        dd($allProjects);
        return view('tasks.create', compact('allProjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tasks|max:255',
        ]);

        $task = Auth::user()->tasks()->create($request->all());

        $task->projects()->sync($request->get('projects'));
        session()->flash('task_id', $task->id);

        return redirect()->route('tasks-list');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $allProjects = Project::allAvailable()->orderBy('name')->get();
//        dd($allProjects);
        return view('tasks.show', compact('task', 'allProjects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
//        dd($request->get('projects'));
//
//        dd($request->all());

        $task->update($request->all());

        $task->projects()->sync($request->get('projects'));
        session()->flash('task_id', $task->id);

        return redirect()->route('tasks-list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('home')->with('status', 'Project deleted.');
    }
}
