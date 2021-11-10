<?php

namespace Database\Seeders;
use App\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make the first User, which has some specific things for testing and constistancy during development:
        User::factory()->firstUser()->create();
        User::factory()->count(5)->create();
    }
}
