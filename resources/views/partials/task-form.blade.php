{{--{{ Form::model($task, ['action' => ['TasksController@update', $task->id], 'method' => 'put']) }}--}}


@if(isset ($task))
    {!! Form::model($task, ['action' => ['TasksController@update', $task->id], 'method'=>'put']) !!}
@else
    {{ Form::open(['action' => 'TasksController@store']) }}
@endif



<div class="w-full px-3 mb-6">
    {{ Form::label('name', 'Name', ['class' => 'block text-gray-700 text-sm']) }}
    {{ Form::text('name', null, ['class' => 'text-field focus:outline-none focus:shadow-outline']) }}
</div>

<div class="w-full px-3 mb-6">
    {{ Form::label('description', 'Description:', ['class' => 'block text-gray-700 text-sm']) }}
    {{ Form::textarea('description',null,['class' => 'text-field focus:outline-none focus:shadow-outline', 'rows' => '3']) }}
</div>

<div class="w-full px-3 mb-6">
    {{ Form::label('display', 'Display:', ['class' => 'block text-gray-700 text-sm']) }}
    {{ Form::hidden('display', 0) }}
    {{ Form::checkbox('display',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) }}
</div>

<div class="w-full px-3 mb-6">
    {{ Form::label('share', 'Share:', ['class' => 'block text-gray-700 text-sm']) }}
    {{ Form::hidden('share', 0) }}
    {{ Form::checkbox('share',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) }}
</div>

<div class="w-full px-3 mb-6">
    {{ Form::label('use_in_reports', 'Use In Reports:', ['class' => 'block text-gray-700 text-sm']) }}
    {{ Form::hidden('use_in_reports', 0) }}
    {{ Form::checkbox('use_in_reports',1,null,null,['class' => 'text-field focus:outline-none focus:shadow-outline']) }}
</div>

<div class="w-full px-3 mb-6">
    @foreach ($allProjects as $project)
        {{-- TODO This is probably horribly ineffecient!! --}}
        @if(isset($task))
            @php $checked = in_array($project->id, $task->projects->pluck('id')->toArray()) ? true : false @endphp
        @endif
        {{ Form::checkbox('projects[]',$project->id) }} {{ $project->name }}<br>
    @endforeach
</div>

<div class="w-full px-3 mb-6">
    {{ Form::submit('Save', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline']) }}
</div>


{{ Form::close() }}
