@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                </div>
                <div class="card-body">
                    <p>You are logged in!</p>
                </div>
                <div class="card-body">
                    @if ($events->count() === 0)
                    <div class="alert alert-warning">
                        You don't have any events, yet.
                    </div>
                @else
                    <div class="card-text">
                        <div class="table-responsive">
                            <table class="table table-sm" style="font-size:.75rem;">
                                <thead>
                                    <tr>
                                        <th scope="col">Start</th>
                                        <th scope="col">End</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Project</th>
                                        <th scope="col">Task</th>
                                        <th scope="col">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>@if (null !== $event->time_start){{ $event->start()->format('H:i') }}@endif</td>
                                        <td>@if (null !== $event->time_end){{ $event->end()->format('H:i') }}@endif</td>
                                        <td>{{ $event->duration }}</td>
                                        <td>{{ $event->project->name }}</td>
                                        <td>{{ $event->task->name }}</td>
                                        <td>{{ str_limit($event->note,100) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </div>
                @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
