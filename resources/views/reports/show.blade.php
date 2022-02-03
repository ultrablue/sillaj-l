@extends('layouts.app')

@section('content')


    {{-- <div class="mt-20 mb-20">
        {{ dump($events) }}
    </div> --}}



    <div class="mt-20">

        <h1 class="text-2xl">{{ $group[1] }} by {{ $group[0] }}</h1>
        <h2>{{ $dates[0]->format('l F jS, Y') }} through {{ $dates[1]->format('l F jS, Y') }}</h2>

        {{-- {{ dump($events) }} --}}
        {{-- {{ dd($group) }} --}}

        <hr>

        <table class="table-auto font-mono mt-10">
            {{-- <thead>
                <tr class="border-b border-black">
                    <th class="w-1/2 text-left">Project</th>
                    <th class="w-1/4 text-left">Task</th>
                    <th class="w-1/4 text-right">Time</th>
                </tr>
            </thead> --}}
            @php
                $currentRow = null;
            @endphp
            @foreach ($events as $row)
                @if ($currentRow !== $row->project)
                    <tr class="border-b-2 border-black">
                        <td colspan="3">Project: {{ $row->project }}</td>
                    </tr>
                    @php
                        $currentRow = $row->project;
                    @endphp
                @endif


                <tbody>
                    @if ($row->task)
                        <tr>
                            <td colspan="2" class="w-1/2 pl-5"><span class="">{{ $row->task }}</span></td>
                            <td class="text-right">{{ $row->duration }}</td>
                        </tr>
                    @elseif (!$row->project && !$row->task)
                        <tr>
                            <td colspan="2">Grand Total</td>
                            <td class="text-right">{{ $row->duration }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2" class="pl-10 pb-5">Total</td>
                            <td class="text-right pb-5">{{ $row->duration }}</td>
                        </tr>
                    @endif
                </tbody>

            @endforeach

        </table>


    @endsection
