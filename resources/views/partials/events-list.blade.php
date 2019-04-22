<div class="card border-secondary">
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
                </tr>
            </thead>
            <tbody>
                @foreach($thisDaysEvents as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{$event->task->name}}</td>
                        <td>{{$event->project->name}}</td>
                        <td>{{$event->start()->format('H:i')}}</td>
                        <td>{{$event->end()->format('H:i')}}</td>
                        <td>{{$event->duration}}</td>
                        <td>{{$event->note}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
