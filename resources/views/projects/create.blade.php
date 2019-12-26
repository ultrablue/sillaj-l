@extends('layouts.app')

@section('content')

    @if($errors->any())
        <div role="alert">
            <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                <span class="oi" data-glyph="warning"></span> Danger
            </div>
            <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                @foreach ($errors->all() as $message)
                    <p>{{$message}}</p>
                @endforeach
            </div>
        </div>
    @endif

    <h2>Create a new Project!</h2>

    @if(isset ($project))
        {!! Form::model($project, ['action' => ['ProjectsController@update']]) !!}
    @else
        {{ Form::open(['action' => 'ProjectsController@store']) }}
    @endif
    <div class="w-full px-3 mb-6">
        {{Form::label('name', 'Name:', ['class' => 'block text-gray-700 text-sm']) }}
        {{ Form::text('name',null,['class' => 'text-field focus:outline-none focus:shadow-outline']) }}
    </div>

    <div class="w-full px-3 mb-6">
        {!! Form::label('description', 'Description:', ['class' => 'block text-gray-700 text-sm']) !!}
        {!! Form::textarea('description',null,['class' => 'text-field focus:outline-none focus:shadow-outline', 'rows' => '3']) !!}
    </div>

    <div class="w-full px-3 mb-6">
        {!! Form::label('display', 'Display:', ['class' => 'block text-gray-700 text-sm']) !!}
        {{ Form::hidden('display', 0) }}
        {!! Form::checkbox('display',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) !!}
    </div>

    <div class="w-full px-3 mb-6">
        {!! Form::label('share', 'Share:', ['class' => 'block text-gray-700 text-sm']) !!}
        {{ Form::hidden('share', 0) }}
        {!! Form::checkbox('share',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) !!}
    </div>

    <div class="w-full px-3 mb-6">
        {!! Form::label('use_in_reports', 'Use In Reports:', ['class' => 'block text-gray-700 text-sm']) !!}
        {{ Form::hidden('use_in_reports', 0) }}
        {!! Form::checkbox('use_in_reports',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) !!}
    </div>


    <div class="w-full px-3 mb-6">

        @foreach ($allTasks as $task)

            @if(isset($project))
                {{-- TODO This is probably horribly ineffecient!! --}}
                @php $checked = in_array($task->id, $project->tasks->pluck('id')->toArray()) ? true : false @endphp
                {{ Form::checkbox('tasks[]',$task->id) }} {{ $task->name }}<br>
            @else
                {{ Form::checkbox('tasks[]',$task->id) }} {{ $task->name }}<br>
            @endif

        @endforeach


    </div>

    {!! Form::submit('Save', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline']) !!}



    {!! Form::close() !!}

@endsection
 
