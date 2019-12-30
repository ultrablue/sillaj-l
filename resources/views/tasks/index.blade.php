@extends('layouts.app')

@section('content')

    <ul class="flex">
        <li class="mr-3">
            <a class="inline-block border border-blue-500 rounded py-1 px-3 bg-blue-500 text-white" href="{{route('task-create')}}">
                <span class="oi" data-glyph="plus"></span> Create a new Task</a>
        </li>
    </ul>


    <div class="bg-auto px-3 text-xs my-6 rounded border">
        <h1 class="text-lg">My Tasks</h1>


        <div class="table w-full table-auto">
            <div class="table-row font-bold">
                <div class="table-cell">ID</div>
                <div class="table-cell">Name</div>
                <div class="table-cell">Description</div>
            </div>


            @if ($tasks->isNotEmpty())
                @foreach ($tasks as $task)

                    <div class="table-row @if(Session::get('task_id') == $task->id)bg-green-200 @endif" data-task-id="{{$task->id}}">
                        <div class="table-cell border-b-2"><a href="#">{{$task->id}}</a></div>
                        <div class="table-cell border-b-2">
                            <a href="{{route('task-show', ['task' => $task->id])}}">{{$task->name}} @if ( $task->share ===1 )*@endif</a>
                        </div>
                        <div class="table-cell border-b-2">{{$task->description}}</div>
                    </div>

                @endforeach

            @else
                <h5>You don't have any tasks, yet.</h5>
            @endif
        </div>
    </div>



    @if (count($otherSharedTasks) >= 1)
        <div class="bg-auto px-3 text-xs my-6 rounded border">
            <h1 class="text-lg">Other Shared Tasks</h1>


            <div class="table w-full table-auto">
                <div class="table-row font-bold">
                    <div class="table-cell">Name</div>
                    <div class="table-cell">Description</div>
                </div>


                @foreach ($otherSharedTasks as $sharedTask)
                    <div class="table-row" data-task-id="{{$sharedTask->id}}">
                        <div class="table-cell border-b-2">{{ $sharedTask->name }}</div>
                        <div class="table-cell border-b-2">{{ $sharedTask->description }}</div>
                    </div>
                @endforeach
                @endif


            </div>
        </div>
@endsection
 
