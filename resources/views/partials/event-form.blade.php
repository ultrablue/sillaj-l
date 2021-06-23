<div class="border-gray-300 border w-1/2 m-2 text-xs">

    <div class="flex px-3">
        <div class="mb-4 mr-2">
            <h1>Create or Edit an Event</h1>
        </div>
    </div>

    @if(isset($event))
        {{ Form::model($event, ['action' => ['EventController@update', $event->id], 'method'=>'put']) }}
    @else
        {{ Form::open(['action' => 'EventController@store']) }}
    @endif

    <div class="flex px-3">
        <div class="mb-4 mr-2">
            {{ Form::label('event_date', 'Date', ['class' => 'block text-gray-700 text-sm']) }}
            {{ Form::text('event_date', session('eventdate'), ['class' => 'text-field focus:outline-none focus:shadow-outline', 'placeholder' => 'yyyy-mm-dd']) }}
        </div>

        <div class="mb-4 mr-2">
            {{ Form::label('time_start', 'Start', ['class' => 'block text-gray-700 text-sm']) }}
            {{ Form::text('time_start', null, ['class' => 'text-field focus:outline-none focus:shadow-outline', 'placeholder' => '24:00', 'size' => '5']) }}
        </div>

        <div class="mb-4 mr-2">
            {{ Form::label('time_end', 'End', ['class' => 'block text-gray-700 text-sm']) }}
            {{ Form::text('time_end', null, ['class' => 'text-field focus:outline-none focus:shadow-outline', 'placeholder' => '24:00', 'size' => '5']) }}
        </div>

        <div class="mb-4">
            {{ Form::label('duration', 'Duration', ['class' => 'block text-gray-700 text-sm']) }}
            {{ Form::text('duration', null, ['class' => 'text-field focus:outline-none focus:shadow-outline', 'placeholder' => '24:00', 'size' => '5']) }}
        </div>
    </div>


    <div>
        <div class="flex flex-row">

            <div class="px-3">
                {{ Form::label('project_id', 'Project', ['class' => 'block text-gray-700 text-sm']) }}

                <div class="relative">
                    {{ Form::select('project_id',
                        $projects->pluck('name', 'id'),
                        null,
                        ['class' => 'block appearance-none bg-gray-200 border border-gray-200 text-gray-700 py-1 px-1 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500'],
                        [],
                        []
                        ) }}

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <span class="oi" data-glyph="chevron-bottom"></span>
                    </div>
                </div>
            </div>

            <div class="px-3 mb-6 ">
                {{ Form::label('task_id', 'Task', ['class' => 'block text-gray-700 text-sm']) }}

                <div class="relative">
                    {{ Form::select('task_id',
                        $tasks->pluck('name', 'id'),
                        null,
                        ['class' => 'block appearance-none bg-gray-200 border border-gray-200 text-gray-700 py-1 px-1 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500'],
                        [],
                        []
                        ) }}

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <span class="oi" data-glyph="chevron-bottom"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full px-3 mb-6">
        {{ Form::label('note', 'Notes', ['class' => 'block text-gray-700 text-sm']) }}
        {!! Form::textarea('note',null,['class' => 'w-full text-field focus:outline-none focus:shadow-outline', 'rows' => '3']) !!}
    </div>


    <div class="w-full px-3 mb-6 ">
        <div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline focus:bg-red-500" type="submit">
                Save
            </button>
        </div>
    </div>


    {{ Form::close() }}


</div>
