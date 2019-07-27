<?php

namespace App\Observers;

use App\Event;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class EventObserver
{

    public function saving(Event $event)
    {

//        dump($event);
//        die();

        // This is here to help track an error that I'm seeing sometimes. If the interval seen here
        // Appears in the database then there was a problem. It means that none of the conditions
        // below were triggered. Which seems unpossible.
        $carbonDuration = CarbonInterval::create('PT0S');

        if ($event->time_start && $event->time_end) {
            $carbonStartTime = Carbon::parse($event->time_start);
            $carbonEndTime = Carbon::parse($event->time_end);
            $event->duration = $carbonEndTime->diffAsCarbonInterval($carbonStartTime)->total('seconds');
            dump($event);
//            dd("Calculate Duration");
        } elseif ($event->time_start && $event->duration) { // Calculate End Time.
            $carbonDuration = $this->parseDuration($event->duration);
            $carbonStartTime = Carbon::parse($event->time_start);
            $carbonEndTime = $carbonStartTime->add($carbonDuration);
            $event->time_end = $carbonEndTime->format("H:i");
            $event->duration = $carbonDuration->total('seconds');
//            dump("Time Start: " . $event->time_start);
//            dump("Duration: " . $event->duration);
//            dump($event);
//            dd("Calculate Time End");
        } elseif ($event->time_end && $event->duration) { // Calculate Start Time

            $carbonDuration = $this->parseDuration($event->duration);
            $carbonEndTime = Carbon::parse($event->time_end);
            $carbonStartTime = $carbonEndTime->sub($carbonDuration);
            $event->time_start = $carbonStartTime->format("H:i");
            $event->duration = $carbonDuration->total('seconds');

//            dump("Time End: " . $event->time_end);
//            dump("Duration: " . $event->duration);
//            dump($event);
//            dd("Calculate Time Start");
        } elseif ($event->duration) {
            $carbonDuration = $this->parseDuration($event->duration);
            $event->duration = $carbonDuration->total('seconds');
        }

        $event->iso_8601_duration = $carbonDuration->spec();
//        dump("Time Start: " . $event->time_start);
//        dump("Time End:   " . $event->time_end);
//        dump("Duration:   " . $event->duration);
//        dd("Case Not Handled!");

//        dd($event);

    }


    /**
     * Handle the event "created" event.
     *
     * @param \App\Event $event
     * @return void
     */
    public function created(Event $event)
    {
        //
    }

    /**
     * Handle the event "updated" event.
     *
     * @param \App\Event $event
     * @return void
     */
    public function updated(Event $event)
    {
        //
    }

    /**
     * Handle the event "deleted" event.
     *
     * @param \App\Event $event
     * @return void
     */
    public function deleted(Event $event)
    {
        //
    }

    /**
     * Handle the event "restored" event.
     *
     * @param \App\Event $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the event "force deleted" event.
     *
     * @param \App\Event $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }

    private function parseDuration($duration)
    {
        if ($duration && (preg_match('#^(\d{1,2})?([ :\.])?(\d{1,2})?$#', $duration, $matches))) {
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
            $carbonDuration = CarbonInterval::create(0, 0, 0, 0, 0, 0, 0, 0);
        }

        return $carbonDuration;
    }
}
