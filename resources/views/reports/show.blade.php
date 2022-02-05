@extends('layouts.app')

@section('content')


    <div class="mt-20">

        <h1 class="text-2xl">{{ $group[1] }} by {{ $group[0] }}</h1>
        <h2>{{ $dates[0]->format('l F jS, Y') }} through {{ $dates[1]->format('l F jS, Y') }}</h2>

        <hr>

        <table class="table-auto font-mono mt-10">
            @php
                $currentProject = null;
            @endphp
            @foreach ($events as $i => $row)
                @if ($currentProject !== $row->project && $row->project != null)
                    @php
                        $currentProject = $row->project;
                    @endphp
                    <tr class="border-b-2 border-gray-500">
                        <th colspan="3" class="pt-5 text-left">Project: {{ $row->project }}</th>
                    </tr>
                @endif


                @if ($row->task && $row->project)
                    {{-- Task Row --}}
                    <tr>
                        <td colspan="1" class="w-1/2 pl-5"><span class="">{{ $row->task }}</span></td>
                        <td class="text-right">{{ round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2) }}</td>
                            {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                        <td class="pl-5 text-right">{{ round(100 * ($row->duration / $events->last()->duration)) }}%</td>
                    </tr>
                @elseif (!$row->project && !$row->task)
                    {{-- Grand Total --}}
                    <tr class="bg-blue-300 border-t border-blue-900">
                        <td colspan="2">Grand Total</td>
                        <td class="text-right">{{ round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2) }}</td>
                            {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                    </tr>
                @elseif ($row->project && !$row->task)
                    {{-- Level 1 Total --}}
                    <tr class="bg-blue-200 pb-5">
                        <td colspan="1" class="pl-5">{{ $currentProject }} Total</td>
                        <td class="text-right">{{ round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2) }}</td>
                            {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                        <td class="pl-5 text-right">{{ round(100 * ($row->duration / $events->last()->duration)) }}%</td>
                    </tr>
                @endif

            @endforeach

        </table>


    @endsection
