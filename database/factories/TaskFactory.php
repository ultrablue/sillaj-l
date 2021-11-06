<?php

namespace Database\Factories;

// use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => '',
            'name' => str_replace('.', '', $this->faker->text(20)),
            'description' => $this->faker->sentence(),
            'display' => true,
            'use_in_reports' => $this->faker->boolean(50),
            'share' => $this->faker->boolean(50),
        ];
    }

    // Make a meeting Task with non-random data.
    public function meeting()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Meeting',
                'description' => 'Meetings of all sorts.',
                'display' => true,
                'use_in_reports' => true,
                'share' => true,
            ];
        });
    }

    // Return a coding Task with non-random data.
    public function coding()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Coding',
                'description' => 'Writing code.',
                'display' => true,
                'use_in_reports' => true,
                'share' => true,
            ];
        });
    }

    // Return an annual leave task with non-random data.
    public function leaveAnnual()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Leave, Annual',
                'description' => 'Annual leave.',
                'display' => true,
                'use_in_reports' => true,
                'share' => true,
            ];
        });
    }

    // Re a sick leave Task with non-random data.
    public function leaveSick()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Leave, Sick',
                'description' => 'Sick leave.',
                'display' => true,
                'use_in_reports' => true,
                'share' => true,
            ];
        });
    }
}
