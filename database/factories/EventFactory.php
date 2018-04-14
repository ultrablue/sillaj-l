<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Event::class, function (Faker $faker) {

    $startDate = Carbon::createFromTimeStamp($faker->dateTimeThisMonth('now')->getTimeStamp());
    $endDate = $startDate->copy()->addMinutes($faker->numberBetween(5,120));

    return [
        'user_id'    => '',
        'time_start' => $startDate->toTimeString(),
	'time_end'   => $endDate->toTimeString(),
	'duration'   => $endDate->diff($startDate)->format('%H:%I:%S'),
	'event_date' => $startDate->toDateString(), 
	'note'       => $faker->paragraph(3, TRUE) 
    ];
});


$factory->state(App\Event::class, 'duration', function (Faker $faker) {
    $duration = Carbon::createFromTime($faker->numberBetween(0,2), $faker->numberBetween(1,59));

    return [
        'duration'   => $duration->toTimeString(),
        'time_start' => NULL,
        'time_end'   => NULL,

    ]; 
});


