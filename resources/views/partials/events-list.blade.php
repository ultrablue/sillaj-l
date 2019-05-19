<div class="card border-secondary mt-3">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Task ID</th>
                <th scope="col">Project ID</th>
                <th scope="col">Start</th>
                <th scope="col">End</th>
                <th scope="col">Duration</th>
                <th scope="col">Note</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($thisDaysEvents as $event)
                <tr row_id="{{$event->id}}">
                    <td>{{$event->id}}</td>
                    <td>{{$event->task->name}}</td>
                    <td>{{$event->project->name}}</td>
                    <td>
                        <div class="row_data" edit_type="click"
                             col_name="start">{{$event->start()->format('H:i')}}</div>
                    </td>
                    <td>
                        <div class="row_data" edit_type="click" col_name="end">{{$event->end()->format('H:i')}}</div>
                    </td>
                    <td>{{$event->duration}}</td>
                    <td>
                        <div class="row_data" edit_type="click" col_name="note">{{$event->note}}</div>
                    </td>
                    <td>
                        <span class="btn_edit"><a href="#" class="btn btn-link" row_id="{{$event->id}}"><span class="oi oi-pencil" title="Edit" aria-hidden="true"></span></a></span>

                        <span class="btn_save"><a href="#" class="btn btn-link" row_id="{{$event->id}}"><span class="oi oi-circle-check text-success" title="Save" aria-hidden="true"></span></a></span>
                        <span class="btn_cancel"><a href="#" class="btn btn-link" row_id="{{$event->id}}"><span class="oi oi-circle-x text-danger" title="Cancel" aria-hidden="true"></span></a></span>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
