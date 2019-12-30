@extends('layouts.app')

@section('content')

    <div class="bg-auto px-3 text-xs my-6 rounded border">
        <h1 class="text-lg">My Projects</h1>


        <div class="table w-full table-auto">
            <div class="table-row font-bold">
                <div class="table-cell">ID</div>
                <div class="table-cell">Name</div>
                <div class="table-cell">Description</div>
            </div>

            @if ($projects->isNotEmpty())
                @foreach ($projects as $project)

                    <div class="table-row @if(Session::get('project_id') == $project->id)bg-green-200 @endif" data-project-id="{{$project->id}}">
                        <div class="table-cell border-b-2"><a href="#">{{$project->id}}</a></div>
                        <div class="table-cell border-b-2">
                            <a href="{{route('project-show', ['project' => $project->id])}}">{{$project->name}} @if ( $project->share ===1 )*@endif</a>
                        </div>
                        <div class="table-cell border-b-2">{{$project->description}}</div>
                    </div>

                @endforeach

            @else
                <h5>You don't have any projects, yet.</h5>
            @endif

        </div>
    </div>

    @if (count($otherSharedProjects) >= 1)
        <div class="bg-auto px-3 text-xs my-6 rounded border">
            <h1 class="text-lg">Other Shared Projects</h1>


            <div class="table w-full table-auto">
                <div class="table-row font-bold">
                    <div class="table-cell">Name</div>
                    <div class="table-cell">Description</div>
                </div>


                @foreach ($otherSharedProjects as $sharedProject)
                    <div class="table-row" data-project-id="{{$sharedProject->id}}">
                        <div class="table-cell border-b-2">{{ $sharedProject->name }}</div>
                        <div class="table-cell border-b-2">{{ $sharedProject->description }}</div>
                    </div>
                @endforeach
                @endif

            </div>
        </div>
@endsection
 
