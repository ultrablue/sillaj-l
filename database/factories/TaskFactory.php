<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
       'user_id'        => '',
       'name'           => str_replace('.', '', $faker->text(20)),
       'description'    => $faker->sentence(),
       'display'        => TRUE,
       'use_in_reports' => $faker->boolean(50),
       'share'          => $faker->boolean(50)
    ];
});

// Make a meeting Task with non-random data.
// TODO Is there a way to make state do many of these so that there's not so much repitition?
$factory->state(App\Task::class, 'meeting', [
    'name'           => 'Meeting',
    'description'    => 'Meetings of all sorts.',
    'display'        => TRUE,
    'use_in_reports' => TRUE,
    'share'          => TRUE
]);

// Make a coding Task with non-random data.
$factory->state(App\Task::class, 'coding', [
    'name'           => 'Coding',
    'description'    => 'Writing code.',
    'display'        => TRUE,
    'use_in_reports' => TRUE,
    'share'          => TRUE
]);


// Make an annual leave task with non-random data.
$factory->state(App\Task::class, 'leaveAnnual', [
    'name'           => 'Leave, Annual',
    'description'    => 'Annual leave.',
    'display'        => TRUE,
    'use_in_reports' => TRUE,
    'share'          => TRUE
]);

// Make a sick leave Task with non-random data.
$factory->state(App\Task::class, 'leaveSick', [
    'name'           => 'Leave, Sick',
    'description'    => 'Sick leave.',
    'display'        => TRUE,
    'use_in_reports' => TRUE,
    'share'          => TRUE
]);


