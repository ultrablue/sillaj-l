{{-- <x-email.report/> --}}



<h1>{{ $group[1] }} by {{ $group[0] }}</h1>
<h2>{{ $startTime->format('l F jS, Y') }} through {{ $startTime->format('l F jS, Y') }}</h2>

<hr>

<table>
    @php
        $currentProject = null;
    @endphp
    @foreach ($events as $i => $row)
        @if ($currentProject !== $row[strtolower($group[0])] && $row[strtolower($group[0])] != null)
            @php
                $currentProject = $row[strtolower($group[0])];
            @endphp
            <tr>
                <th colspan="3">{{ $group[0] }}: {{ $row[strtolower($group[0])] }}</th>
            </tr>
        @endif


        @if ($row[strtolower($group[0])] && $row[strtolower($group[1])])
            {{-- Level 2 Row --}}
            <tr>
                <td colspan="1">{{ $row[strtolower($group[1])] }}</td>
                <td>{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                <td>{{ sprintf('%.0f%%', (100 * ($row->duration / $events->last()->duration))) }}</td>
            </tr>
        @elseif (!$row[strtolower($group[0])] && !$row[strtolower($group[1])])
            {{-- Grand Total --}}
            <tr>
                <td colspan="1">Grand Total</td>
                <td>{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                <td></td>
            </tr>
        @elseif ($row[strtolower($group[0])] && !$row[strtolower($group[1])])
            {{-- Level 1 Total --}}
            <tr>
                <td colspan="1">{{ $currentProject }} Total</td>
                <td>{{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}</td>
                {{-- {{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%h:%I') }} --}}
                <td>{{ sprintf('%.0f%%', (100 * ($row->duration / $events->last()->duration))) }}</td>
            </tr>
        @endif

    @endforeach

</table>
