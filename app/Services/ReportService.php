<?php

namespace App\Services;

use Carbon\CarbonImmutable;

use Illuminate\Http\Request;


class ReportService
{

    public function startAndEndDates(Request $request): array
    {

        // If start-date and end-date are present in the Request, return them.

        // now will be used a few times.
        // If 'now' is present in the request, as it will be for testing, then use it's value. Otherwise use the current now.
        $now = $request['now'] ? new CarbonImmutable($request['now']) : new CarbonImmutable();

        // XXX Next: start working on the view for testng.

        switch ($request['predefined-range']) {
            case 'previous-month':
                $startDate = $now->startOfMonth()->subMonthsNoOverflow();
                $endDate = $now->subMonthsNoOverflow()->endOfMonth();
                break;
            case 'month-to-date':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now;
                break;
            case 'year-to-date':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now;
                break;
            case 'all-time':
                // $startDate = new CarbonImmutable(Event::where('user_id', '=', auth()->user()->id)->min('event_date'));
                // $endDate = new CarbonImmutable(Event::where('user_id', '=', auth()->user()->id)->max('event_date'));
                break;
            case 'custom':
                // TODO Validate these!!!!
                $startDate = new CarbonImmutable($request['start_date']);
                $endDate = new CarbonImmutable($request['end_date']);
                // if ($group === 'project') {
                //     $eventsCollection = Event::rollupByProject($startDate, $endDate);
                // } elseif ($group === 'task') {
                //     $eventsCollection = Event::rollupByTask($startDate, $endDate);
                // }
                break;
            case 'last-week':
                $startDate = $now->startOfWeek()->subDays(7);
                $endDate = $startDate->endOfWeek();
                break;
            default:
            case 'this-week':
                $startDate = $now->startOfWeek();
                $endDate = $now->endOfWeek();
                break;
        }



        return ['start-date' => $startDate, 'end-date' => $endDate];
    }
}
