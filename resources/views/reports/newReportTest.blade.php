@extends('layouts.app')

@section('content')
   <h1 class="text-4xl mb-8">Report</h1>

   {{-- @dd($groupedEvents) --}}
   {{-- @dd($projectTotals['Dolore est iste']->total_duration) --}}

   <div>
      @foreach ($groupedEvents as $project => $tasks)
         <div>
            Project: {{ $project }}
         </div>

         <div class="mb-10">
            @foreach ($tasks as $taskName => $events)
               <div>Task: {{ $taskName }}</div>
               <div>Sum: {{ $events->sum('duration') }}</div>
               <div>Percentage: {{ ($events->sum('duration') / $ungroupedEvents->sum('duration')) * 100 }}</div>
               <div>Duration (decimal): {{ sprintf('%.2f',round(Carbon\CarbonInterval::seconds($events->sum('duration'))->cascade()->total('hours'),2)) }}</div>
               <div>Duration (hh:mm): {{ secondstoHm($events->sum('duration')) }}</div>
               <div>Project All Time Total Duration: {{ secondsToHm($projectTotals[$project]->total_duration) }} ({{ $projectTotals[$project]->total_duration }})</div>
               <hr>
            @endforeach
         </div>
      @endforeach

      Grand Total For Report: {{ secondsToHm($ungroupedEvents->sum('duration')) }}

   </div>


   <div>
      {{-- @dump($projectTotals) --}}
   </div>
@endsection
