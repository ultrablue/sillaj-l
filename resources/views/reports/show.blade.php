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
                @if ($currentProject !== $row[strtolower($group[0])] && $row[strtolower($group[0])] != null)
                    @php
                        $currentProject = $row[strtolower($group[0])];
                    @endphp
                    <tr class="border-b-2 border-gray-500">
                        <th colspan="3" class="pt-5 text-left">{{ $group[0] }}: {{ $row[strtolower($group[0])] }}</th>
                    </tr>
                @endif


                @if ($row[strtolower($group[0])] && $row[strtolower($group[1])])
                    {{-- Level 2 Row --}}
                    <tr>
                        <td colspan="1" class="w-1/2 pl-5"><span class="">{{ $row[strtolower($group[1])] }}</span></td>
                        <td class="text-right">{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                        {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                        <td class="pl-5 text-right">{{ sprintf('%.2f', round(100 * ($row->duration / $events->last()->duration), 2)) }}%</td>
                    </tr>
                @elseif (!$row[strtolower($group[0])] && !$row[strtolower($group[1])])
                    {{-- Grand Total --}}
                    <tr class="bg-blue-300 border-t border-blue-900 text-2xl">
                        <td colspan="1">Grand Total</td>
                        <td class="text-right">{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                        {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                        <td></td>
                    </tr>
                @elseif ($row[strtolower($group[0])] && !$row[strtolower($group[1])])
                    {{-- Level 1 Total --}}
                    <tr class="bg-blue-800 pb-5 font-bold text-zinc-300 text-lg">
                        <td colspan="1" class="pl-12">{{ $currentProject }} Total</td>
                        <td class="text-right">{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                        {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                        <td class="pl-5 text-right">{{ sprintf('%.2f', round(100 * ($row->duration / $events->last()->duration), 2)) }}%</td>
                    </tr>
                    <tr class="bg-sky-300 font-bold text-lg">
                        <td class="pl-12">{{ $currentProject }} All Time Total</td>
                        <td class="text-right">
                            {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($projectTotals[$currentProject]->total_duration)->cascade()->total('hours'),2)) }}
                        </td>
                        <td></td>
                    </tr>
                @endif
            @endforeach

        </table>

        {{-- @dump($projectTotals) --}}
    @endsection
