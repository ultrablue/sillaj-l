<?php

namespace Database\Factories;

use Carbon\Carbon;
use Carbon\CarbonInterval;
// use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition()
    {
        // I think the idea here is to select a random date within the next two weeks.
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeThisMonth('+2 week')->getTimeStamp());
        // This bit sets the time to a multiple of 5 minutes.
        $resolution = 5;
        $s = $resolution * 60;
        $startDate->setTimestamp($s * (int) ceil($startDate->getTimestamp() / $s));
        // The idea here is to keep the durations to increments of 5 minutes. This isn't strictly necessary, 
        // but it makes the data easier to check visually. And it's more realstic. I don't think I've ever
        // made an Event with any time that wasn't rounded to the nearest 5 minutes.
        // In this case, the minimum and maximum will be:
        //    - 3 * 5 = 15
        //    - 48 * 5 = 240 minutes = 4 hours
        $eventDuration = $this->faker->numberBetween(3, 40) * 5;
        $endDate = $startDate->copy()->addMinutes($eventDuration);
        // TODO this is redundant, no?
        $duration = $endDate->diffAsCarbonInterval($startDate);

        return [
            'user_id' => '',
            'time_start' => $startDate->toTimeString(),
            'time_end' => $endDate->toTimeString(),
            'duration' => $duration->spec(),
            'event_date' => $startDate->toDateString(),
            'note' => $this->faker->paragraph(3, true),
        ];
    }

    public function duration()
    {
        // return $this->state(App\Event::class, 'duration', function (Faker $this->faker) {

        return $this->state(function (array $attributes) {
            // See above for an explanation.
            $eventDuration = $this->faker->numberBetween(3, 40) * 5;

            // years, months, weeks, days, hours, minutes, seconds, microseconds
            $duration = CarbonInterval::create(0, 0, 0, 0, 0, $eventDuration, 0, 0);

            return
                [
                    'duration' => $duration->spec(),
                    'time_start' => null,
                    'time_end' => null,
                ];
        });
    }
}
