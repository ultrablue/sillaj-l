{{-- <x-email.report/> --}}


<table style="width:auto; margin:3em; border-collapse: collapse; font-family: Verdana, Geneva, Tahoma, sans-serif;">
    <thead>
        <tr>
            <th colspan="2" style="font-size:1.5em;">
                {{-- Hours for the week of {{ Carbon::now()->startOfWeek()->format('F j, Y') }} --}}
                Hours for {{ Carbon::now()->format('l F j, Y') }}
                {{-- This should be the period requested (day, week, month, year, etc.) --}}
            </th>
        </tr>
    </thead>
    @foreach ($events as $firstLevel => $secondLevel)
        <tr>
            <td style="border:0px solid black; padding-top:1em; padding-bottom:.5em; background-color: white; text-align:left; vertical-align: top; font-weight: bold; font-size:1.25em;" colspan="2">
                {{ $group[0] }}:
                {{ $firstLevel }}</td>
        </tr>
        <tr>
            <td colspan="1" style=" padding-left: 1em; font-weight: bold; padding-bottom:.5em;"> {{ $group[1] }}</td>
            <td style="font-weight: bold; padding-bottom:.5em;">Time</td>
        </tr>
        @foreach ($secondLevel as $secondLevelElement => $descriptions)
            <tr>
                <td style="padding-left: 1.75em;">{{ $secondLevelElement }}</td>
                <td>{{ sprintf('%01.2f', round($descriptions->sum('duration') / (60 * 60), 2)) }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="padding-top:.5em; padding-bottom: .75em;  margin-bottom: 2em; padding-left: 1em; border-bottom:2px solid black; border-top:1px solid black; ">
                {{ $group[0] }} Total:
            </td>
            <td style="padding-top:.5em; padding-bottom: .75em; margin-bottom: 0em; border-bottom:2px solid black;  border-top:1px solid black; border-collapse: collapse">
                {{ sprintf(
    '%01.2f',
    round(
        $secondLevel->pluck('*.duration')->flatten()->sum() / 3600,
        2,
    ),
) }}
                {{-- Urg, this is lame. It calculates (😥) and prints prints the percentage of time spent on the Task or Project. It needs to be cleaned up. Badly. --}}
                {{ sprintf(
    '(%01.0f%%)',
    (round(
        $secondLevel->pluck('*.duration')->flatten()->sum() / 3600,
        2,
    ) /
        round($total / 3600, 2)) *
        100,
) }}
            </td>
        </tr>
    @endforeach
    <tr style="font-size: 1.25em; background-color: lightgreen;">
        <td style="padding-top: .5em; padding-bottom: .5em;  padding-left:.5em;">Grand Total</td>
        {{-- {{ vsprintf('%2d:%02d', decimalToHms(round($total / 3600, 2))) }} --}}
        <td style="padding-top: .5em;  padding-bottom: .5em;">{{ sprintf('%01.2f', round($total / 3600, 2)) }}</td>
    </tr>
</table>
