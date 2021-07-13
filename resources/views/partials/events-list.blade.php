<div class="bg-auto px-3 text-xs border my-6 rounded">
    <h1 class="text-lg">Events List</h1>


    <div class="table w-full table-auto">
        <div class="table-row font-bold">
            <!-- <div class="table-cell">ID</div> -->
            <div class="table-cell">Start</div>
            <div class="table-cell">End</div>
            <div class="table-cell">Duration</div>
            <div class="table-cell">Project</div>
            <div class="table-cell">Task</div>
            <div class="table-cell">Description</div>
            <div class="table-cell">Delete</div>
        </div>


        @foreach ($thisDaysEvents as $event)
            <div class="table-row" class="cursor-pointer" data-event-id="{{ $event->id }}">
                <!-- <div class="table-cell border-b-2 cursor-pointer">{{ $event->id }}</div> -->
                <div class="table-cell  border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    @isset($event->time_start)
                        {{ $event->start()->format('H:i') }}
                    @endisset
                </div>
                <div class="table-cell border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    @isset($event->time_end)
                        {{ $event->end()->format('H:i') }}
                    @endisset
                </div>
                <div class="table-cell border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    {{ $event->iso_8601_duration->format('%H:%I') }}
                </div>
                <div class="table-cell border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    {{ $event->project->name ?? 'No Project!' }}</div>
                <div class="table-cell border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    {{ $event->task->name ?? 'No Task!' }}</div>
                <div class="table-cell border-b-2 cursor-pointer" onclick="window.location='{{ route('event-show', $event->id) }}';">
                    {{ str_limit($event->note, 30) }}</div>
                <div class="table-cell border-b-2">
                    {!! Form::open(['route' => ['event-delete', $event->id], 'method' => 'delete', 'onClick' => 'confirmEventDelete(event)', 'id' => $event->id]) !!}
                    {!! Form::submit('x', ['class' => 'text-red-700 text-xs px-1 py-1 my-1 rounded']) !!}
                    {!! Form::close() !!}

                    {{-- <span class="oi text-red-700" data-glyph="circle-x"></span> --}}
                </div>
            </div>

        @endforeach


        <div class="table-row font-bold">
            <!-- <div class="table-cell">&nbsp;</div> -->
            <div class="table-cell"></div>
            <div class="table-cell"></div>
            <div class="table-cell">{{ $totalDuration->cascade()->format('%H:%I') }}</div>
            <div class="table-cell"></div>
            <div class="table-cell"></div>
            <div class="table-cell"></div>
            <div class="table-cell"></div>
        </div>


    </div>


</div>
