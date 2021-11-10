<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // The password for test users is 'secret' (no quotes).
            'remember_token' => str_random(10),
        ];
    }

    public function firstUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'First User',
                'email' => 'firstuser@example.com'
            ];
        });
    }
}
