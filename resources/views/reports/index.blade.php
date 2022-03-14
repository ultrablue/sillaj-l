@extends('layouts.app')

@section('content')

    <h1 class="text-2xl">Reporting</h1>

    <div class="mt-10">

        {!! Form::open(['route' => 'reports-show', 'class' => 'w-full max-w-sm']) !!}
        <fieldset class="border-2 rounded-md border-gray-300 border-solid p-2">
            <legend class="pl-1 pr-5 ">Grouping</legend>
            <div class="">
                {{ Form::radio('group-by', 'project', true, ['id' => 'group-by-project']) }}
                {{ Form::label('group-by-project', 'Project') }}
                <br>
                {{ Form::radio('group-by', 'task', false, ['id' => 'group-by-task']) }}
                {{ Form::label('group-by-task', 'Task') }}

            </div>
        </fieldset>
        <div class="mt-10">
            <fieldset class="border-2 rounded-md border-gray-300 border-solid p-2">
                <legend class="pl-1 pr-5 ">Report</legend>
                {{ Form::radio('predefined-range', 'this-week', true, ['id' => 'predefined-range-this-week']) }}
                {{ Form::label('predefined-range-this-week', 'This week') }}
                <br>

                {{ Form::radio('predefined-range', 'month-to-date', false, ['id' => 'predefined-range-month-to-date']) }}
                {{ Form::label('predefined-range-month-to-date', 'Month to date') }}
                <br>

                {{ Form::radio('predefined-range', 'year-to-date', false, ['id' => 'predefined-range-year-to-date']) }}
                {{ Form::label('predefined-range-year-to-date', 'Year to date') }}
                <br>

                {{ Form::radio('predefined-range', 'all-time', false, ['id' => 'predefined-range-all-time']) }}
                {{ Form::label('predefined-range-all-time', 'All Time') }}
                <br>

                {{ Form::radio('predefined-range','custom',false,['id' => 'predefined-range-custom'],['disabled' => 'disabled']) }}
                {{ Form::label('predefined-range-custom', '(Custom - Not implemented, yet; will display two date pickers)', ['class' => 'text-gray-400']) }}
            </fieldset>


        </div>

        <div class="mt-10">
            <fieldset class="border-2 rounded-md border-gray-300 border-solid p-2">
                {{ Form::label('start_date', 'Start Date', ['class' => 'block text-gray-700 text-sm']) }}
                {{ Form::text('start_date', session('start_date'), ['class' => 'text-field focus:outline-none focus:shadow-outline','placeholder' => 'yyyy-mm-dd']) }}
                {{-- {{ Form::date('start_date', Carbon\Carbon::parse(session('start_date')), ['class' => 'text-field focus:outline-none focus:shadow-outline','placeholder' => 'yyyy-mm-dd']) }} --}}
                <br>
                {{ Form::label('end_date', 'End Date', ['class' => 'block text-gray-700 text-sm']) }}
                {{ Form::text('end_date', session('end_date'), ['class' => 'text-field focus:outline-none focus:shadow-outline','placeholder' => 'yyyy-mm-dd']) }}
            </fieldset>
        </div>


        <div class="mt-10">
            {{ Form::submit('Generate...', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) }}
        </div>
        {!! Form::close() !!}


    @endsection
