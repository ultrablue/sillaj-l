@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$project->name}}</div>

                <div class="card-body">
                     <div class="card-text">
                        {{ $project->description }}
                     </div>
                     <ul class="list-group list-group-flush">
                         <li class="list-group-item">ID: {{$project->id}}</li>
                         <li class="list-group-item">User: {{$project->user_id}}</li>
                         <li class="list-group-item">Display: {{$project->display}}</li>
                         <li class="list-group-item">Share: {{$project->share}}</li>
                         <li class="list-group-item">Use in Reports: {{$project->use_in_reports}}</li>
                         <li class="list-group-item">Created: {{$project->created_at}}</li>
                         <li class="list-group-item">Modified: {{$project->updated_at}}</li>
                    </ul>
                </div>
            </div>
            <div class="card-header">Tasks</div>
                <ul class="list-group list-group-flush">
                    @foreach ($project->tasks as $task)
                    <li class="list-group-item">{{$task->name}}</li>
                    @endforeach
                </ul> 
        </div>
    </div>
</div>
@endsection

