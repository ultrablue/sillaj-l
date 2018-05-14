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

                <div class="card-body">
                    <h5 class="card-title">Add a Project</h5>
                    <div class="card-text">
                    <form method="POST" action="{{ $project->path() . '/tasks' }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>


            </div> {{-- card --}}
        </div> {{-- col-md-8 --}}
    </div> {{-- row justify-content-center --}}
</div> {{-- container --}}
@endsection
 
