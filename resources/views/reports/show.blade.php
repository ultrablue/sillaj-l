@extends('layouts.app')

@section('content')



    <div class="mt-20">

        <h1 class="text-2xl">{{ $group[1] }} by {{ $group[0] }}</h1>
        <h2>{{ $dates[0]->format('l F jS, Y') }} through {{ $dates[1]->format('l F jS, Y') }}</h2>

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


    @endsection
