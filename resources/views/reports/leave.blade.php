@extends('layouts.app')

@section('content')
    <div class="mt-20">

        {{-- <h1 class="text-2xl">{{ $group[1] }} by {{ $group[0] }}</h1> --}}
        <h2>{{ $dates['start']->format('l F jS, Y') }} through {{ $dates['end']->format('l F jS, Y') }}</h2>

        <hr>

        <table class="table-auto font-mono mt-10 border border-1">
            @foreach ($events as $i => $rows)
                <tr>
                    <td colspan="4" class="pl-5 border">{{ $i }}</td>
                </tr>
                @foreach ($rows as $row)
                    <tr class="border">
                        <td class="pl-5">{{ $row->event_date->format('l F jS') }}
                        <td class="pl-5">{{ $row->time_start_timestamp->format('H:i') }}</td>
                        <td class="pl-5">{{ $row->time_end_timestamp->format('H:i') }}</td>
                        <td class="pl-5 text-right">{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                    </tr>
                @endforeach
                <tr class="bg-green-100">
                    <td class="pl-5" colspan="3">Sum:</td>
                    <td class="pl-5 text-right">{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($rows->sum('duration'))->cascade()->total('hours'),2)) }}</td>
                </tr>
            @endforeach
        </table>
    @endsection
