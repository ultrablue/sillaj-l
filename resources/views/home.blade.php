@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                </div>
                <div class="card-body">
                    <p>You are logged in!</p>
                </div>
                <div class="card-body">
                    @if ($events->count() === 0)
                    <div class="alert alert-warning">
                        You don't have any events, yet.
                    </div>
                @else
                    <div class="card-text">
                        <ul class="list-group list-group-flush">
                        @foreach ($events as $event)
                            <li class="list-group-item">{{ $event->note }}</li>
                   @endforeach
                        </ul>
                    </div>
                @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
