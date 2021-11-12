<?php

namespace Database\Factories;



// Projects Table Factory.

// use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{

    public function definition()
    {



        return [
            //'user_id' => '', // This *must* be supplied by the caller!
            'name'           => str_replace('.', '', $this->faker->text(20)),
            'description'    => $this->faker->sentence(),
            'display'        => TRUE,
            'use_in_reports' => $this->faker->boolean(50),
            'share'          => $this->faker->boolean(50)
        ];
    }

    public function firstProject()
    {
        return $this->state(function (array $aatributes) {
            return [
                'name'           => 'sillaj-l Test Project',
                'description'    => 'This is a static Test Project used for unit tests, etc.',
                'display'        => TRUE,
                'use_in_reports' => TRUE,
                'share'          => TRUE
            ];
        });
    }
}
