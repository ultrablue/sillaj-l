<?php

// Projects Table Factory.

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        //'user_id' => '', // This *must* be supplied by the caller!
        'name'           => str_replace('.', '', $faker->text(20)),
        'description'    => $faker->sentence(),
        'display'        => TRUE,
        'use_in_reports' => $faker->boolean(50),
        'share'          => $faker->boolean(50)
    ];
});

$factory->state(App\Project::class, 'firstProject', [
    'name'           => 'sillaj-l Test Project',
    'description'    => 'This is a static Test Project used for unit tests, etc.',
    'display'        => TRUE,
    'use_in_reports' => TRUE,
    'share'          => TRUE
]);



