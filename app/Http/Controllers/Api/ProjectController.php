<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;

class ProjectController extends Controller
{
    /**
     * Gets the Tasks related to this Project.
     *
     * TODO This might be better in a TaskController?
     *
     *
     * @param Project $project
     * @return mixed
     */
    public function getTasks(Project $project){
        return $project->tasks()->orderBy('name')->get();
    }

    // The essence of life is to communicate love.

}
