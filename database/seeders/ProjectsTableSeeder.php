<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the Projects Table database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all the Users from the database.
        $users = User::all();

        Project::factory()->firstProject()->create(['user_id' => $users->first()->id]);

        // Seed Projects.
        $users->each(function ($user) {
            // Make some that have share set to TRUE.
            Project::factory()->count(2)->create(['user_id' => $user->id, 'share'=>TRUE]);
            // Make some that have share set to FALSE.
            Project::factory()->count(2)->create(['user_id' => $user->id, 'share' => FALSE]);
        });


        // Try this:
        // $tasks = get all Tasks for User and all shared Tasks.
        // Combine the results of the factories.
        // $userProjects = factory(...
        // $userProjects->combine(factory(...
        // Loop over the resulting collection
        // $userProjects->each( function($project use ($tasks) {
        //     The tasks() method is what knows about the many-to-many.
        //     See https://stackoverflow.com/questions/45269146/laravel-seeding-many-to-many-relationship for more.
        //     $project->tasks()->attach($tasks->random(rand(3,5))->pluck('id')...
        // });


    }
}
