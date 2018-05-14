@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$project->name}}</div>

                <div class="card-body">
                     <h5 class="card-title">
                        {{ $project->description }}
                     </h5>
                     <ul class="list-group list-group-flush">
                         <li class="list-group-item">ID: {{$project->id}}</li>
                         <li class="list-group-item">User: {{$project->user_id}}</li>
                         <li class="list-group-item">Display: {{$project->display}}</li>
                         <li class="list-group-item">Share: {{$project->share}}</li>
                         <li class="list-group-item">Use in Reports: {{$project->use_in_reports}}</li>
                         <li class="list-group-item">Created: {{$project->created_at->diffForHumans()}}</li>
                         <li class="list-group-item">Modified: {{$project->updated_at->diffForHumans()}}</li>
                    </ul>
                </div>

            <div class="card-body">
                <h5 class="card-title">Tasks</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($project->tasks as $task)
                    <li class="list-group-item">{{$task->name}}</li>
                    @endforeach
                </ul>
            </div>
                
        </div>
    </div>
</div>
@endsection

