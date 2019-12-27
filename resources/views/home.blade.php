@extends('layouts.app')

@section('content')

    <div class="flex w-auto border">
        @include('partials.calendar-nav')
        @include('partials.event-form')

    </div>

    <div class="">
        @include('partials.events-list')
    </div>


@endsection
