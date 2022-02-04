@extends('layouts.app')

@section('content')


    <div class="mt-20">

        <h1 class="text-2xl">{{ $group[1] }} by {{ $group[0] }}</h1>
        <h2>{{ $dates[0]->format('l F jS, Y') }} through {{ $dates[1]->format('l F jS, Y') }}</h2>

        <hr>

        <table class="table-auto font-mono mt-10">
            @php
                $currentRow = null;
            @endphp
            @foreach ($events as $row)
                @if ($currentRow !== $row->project)
                    <tr class="border-b-2 border-black">
                        <td colspan="3" class="pt-5">Project: {{ $row->project }}</td>
                    </tr>
                    @php
                        $currentRow = $row->project;
                    @endphp
                @endif


                <tbody>
                    @if ($row->task)
                        <tr>
                            <td colspan="2" class="w-1/2 pl-5"><span class="">{{ $row->task }}</span></td>
                            <td class="text-right">{{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I')}}</td>
                        </tr>
                    @elseif (!$row->project && !$row->task)
                        <tr class="bg-gray-400">
                            <td colspan="2">Grand Total</td>
                            <td class="text-right">{{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I')}}</td>
                        </tr>
                    @else
                        <tr class="bg-gray-200 pb-5">
                            <td colspan="2" class="pl-5">Total</td>
                            <td class="text-right">{{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I')}}</td>
                        </tr>
                    @endif
                </tbody>

            @endforeach

        </table>


    @endsection
