@dd($startTime)



@php
$currentProject = null;
@endphp
@foreach ($events as $i => $row)
    @if ($currentProject !== $row[strtolower($group[0])] && $row[strtolower($group[0])] != null)
        @php
            $currentProject = $row[strtolower($group[0])];
        @endphp
        {{ $group[0] }}: {{ $row[strtolower($group[0])] }}
    @endif


    @if ($row[strtolower($group[0])] && $row[strtolower($group[1])])
        {{-- Level 2 Row --}}
        {{ $row[strtolower($group[1])] }} {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}
        {{ sprintf('%.2f', round(100 * ($row->duration / $events->last()->duration), 2)) }}%
    @elseif (!$row[strtolower($group[0])] && !$row[strtolower($group[1])])
        {{-- Grand Total --}}
        Grand Total {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}
    @elseif ($row[strtolower($group[0])] && !$row[strtolower($group[1])])
        {{-- Level 1 Total --}}
        {{ $currentProject }} Total {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($row->duration)->cascade()->total('hours'),2)) }}
        {{ sprintf('%.2f', round(100 * ($row->duration / $events->last()->duration), 2)) }}%
    @endif

@endforeach




































{{-- @foreach ($events as $firstLevel => $secondLevel)
    {{ $group[0] }}: {{ $firstLevel }}
    {{ $group[1] }}

    @foreach ($secondLevel as $secondLevelElement => $descriptions)
        {{ $secondLevelElement }} {{ sprintf('%01.2f', round($descriptions->sum('duration') / (60 * 60), 2)) }}
    @endforeach

    {{ $group[1] }} Total: {{ sprintf(
    '%01.2f',
    round(
        $secondLevel->pluck('*.duration')->flatten()->sum() / 3600,
        2,
    ),
) }}
@endforeach

Grand Total
{{-- {{ vsprintf('%2d:%02d', decimalToHms(round($total / 3600, 2))) }} --}}
{{-- {{ sprintf('%01.2f', round($total / 3600, 2)) }} --}} --}}
