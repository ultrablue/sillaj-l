<?php

namespace Database\Factories;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition()
    {
        // $factory->define(App\Event::class, function (Faker $this->faker) {
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeThisMonth('now')->getTimeStamp());
        $endDate = $startDate->copy()->addMinutes($this->faker->numberBetween(5, 180));
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
            $duration = CarbonInterval::create(0, 0, 0, 0, $this->faker->numberBetween(0, 7), $this->faker->numberBetween(1, 59), 0, 0);

            return
            [
                'duration' => $duration->spec(),
                'time_start' => null,
                'time_end' => null,
            ];
        });
    }
}
