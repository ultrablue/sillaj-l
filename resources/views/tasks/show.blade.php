@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$task->name}}</div>

                <div class="card-body">
                     <h5 class="card-title">
                        {{ $task->description }}
                     </h5>
                     <ul class="list-group list-group-flush">
                         <li class="list-group-item">ID: {{$task->id}}</li>
                         <li class="list-group-item">User: {{$task->user_id}}</li>
                         <li class="list-group-item">Display: {{$task->display}}</li>
                         <li class="list-group-item">Share: {{$task->share}}</li>
                         <li class="list-group-item">Use in Reports: {{$task->use_in_reports}}</li>
                         <li class="list-group-item">Created: {{$task->created_at->diffForHumans()}}</li>
                         <li class="list-group-item">Modified: {{$task->updated_at->diffForHumans()}}</li>
                    </ul>
                </div>

            <div class="card-body">
                <h5 class="card-title">Projects</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($task->projects as $project)
                    <li class="list-group-item">{{$project->name}}</li>
                    @endforeach
                </ul>
            </div>
                
        </div>
    </div>
</div>
@endsection

