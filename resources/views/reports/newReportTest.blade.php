@extends('layouts.app')

@section('content')
   <div class="mt-20">

      <h1 class="text-4xl mb-8">Report</h1>

      <h1 class="text-2xl">{{ ucfirst($group[1]) }} by {{ ucfirst($group[0]) }}</h1>
      <h2>{{ $dates['start-date']->format('l, F jS, Y') }} through {{ $dates['end-date']->format('l, F jS, Y') }}</h2>




      {{-- @dump($group) --}}
      <div>
         @foreach ($groupedEvents as $firstLevel => $secondLevel)
            <div class="mt-10 font-bold text-2xl font-mono">
               {{ ucfirst($group[0]) }}: {{ $firstLevel }}
            </div>
            <div class="pl-2 italic">
               <span class="font-semibold">{{ $firstLevel }}</span> all-time duration: {{ secondsToHm($projectTotals[$firstLevel]->total_duration) }}
            </div>

            <div class="mb-10">
               @foreach ($secondLevel as $secondLevelName => $events)
                  <div class="pl-8 bg-orange-300">{{ ucfirst($group[1]) }}: {{ $secondLevelName }}</div>
                  {{-- <div>Sum: {{ $events->sum('duration') }}</div> --}}
                  <div>Percentage: {{ round(($events->sum('duration') / $ungroupedEvents->sum('duration')) * 100) }}%</div>
                  <div>Duration (decimal): {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($events->sum('duration'))->cascade()->total('hours'),2)) }}</div>
                  <div>Duration (hh:mm): {{ secondstoHm($events->sum('duration')) }}</div>
                  <hr>
               @endforeach
            </div>
         @endforeach

         Grand Total For Report: {{ secondsToHm($ungroupedEvents->sum('duration')) }}

      </div>

   </div>
@endsection
