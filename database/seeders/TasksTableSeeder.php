<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $testUser = App\User::first();
        factory('App\Task')->states('meeting')->create(['user_id' => $testUser->id]);
        factory('App\Task')->states('coding')->create(['user_id' => $testUser->id]);
        factory('App\Task')->states('leaveAnnual')->create(['user_id' => $testUser->id]);
        factory('App\Task')->states('leaveSick')->create(['user_id' => $testUser->id]);

        // Get all the Users from the database.
        $users = App\User::all();

        // Seed Tasks.
        $users->each(function ($user) {
            // Make some that have share set to TRUE.
            factory('App\Task', 5)->create(['user_id' => $user->id, 'share' => TRUE]);
            // Make some that have share set to FALSE.
            factory('App\Task', 5)->create(['user_id' => $user->id, 'share' => FALSE]);
        });
    }
}
