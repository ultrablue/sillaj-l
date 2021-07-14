<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the User's Projects.
        $projects = Project::where('user_id', $request->user()->id)->orderBy('name')->get() ?? '';
        $otherSharedProjects = Project::where([['user_id', '<>', $request->user()->id], ['share', '=', true]])->get();

        return view('projects.index', compact('projects', 'otherSharedProjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allTasks = Task::allAvailable()->get();
//        dd($allTasks);
        return view('projects.create', compact('allTasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:projects|max:255',
        ]);

        $project = Auth::user()->projects()->create($request->all());

        $project->tasks()->sync($request->get('tasks'));
        session()->flash('project_id', $project->id);

        return redirect()->route('projects-list');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $allTasks = Task::allAvailable()->orderBy('name')->get();

        return view('projects.show', compact('project', 'allTasks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
//        dd($request->all());
//        dd($request->get('tasks'));

        $project->update($request->all());
        $project->tasks()->sync($request->get('tasks'));
        session()->flash('project_id', $project->id);

        return redirect()->route('projects-list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('home')->with('status', 'Project deleted.');
    }
}
