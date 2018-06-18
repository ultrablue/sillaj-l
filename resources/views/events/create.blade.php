@extends('layouts.app')

@section('content')
    <div class="container">
        <form>
            <div class="form-group">
                <label for="exampleInputEmail1">Date</label>
                <input type="date" class="form-control" id="date" aria-describedby="Date" placeholder="Enter date">

                <small id="emailHelp" class="form-text text-muted">Please Enter a date.</small>
            </div>

            <div class="form-group">
                <label for="projectList">Project</label>
                <select class="form-control" id="projecList">
                @foreach($projects as $project)
                    <option>{{$project->name}}</option>
                @endforeach
                </select>
            </div>



            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


@endsection
