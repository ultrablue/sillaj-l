


@extends('layouts.app')

@section('content')







<h1>Yo</h1>

{{-- {{ dump($events) }} --}}

<hr>

<ul>
@foreach ($events as $project => $tasks)
    <li>{{ $project }}
        <ul>
    @foreach ($tasks as $task => $descriptions)
        <li>{{ $task }} - {{ round($descriptions->sum('duration')/(60 * 60),2) }}    
            {{-- {{ $descriptions->sum('duration') }} --}}
            @endforeach
            <li>Project Total: {{ round($tasks->pluck("*.duration")->flatten()->sum()/(3600),2) }}</li>
</ul>
    </li>
@endforeach
</ul>

Total:  {{  vsprintf ('%2d:%02d:%02d', decimalToHms( round($total/(3600),2) ) ) }}   {{   round($total/(3600),2) }}

<hr>

<div>
    <ul>
        <li>Add layout</li>
        <li>Make Table layout for report</li>
        <li>Add Pickers for Grouping and Date Range</li>
        <li>Make a decimal to h:m:s Helper.</li>
        <li>Make sure you're only getting the Events for the current user.</li>
        <li>Make sure that only authenticanted Users can access the Reports page.</li>
        <li>You need a Reports Controller.</li>
    </ul>
</div>

<ul>
    <li>By Project</li>
    <li>By Task</li>
</ul>


<ul>
    <li>This week</li>
    <li>This month</li>
    <li>Year to date</li>
    <li>All time</li>
</ul>



@endsection

