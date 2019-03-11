<form style="padding-top:1em; padding-bottom:3em;">
    <div class="form-group" style="">
        <label for="project">Project</label>
        <select class="form-control form-control-sm" id="project" name="project">
            <option>IT</option>
            <option>IT - Some Task</option>
            <option>IT - Another Task</option>
            <option>IT - This is your life now dude learn to live with it or don't whatever.</option>
            <option>5</option>
        </select>
    </div>


    <div class="form-group input-sm" style="">
        <label for="task">Task</label>
        <select class="form-control form-control-sm" id="task" name="task">
            <option>Email, News, Etc.</option>
            <option>Meeting</option>
            <option>Coding</option>
            <option>Leave, Annual</option>
            <option>Leave, Sick</option>
        </select>
    </div>


    <div class="form-group">
        <label for="duration">Duration</label>
        <input type="time" class="form-control form-control-sm" id="duration" name="duration" aria-describedby="duration" placeholder="Duration">
    </div>

    <div class="form-group">
        <label for="start">Start Time</label>
        <input type="time" class="form-control form-control-sm" id="start" name="start" aria-describedby="start" placeholder="Start Time">
    </div>

    <div class="form-group">
        <label for="end">End Time</label>
        <input type="time" class="form-control form-control-sm" id="end" name="end" aria-describedby="end" placeholder="End Time">
    </div>


    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input id="email" type="text" class="form-control form-control-sm" name="email" placeholder="Email">
    </div>


    <div class="form-row align-items-center">
        <div class="col-sm-5 my-1">
            <label class="sr-only" for="inlineFormInputName">Name</label>
            <input type="text" class="form-control" id="inlineFormInputName" placeholder="Jane Doe">
        </div>
    </div>

</form>
