@extends('layouts.app')

@section('content')







    <h1>Reports...</h1>

    {{-- {{ dump($events) }} --}}

    <hr>

    <table class="table-auto border border-black">
        @foreach ($events as $project => $tasks)
            <thead>
                <tr class="border bg-yellow-300 ">
                    <th colspan="2">{{ $project }}</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th class="w-1/2">Task</th>
                    <th class="w-1/2">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task => $descriptions)
                    <tr>
                        <td>{{ $task }}</td>
                        <td class="text-right">{{ round($descriptions->sum('duration') / (60 * 60), 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="border-gray-500 border bg-blue-400">Project Total:</td>
                    <td class="text-right">{{ round(
    $tasks->pluck('*.duration')->flatten()->sum() / 3600,
    2,
) }}
                    </td>
                </tr>
            </tbody>

        @endforeach
        <tr>
            <td>Total:</td>
            <td>{{ vsprintf('%2d:%02d:%02d', decimalToHms(round($total / 3600, 2))) }}
                {{ round($total / 3600, 2) }}
            </td>
        </tr>

    </table>


    <hr class="p-0">

    <div class="py-5">
        <ul class="list-disc py-3">
            <li class="line-through">Add layout</li>
            <li>Make Table layout for report</li>
            <li>Add Pickers for Grouping and Date Range</li>
            <li class="line-through">Make a decimal to h:m:s Helper.</li>
            <li>Make sure you're only getting the Events for the current user.</li>
            <li>Make sure that only authenticanted Users can access the Reports page.</li>
            <li class="line-through">You need a Reports Controller.</li>
        </ul>
    </div>

    <ul class="list-disc py-3">
        <li>By Project</li>
        <li>By Task</li>
    </ul>


    <ul class="list-disc py-3">
        <li>This week</li>
        <li>This month</li>
        <li>Year to date</li>
        <li>All time</li>
    </ul>



@endsection
