@extends('layouts.app')

@section('content')

    <div class="flex w-auto border">
        @include('partials.calendar-nav')
        @include('partials.event-form')

    </div>

    <div class="">
        @if($thisDaysEvents->isEmpty())
            <h1>You don't have any Events, yet.</h1>
        @else
            @include('partials.events-list')
        @endif
    </div>


@endsection
