<form style="padding-top:1em; padding-bottom:3em;">
    <div class="form-row">
        <div class="col" style="">
            <label for="project">Project</label>
            <select class="form-control form-control-sm" id="project" name="project">
                <option>IT</option>
                <option>IT - Some Task</option>
                <option>IT - Another Task</option>
                <option>IT - This is your life now dude learn to live with it or don't whatever.</option>
                <option>5</option>
            </select>
        </div>


        <div class="col" style="">
            <label for="task">Task</label>
            <select class="form-control form-control-sm" id="task" name="task" placeholder="Task">
                <option>Email, News, Etc.</option>
                <option>Meeting</option>
                <option>Coding</option>
                <option>Leave, Annual</option>
                <option>Leave, Sick</option>
            </select>
        </div>
    </div><!-- /form-row-->

    
    <div class="form-row">

        <div class="col-md-4">
            <label for="duration">Duration</label>
            <input type="time" class="form-control form-control-sm" id="duration" name="duration" aria-describedby="duration" placeholder="Duration">
        </div>

        <div class="col-md-4">
            <label for="start">Start Time</label>
            <input type="time" class="form-control form-control-sm" id="start" name="start" aria-describedby="start" placeholder="Start Time">
        </div>

        <div class="col-md-4">
            <label for="end">End Time</label>
            <input type="time" class="form-control form-control-sm" id="end" name="end" aria-describedby="end" placeholder="End Time">
        </div>

    </div><!-- /form-row-->

    <div class="form-row">
        <label for="description">Notes</label>
        <textarea class="form-control" id="description" rows="3"></textarea>
    </div><!-- /form-row-->

    <div class="form-row">
        <button type="submit" class="btn btn-primary my-2">Confirm identity</button>
    </div><!-- /form-row-->

</form>
