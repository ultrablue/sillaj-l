@foreach ($events as $firstLevel => $secondLevel)
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
{{ sprintf('%01.2f', round($total / 3600, 2)) }}
