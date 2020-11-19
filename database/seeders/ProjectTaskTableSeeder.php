<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectTaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = App\Project::all();
        $standardTasks = App\Task::take(4)->get();
        $projects->each(function($project) use ($standardTasks) {
            $project->tasks()->attach($standardTasks->random(2,4));
            $otherUsersSharedTasks=App\Task::where([
                ['share', '=', 'TRUE'],
                ['user_id', '<>', $project->user_id]
            ])->get();
           $project->tasks()->attach($otherUsersSharedTasks->random(2,4));
        });
    }
}
