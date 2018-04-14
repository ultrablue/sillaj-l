<?php

// Projects Table Seeder.

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'user_id' => '', // This will be supplied by a Seeder.
        'name'           => str_replace('.', '', $faker->text(20)),
        'description'    => $faker->sentence(),
        'display'        => TRUE,
        'use_in_reports' => $faker->boolean(50),
        'share'          => $faker->boolean(50)
    ];
});
