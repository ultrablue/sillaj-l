@extends('layouts.app')

@section('content')


    <div>
        <h1>Navigation goes Here</h1>
        <ul class="lg:flex items-center justify-between text-base text-gray-700 pt-4 lg:pt-0">
            <li><a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400"
                    href="#">This week</a></li>
            <li>This month</li>
            <li>Year to date</li>
            <li>All time</li>
        </ul>



    </div>


    <div class="mt-20">

        {!! Form::open(['route' => 'reports-show', 'class' => 'w-full max-w-sm']) !!}
        <div class="">
            <div class="">Group By</div>
            {{ Form::label('group-by-project', 'Project') }}
            {{ Form::radio('group-by', 'project', ['id' => 'group-by-project']) }}
            <br>
            {{ Form::label('group-by-task', 'Task') }}
            {{ Form::radio('group-by', 'task', ['id' => 'group-by-task']) }}

        </div>
        <div class="mt-10">
            <div class="">Predefined Range</div>
            {{ Form::label('predefined-range-this-week', 'This week') }}
            {{ Form::radio('predefined-range', 'this-week', ['id' => 'predefined-range-this-week']) }}
            <br>

            {{ Form::label('predefined-range-this-month', 'This month') }}
            {{ Form::radio('predefined-range', 'this-month', ['id' => 'predefined-range-this-month']) }}
            <br>

            {{ Form::label('predefined-range-this-year', 'This year') }}
            {{ Form::radio('predefined-range', 'this-year', ['id' => 'predefined-range-this-year']) }}
            <br>

            {{ Form::label('predefined-range-all-time', 'All Time') }}
            {{ Form::radio('predefined-range', 'all-time', ['id' => 'predefined-range-all-time']) }}
            <br>


        </div>
        <div class="mt-10">
            {{ Form::submit('Click Me!', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) }}
        </div>
        {!! Form::close() !!}
        <hr class="p-0 mt-10">

        <div class="py-5">
            <ul class="list-disc py-3">
                <li class="line-through">Add layout</li>
                <li>Make Table layout for report</li>
                <li>Add Pickers for Grouping and Date Range</li>
                <li class="line-through">Make a decimal to h:m:s Helper.</li>
                <li>Make sure you're only getting the Events for the current user.</li>
                <li>Make sure that only authenticanted Users can access the Reports page.</li>
                <li class="line-through">You need a Reports Controller.</li>
            </ul>
        </div>

        <ul class="list-disc py-3">
            <li>By Project</li>
            <li>By Task</li>
        </ul>


        <ul class="list-disc py-3">
            <li>This week</li>
            <li>This month</li>
            <li>Year to date</li>
            <li>All time</li>
        </ul>

    </div>

@endsection
