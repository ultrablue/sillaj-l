<?php

namespace Database\Seeders;

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
        // Make the first user manually:
        factory(App\User::class)->states('firstUser')->create();
        factory(App\User::class, 5)->create();
    }
}
