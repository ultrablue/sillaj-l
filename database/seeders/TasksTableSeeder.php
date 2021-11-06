<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Task;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * TODO Add a note about the order that the Seeders need to run in, please.
     * TODO Maybe see if there's a way to check to make sure the dependencies have been run? So, check to make sure that First User exists, for example.
     *
     * @return void
     */
    public function run()
    {

        // First, create some specific Tasks for the first user, mainly for testing and consistancy during coding.
        // Note that this assumes that the first user is the one seeded by the UserTableSeeder.
        $firstUser = User::first();
        // Make a default 
        Task::factory()->meeting()->create(['user_id' => $firstUser->id]);
        Task::factory()->coding()->create(['user_id' => $firstUser->id]);
        Task::factory()->leaveAnnual()->create(['user_id' => $firstUser->id]);
        Task::factory()->leaveSick()->create(['user_id' => $firstUser->id]);

        // Now, make some Tasks for each User.
        // Get all the Users from the database.
        $users = User::all();

        // Seed each User's Tasks.
        $users->each(function ($user) {
            // Make some that have share set to TRUE.
            Task::factory()->count(5)->create(['user_id' => $user->id, 'share' => TRUE]);
            // Make some that have share set to FALSE.
            Task::factory()->count(5)->create(['user_id' => $user->id, 'share' => FALSE]);
        });
    }
}
