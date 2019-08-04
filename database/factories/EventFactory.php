<?php

use Carbon\CarbonInterval;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Event::class, function (Faker $faker) {

    $startDate = Carbon::createFromTimeStamp($faker->dateTimeThisMonth('now')->getTimeStamp());
    $endDate = $startDate->copy()->addMinutes($faker->numberBetween(5, 180));
    $duration = $endDate->diffAsCarbonInterval($startDate);

    return [
        'user_id' => '',
        'time_start' => $startDate->toTimeString(),
        'time_end' => $endDate->toTimeString(),
        'duration' => $duration->spec(),
        'event_date' => $startDate->toDateString(),
        'note' => $faker->paragraph(3, TRUE)
    ];
});


$factory->state(App\Event::class, 'duration', function (Faker $faker) {
    $duration = CarbonInterval::create(0, 0, 0, 0,
        $faker->numberBetween(0, 7), $faker->numberBetween(1, 59),
        0, 0);

    return [
        'duration' => $duration->spec(),
        'time_start' => NULL,
        'time_end' => NULL,

    ];
});


