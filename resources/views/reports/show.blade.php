@extends('layouts.app')

@section('content')


    <div>
        <h1>Navigation goes Here</h1>
        <ul class="lg:flex items-center justify-between text-base text-gray-700 pt-4 lg:pt-0">
            <li><a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400"
                    href="#">This week</a></li>
            <li>This month</li>
            <li>Year to date</li>
            <li>All time</li>
        </ul>
    </div>


    <div class="mt-20">

        <h1 class="text-2xl">Reports...</h1>

        {{-- {{ dump($events) }} --}}
        {{-- {{ dd($group) }} --}}

        <hr>

        <table class="table-fixed font-mono">
            @foreach ($events as $firstLevel => $secondLevel)
                <thead>
                    <tr class="text-left border-b-2 border-black">
                        <th colspan="2">{{ $group[0] }}: {{ $firstLevel }}</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="border-b border-black">
                        <th class="w-1/2 text-left">{{ $group[1] }}</th>
                        <th class="w-1/2 text-right">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($secondLevel as $secondLevelElement => $descriptions)
                        <tr>
                            <td>{{ $secondLevelElement }}</td>
                            <td class="text-right">
                                {{ sprintf('%01.2f', round($descriptions->sum('duration') / (60 * 60), 2)) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-200">
                        <td>{{ $group[1] }} Total:</td>
                        <td class="text-right">
                            {{ sprintf(
    '%01.2f',
    round(
        $secondLevel->pluck('*.duration')->flatten()->sum() / 3600,
        2,
    ),
) }}
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                    </tr>
                </tbody>

            @endforeach
            <tr class="bg-gray-300">
                <td>Grand Total</td>
                <td class="text-right">
                    {{-- {{ vsprintf('%2d:%02d:%02d', decimalToHms(round($total / 3600, 2))) }} --}}
                    {{ sprintf('%01.2f', round($total / 3600, 2)) }}
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
            <li>By firstLevel</li>
            <li>By Task</li>
        </ul>


        <ul class="list-disc py-3">
            <li>This week</li>
            <li>This month</li>
            <li>Year to date</li>
            <li>All time</li>
        </ul>

    </div>

@endsection
