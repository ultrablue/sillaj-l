<?php

namespace App\Http\Middleware;

use Closure;

use Symfony\Component\HttpFoundation\ParameterBag;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class processTemporalInputs
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {


        if ($request->duration && (preg_match('#^(\d{1,2})?([ :\.])?(\d{1,2})?$#', $request->duration, $matches))) {
            $durationHours = (int)$matches[1];
            $durationDivider = isset($matches[2]) ? $matches[2] : '';
            // If there was a third match (minutes), use it, otherwise set it to 0.
            if ($durationDivider === '.') {
                $durationMinutes = (int)round(((float)('.' . $matches[3])) * 60);
            } else {
                $durationMinutes = isset($matches[3]) ? (int)$matches[3] : 0;

            }

            $carbonDuration = CarbonInterval::create(0, 0, 0, 0, $durationHours, $durationMinutes);
        } else {
            $carbonDuration = null;
        }

        $request['iso_8601_duration'] = $carbonDuration;
        $request['duration'] = $carbonDuration;

//
//        $request['carbon_time_start'] = NULL;
//        if ($request->time_start) {
//            $request['carbon_time_start'] = new Carbon($request->time_start);
//        }
//
//        $request['carbon_time_end'] = NULL;
//        if ($request->time_end) {
//            $request['carbon_time_end'] = new Carbon($request->time_end);
//        }


        // The truth table is this:
        // If there's a start_time and an end_time, calculate the duration, even if the user provided a duration.
        // If there's a start_time and duration, calculate the end_time.
        // If there's an end_time and a duration, calculate the start_time.
        // Otherwise, do nothing.
//        if ($request->carbon_time_start && $request->carbon_time_end) {
//            $request['iso_8601_duration'] = $request->carbon_time_end->diffAsCarbonInterval($request->carbon_time_start);
//        } elseif (!$request->carbon_time_start && $request->carbon_time_end && $request->iso_8601_duration) {
//            $request['carbon_time_start'] = $request->carbon_time_end->copy()->sub($request->iso_8601_duration);
//        } elseif (!$request->carbon_time_end && $request->carbon_time_start && $request->iso_8601_duration) {
//            $request['carbon_time_end'] = $request->carbon_time_start->copy()->add($request->iso_8601_duration);
//        }

//        dump($request->iso_8601_duration);
//        dd($request->all());

        return $next($request);
    }
}
