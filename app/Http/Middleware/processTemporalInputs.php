<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Closure;

class processTemporalInputs
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     *
     * @throws \Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function handle($request, Closure $next)
    {
        if ($request->time_start || $request->time_end) {
            // Was there a : in the start time? If not, then attempt to get a time.
            if (strpos($request->time_start, ':') === false) {
                $request['time_start'] = $this->parseTimeWithNoColon($request->time_start);
            }

            // Do the same for end time.
            if (strpos($request->time_end, ':') === false) {
                $request['time_end'] = $this->parseTimeWithNoColon($request->time_end);
            }
        }

//        if ($request->duration && (preg_match('#^(\d{1,2})?([ :\.])?(\d{1,2})?$#', $request->duration, $matches))) {
//            $durationHours = (int)$matches[1];
//            $durationDivider = isset($matches[2]) ? $matches[2] : '';
//            // If there was a third match (minutes), use it, otherwise set it to 0.
//            if ($durationDivider === '.') {
//                $durationMinutes = (int)round(((float)('.' . $matches[3])) * 60);
//            } else {
//                $durationMinutes = isset($matches[3]) ? (int)$matches[3] : 0;
//
//            }
//
//            $carbonDuration = CarbonInterval::create(0, 0, 0, 0, $durationHours, $durationMinutes);
//        } else {
//            $carbonDuration = null;
//        }
//
//        $request['iso_8601_duration'] = $carbonDuration;
//        $request['duration'] = $carbonDuration;

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

    /**
     * Helps with the case where a time was entered without a colon.
     * Things may not be intuitive. Times wrap via modulo math. So 25 hours becomes 0100.
     * Minutes are treated similarly. So, 65 minutes becomes 5 minutes. Hours are NOT incrimented.
     * So, 25:65 becomes 01:05.
     *
     * If there are 1 or 2 digts, then it's assumed to be hours. In that case, modulo is used again to reduce it if needed.
     *
     * @param $timeString
     *
     * @return string|void
     */
    private function parseTimeWithNoColon($timeString)
    {
        if ($timeString === null) {
            return;
        }
        // This will match a three digit start time.
        // The assumption is that 123 becomes 01:23.
        preg_match('/(\d{1,2})(\d\d)/', $timeString, $matches);
        if ($matches) {
            $hours = $matches[1] % 24;
            $minutes = $matches[2] % 60;
        } else {
            $hours = $timeString % 24;
            $minutes = 0;
        }

        $hours = sprintf("%'.02d", $hours);
        $minutes = sprintf('%-02s', $minutes);

        $formattedTime = $hours.':'.$minutes;

        // dump($formattedTime);
        // dump($hours);
        // dd($minutes);

        return $formattedTime;
    }
}
