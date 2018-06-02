@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">tasks</div>

                <div class="card-body">
                    @if ($tasks->isNotEmpty())
                        @foreach ($tasks as $task)
                            <article>
                                <h5><a href="{{$task->path()}}">{{ $task->name }}</a>@if ($task->share === 1) <span class="badge badge-info">Shared<span>@endif</h5>
                                        <div>{{ $task->description }}</div>
                                        <hr />
                            </article>
                        @endforeach
                    @else
                        <article>
                            <h5>You don't have any tasks, yet.</h5>
                        </article>
                    @endif
                </div>
                @if (count($otherSharedTasks) >= 1)
                    <div class="card-body">
                        @foreach ($otherSharedTasks as $sharedTask)
                            <article>
                                <h4>{{ $sharedTask->name }}</h4>
                                <div>{{ $sharedTask->description }}</div>
                                <hr />
                            </article>
                        @endforeach
                @endif
                </div>

                <div class="card-body">
                    <h5 class="card-title">Add a task</h5>
                    <div class="card-text">
                    <form method="POST" action="/what-should-this-be?">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>


            </div> {{-- card --}}
        </div> {{-- col-md-8 --}}
    </div> {{-- row justify-content-center --}}
</div> {{-- container --}}
@endsection
 
