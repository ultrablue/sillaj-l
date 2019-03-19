<form style="padding-top:1em; padding-bottom:3em;" action="/~sillaj-l-01/sillaj-l/public/event" method="post">
        @csrf
    <div class="form-row">
        <div class="col" style="">
            
            <label for="project">Project</label>
            <select class="form-control form-control-sm" id="project" name="project_id">
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->name}}</option>
                @endforeach
            </select>
        </div>


        <div class="col" style="">
            <label for="task">Task</label>
            <select class="form-control form-control-sm" id="task" name="task_id" placeholder="Task">
                @foreach($tasks as $task)
                    <option value="{{$task->id}}">{{$task->name}}</option>
                @endforeach
            
            </select>
        </div>
    </div><!-- /form-row-->

    
    <div class="form-row">
        
        <div class="col-md-3">
            <label for="date">Date</label>
            <input type="date" class="form-control form-control-sm" id="date" name="event_date" aria-describedby="Date" placeholder="Enter date">
        </div>

        <div class="col-md-3">
            <label for="duration">Duration</label>
            <input type="time" class="form-control form-control-sm" id="duration" name="duration" aria-describedby="duration" placeholder="Duration">
        </div>

        <div class="col-md-3">
            <label for="start">Start Time</label>
            <input type="time" class="form-control form-control-sm" id="start" name="time_start" aria-describedby="start" placeholder="Start Time">
        </div>

        <div class="col-md-3">
            <label for="end">End Time</label>
            <input type="time" class="form-control form-control-sm" id="end" name="time_end" aria-describedby="end" placeholder="End Time">
        </div>

    </div><!-- /form-row-->

    <div class="form-row">
        <label for="note">Notes</label>
        <textarea class="form-control" id="note" rows="3" name="note"></textarea>
    </div><!-- /form-row-->

    <div class="form-row">
        <button type="submit" class="btn btn-primary my-2">Submit</button>
    </div><!-- /form-row-->

</form>
