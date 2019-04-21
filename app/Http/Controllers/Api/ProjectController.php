<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;

class ProjectController extends Controller
{

    // TODO phpDocs here, please!!!
    public function getTasks(Project $project){
        return $project->tasks;
    }


}
