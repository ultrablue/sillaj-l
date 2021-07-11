@extends('layouts.app')

@section('content')

    <h1 class="text-2xl">Reporting</h1>

    <div class="mt-10">

        {!! Form::open(['route' => 'reports-show', 'class' => 'w-full max-w-sm']) !!}
        <fieldset class="border-2 rounded-md border-gray-300 border-solid p-2">
            <legend class="pl-1 pr-5 ">Grouping</legend>
            <div class="">
                {{ Form::radio('group-by', 'project', ['id' => 'group-by-project']) }}
                {{ Form::label('group-by-project', 'Project') }}
                <br>
                {{ Form::radio('group-by', 'task', ['id' => 'group-by-task']) }}
                {{ Form::label('group-by-task', 'Task') }}

            </div>
        </fieldset>
        <div class="mt-10">
            <fieldset class="border-2 rounded-md border-gray-300 border-solid p-2">
                <legend class="pl-1 pr-5 ">Grouping</legend>
                {{ Form::radio('predefined-range', 'this-week', ['id' => 'predefined-range-this-week']) }}
                {{ Form::label('predefined-range-this-week', 'This week') }}
                <br>

                {{ Form::radio('predefined-range', 'month-to-date', ['id' => 'predefined-range-month-to-date']) }}
                {{ Form::label('predefined-range-month-to-date', 'Month to date') }}
                <br>

                {{ Form::radio('predefined-range', 'year-to-date', ['id' => 'predefined-range-year-to-date']) }}
                {{ Form::label('predefined-range-year-to-date', 'Year to date') }}
                <br>

                {{ Form::radio('predefined-range', 'all-time', ['id' => 'predefined-range-all-time'], ['disabled' => 'disabled']) }}
                {{ Form::label('predefined-range-all-time', '(All Time - Not implemented, yet)', ['class' => 'text-gray-400']) }}
                <br>

                {{ Form::radio('predefined-range', 'custom', ['id' => 'predefined-range-custom'], ['disabled' => 'disabled']) }}
                {{ Form::label('predefined-range-custom', '(Custom - Not implemented, yet; will display two date pickers)', ['class' => 'text-gray-400']) }}
            </fieldset>


        </div>
        <div class="mt-10">
            {{ Form::submit('Generate...', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) }}
        </div>
        {!! Form::close() !!}
        <hr class="p-0 mt-10">

        <div class="py-5">
            <ul class="list-disc py-3">
                <li class="line-through">Add the current grouping to the report header.</li>
                <li class="line-through">Add the current date range (as two dates) to the report header.</li>
                <li class="line-through">Add layout</li>
                <li class="line-through">Make Table layout for report</li>
                <li><span class="line-through">Add Pickers for Grouping</span> and Date Range</li>
                <li class="line-through">Make a decimal to h:m:s Helper.</li>
                <li class="line-through">Make sure you're only getting the Events for the current user.</li>
                <li class="line-through">Make sure that only authenticanted Users can access the Reports page.</li>
                <li class="line-through">You need a Reports Controller.</li>
            </ul>
        </div>

        <ul class="list-disc py-3">
            <li class="line-through">By Project</li>
            <li class="line-through">By Task</li>
        </ul>


        <ul class="list-disc py-3">
            <li class="line-through">This week (all week)</li>
            <li class="line-through">This month (month to date)</li>
            <li>This month (all month) (won't implement)</li>
            <li class="line-through">Year to date</li>
            <li>Year to date (all year) (won't implement)</li>
            <li>All time</li>
            <li>Custom - two date pickers appear</li>
        </ul>

    </div>

@endsection
