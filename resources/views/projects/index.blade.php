@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Projects</div>

                <div class="card-body">
                    @foreach ($projects as $project)
                    <article>
                        <h4><a href="{{$project->path()}}">{{ $project->name }}</a></h4>
                        <div>{{ $project->description }}</div>
                        <hr />
                    </article>
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
