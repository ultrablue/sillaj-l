@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="/~sillaj-l-01/sillaj-l/public/event" method="post">
        @csrf
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="event_date" aria-describedby="Date" placeholder="Enter date">

                <small id="dateHelp" class="form-text text-muted">Please Enter a date.</small>
            </div>

            <div class="form-group">
                <label for="projectList">Project</label>
                <select class="form-control" id="projecList" name="project_id">
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->name}}</option>
                @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="taskList">Task</label>
                <select class="form-control" id="taskList" name="task_id">
                @foreach($tasks as $task)
                    <option value="{{$task->id}}">{{$task->name}}</option>
                @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="">Duration</label>
                <input type="text" class="form-control" id="duration" name="duration" aria-describedby="Duration" placeholder="Enter duration">

                <small id="durationHelp" class="form-text text-muted">Please Enter a duration.</small>
            </div>

            <div class="form-group">
                <label for="startTime">Start Time</label>
                <input type="time" class="form-control" id="startTime" aria-describedby="startTime" name="time_start" placeholder="Enter the start time.">
                <small id="startTimeHelp" class="form-text text-muted">Please Enter the start time.</small>
            </div>


            <div class="form-group">
                <label for="endTime">End Time</label>
                <input type="time" class="form-control" id="endTime" name="time_end" aria-describedby="endTime" placeholder="Enter the end time.">
                <small id="endTimeHelp" class="form-text text-muted">Please Enter the end time.</small>
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


@endsection
