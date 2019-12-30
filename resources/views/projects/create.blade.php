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

    @include('partials.project-form')

@endsection
 
